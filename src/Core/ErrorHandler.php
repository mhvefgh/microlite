<?php
namespace Src\Core;

class ErrorHandler {
    public static function register() {
        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
    }

    public static function handleException(\Throwable $e) {
        http_response_code(500);
        echo "<h2>Exception:</h2><pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    }

    public static function handleError($severity, $message, $file, $line) {
        http_response_code(500);
        echo "<h2>Error:</h2><pre>$message in $file:$line</pre>";
    }
}
