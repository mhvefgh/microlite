<?php
if (!function_exists('array_get')) {
    function array_get(array $array, string $key, $default = null) {
        return $array[$key] ?? $default;
    }
}
if (!function_exists('array_has')) {
    function array_has(array $array, string $key) {
        return array_key_exists($key, $array);
    }
}
