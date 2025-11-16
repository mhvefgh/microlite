<?php

namespace Src\Core;

use Psr\Log\LoggerInterface;

class ErrorHandler
{
    private $logger;
    private bool $displayDetails;

    public function __construct(?LoggerInterface $logger = null, bool $displayDetails = false)
    {
        $this->logger = $logger;
        $this->displayDetails = $displayDetails;
    }

    public function register(): void
    {
        set_exception_handler([$this, 'handleException']);
        set_error_handler([$this, 'handleError']);
    }

    public function handleException($e): void
    {
        if ($this->logger) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);
        }
        if ($this->displayDetails) {
            http_response_code(500);
            echo '<pre>' . htmlspecialchars((string)$e, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</pre>';
            return;
        }
        http_response_code(500);
        echo 'Internal Server Error';
    }

    public function handleError($errno, $errstr, $errfile, $errline): bool
    {
        $message = sprintf('PHP Error: [%d] %s in %s on line %d', $errno, $errstr, $errfile, $errline);
        if ($this->logger) {
            $this->logger->error($message);
        }
        // Let PHP internal handler run if necessary
        return false;
    }
}
