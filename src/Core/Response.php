<?php
namespace Src\Core;
use Src\Core\Security\SessionManager;

class Response
{
    private int $status = 200;
    private array $headers = [];
    private string $body = '';

    public function status(int $code): self
    {
        $this->status = $code;
        return $this;
    }

    public function header(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function json($data, int $status = 200): self
    {
        $this->status = $status;
        $this->header('Content-Type', 'application/json; charset=utf-8');
        $this->body = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $this;
    }

    public function send(string $body = null): void
    {
        if ($body !== null) {
            $this->body = $body;
        }

        if (!headers_sent()) {
            http_response_code($this->status);
            foreach ($this->headers as $name => $value) {
                header("$name: $value", true);
            }
        }

        echo $this->body;
    }

    /**
     * Redirect to another URL (simple redirect)
     */
    public function redirect(string $url, int $status = 302): void
    {
        if (!headers_sent()) {
            header("Location: {$url}", true, $status);
        }
        exit;
    }

    /**
     * Laravel-style redirect with flash message
     * Example:
     *     return Response::withError('Invalid credentials', '/login');
     *     return Response::withSuccess('Welcome!', '/home');
     */
    public static function withError(string $message, string $url): void
    {
        SessionManager::start();
        $_SESSION['_flash'] = ['error' => $message];
        header("Location: {$url}");
        exit;
    }

    public static function withSuccess(string $message, string $url): void
    {
        SessionManager::start();
        $_SESSION['_flash'] = ['success' => $message];
        header("Location: {$url}");
        exit;
    }

    // Compatibility aliases
    public function setStatusCode(int $code): self
    {
        return $this->status($code);
    }

    public function setBody(string $content): self
    {
        $this->body = $content;
        return $this;
    }
}
