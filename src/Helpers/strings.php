<?php
if (!function_exists('str_random')) {
    function str_random($length = 16) {
        return substr(bin2hex(random_bytes($length)), 0, $length);
    }
}
if (!function_exists('str_slug')) {
    function str_slug($string) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
        return trim($slug, '-');
    }
}
