<?php
if (!function_exists('env')) {
    function env(string $key, $default = null) {
        return $_ENV[$key] ?? getenv($key) ?: $default;
    }
}
if (!function_exists('view')) {
    function view(string $template, array $data = []) {
        \Src\Core\View::render($template, $data);
    }
}
if (!function_exists('dd')) {
    function dd(...$vars) {
        echo '<pre>'; var_dump(...$vars); echo '</pre>'; die();
    }
}
