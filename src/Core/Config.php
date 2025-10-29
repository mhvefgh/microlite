<?php
namespace Src\Core;

class Config {
    public static function get(string $key, $default = null) {
        $file = __DIR__ . '/../../config/app.php';
        $config = file_exists($file) ? include $file : [];
        return $config[$key] ?? $default;
    }
}
