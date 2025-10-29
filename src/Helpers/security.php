<?php
if (!function_exists('sanitize')) {
    function sanitize($data) {
        return is_array($data) ? array_map('sanitize', $data) : htmlspecialchars(strip_tags($data));
    }
}
if (!function_exists('csrf_token')) {
    function csrf_token() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['_csrf'])) $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        return $_SESSION['_csrf'];
    }
}
if (!function_exists('verify_csrf')) {
    function verify_csrf($token): bool {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return isset($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], $token);
    }
}
