<?php
// Escape for HTML output (XSS protection)
if (!function_exists('e')) {
    function e($value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

// Safe input retrieval with optional filter
if (!function_exists('input')) {
    /**
     * Get input value from PHP superglobals in a controlled manner.
     * $source: 'post', 'get', 'cookie', 'server'
     */
    function input(string $key, $default = null, string $source = 'request') {
        $source = strtolower($source);
        switch ($source) {
            case 'post':
                $val = $_POST[$key] ?? $default;
                break;
            case 'get':
                $val = $_GET[$key] ?? $default;
                break;
            case 'cookie':
                $val = $_COOKIE[$key] ?? $default;
                break;
            case 'server':
                $val = $_SERVER[$key] ?? $default;
                break;
            default:
                $val = $_REQUEST[$key] ?? $default;
        }
        return is_string($val) ? trim($val) : $val;
    }
}

// Generate random token for CSRF and similar uses
if (!function_exists('generate_token')) {
    function generate_token(int $length = 32): string {
        return bin2hex(random_bytes($length));
    }
}
if (!function_exists('auth')) {
    function auth(): ?array {
        return \Src\Core\Security\AuthUserProvider::user();
    }
}