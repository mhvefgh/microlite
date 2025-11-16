<?php

class CsrfMiddleware
{
    // You can adjust where tokens are stored or how they're passed (header or form field)
    private $tokenKey = '_csrf_token';
    private $formField = '_csrf';

    public function handle($request, $next)
    {
        // Only validate state-changing methods
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            $token = $_POST[$this->formField] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            $sessionToken = $_SESSION[$this->tokenKey] ?? null;
            if (empty($sessionToken) || !is_string($token) || !hash_equals($sessionToken, (string)$token)) {
                http_response_code(403);
                // In production, return a generic error; in development you may want more info.
                echo 'CSRF validation failed';
                exit;
            }
        }
        return $next($request);
    }

    // Helper: ensure a token exists in session and return it for forms
    public function getToken(): string
    {
        if (empty($_SESSION[$this->tokenKey])) {
            $_SESSION[$this->tokenKey] = bin2hex(random_bytes(32));
        }
        return $_SESSION[$this->tokenKey];
    }
}
