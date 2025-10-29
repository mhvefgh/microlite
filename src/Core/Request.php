<?php
namespace Src\Core;

class Request
{
    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function uri(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        // Remove query string
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        // Remove index.php if present
        $uri = str_replace('/index.php', '', $uri);

        return rtrim($uri, '/') ?: '/';
    }

    public function input(string $key = null)
    {
        $data = array_merge($_GET, $_POST);
        return $key ? ($data[$key] ?? null) : $data;
    }

    /**
     * Retrieve authenticated user (if available)
     */
    public function user(): ?array
    {
        // Start session if not started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Example: store user info in $_SESSION['user']
        return $_SESSION['user'] ?? null;
    }

    /**
     * Check if a user is authenticated
     */
    public function isAuthenticated(): bool
    {
        return $this->user() !== null;
    }
}
