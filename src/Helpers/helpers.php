<?php

/**
 * ------------------------------------------------------------
 * Global Helper Functions
 * ------------------------------------------------------------
 * These functions are auto-loaded and available everywhere.
 * Similar to Laravel-style helpers but lightweight and framework-agnostic.
 */

// ------------------------------------------------------------
// Environment Helper
// ------------------------------------------------------------
if (!function_exists('env')) {
    /**
     * Retrieve an environment variable with optional default.
     */
    function env(string $key, $default = null)
    {
        $value = $_ENV[$key] ?? getenv($key);

        if ($value === false || $value === null) {
            return $default;
        }

        // Normalize boolean & null values
        $lower = strtolower($value);
        return match ($lower) {
            'true', '(true)'   => true,
            'false', '(false)' => false,
            'null', '(null)'   => null,
            'empty', '(empty)' => '',
            default            => $value,
        };
    }
}

// ------------------------------------------------------------
// Debug Helper
// ------------------------------------------------------------
if (!function_exists('dd')) {
    /**
     * Dump and Die - clean HTML version
     */
    function dd(...$vars): never
    {
        echo '<pre style="background:#f7f7f9;color:#333;padding:10px;
              border-radius:6px;font-family:Menlo,Consolas,monospace;
              border:1px solid #ddd;overflow:auto;">';
        foreach ($vars as $var) {
            var_dump($var);
        }
        echo '</pre>';
        exit(1);
    }
}

// ------------------------------------------------------------
// View Helper
// ------------------------------------------------------------
if (!function_exists('view')) {
    /**
     * Render a view template with optional layout.
     *
     * Usage:
     *   return view('home', ['title' => 'Welcome']);
     *   return view('admin.dashboard', $data, 'layouts.app');
     */
    function view(string $template, array $data = [], ?string $layout = null): string
    {
        return \Src\Core\View::make($template, $data, $layout);
    }
}

// ------------------------------------------------------------
// Partial View Helper
// ------------------------------------------------------------
if (!function_exists('partial')) {
    /**
     * Include a reusable partial view.
     *
     * Example:
     *   <?= partial('partials.navbar', ['user' => $user]) ?>
     */
    function partial(string $partial, array $data = []): void
    {
        \Src\Core\View::partial($partial, $data);
    }
}

// ------------------------------------------------------------
// Asset Helper (useful for versioned or CDN assets)
// ------------------------------------------------------------
if (!function_exists('asset')) {
    /**
     * Generate an absolute URL to an asset.
     */
    function asset(string $path): string
    {
        $base = rtrim(env('APP_URL', ''), '/');
        return $base . '/' . ltrim($path, '/');
    }
}


// ------------------------------------------------------------
// Session & Flash Helpers (Global Access)
// ------------------------------------------------------------
use Src\Core\Security\SessionManager;

// Ensure session is started only once
SessionManager::start();

if (!function_exists('flash')) {
    /**
     * Flash message helper (set or get once)
     * Example:
     *   flash('error', 'Invalid login');
     *   $msg = flash('error');
     */
    function flash(string $key, string $value = null): mixed
    {
        return SessionManager::flash($key, $value);
    }
}

if (!function_exists('session')) {
    /**
     * Global session helper similar to Laravel's session()
     * Example:
     *   session('user'); // get
     *   session(['user' => $data]); // set
     */
    function session(string|array $key, mixed $value = null): mixed
    {
        SessionManager::start();

        if (is_array($key)) {
            foreach ($key as $k => $v) {
                SessionManager::set($k, $v);
            }
            return null;
        }

        return SessionManager::get($key, $value);
    }
}


function e($string) {
    return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
}

