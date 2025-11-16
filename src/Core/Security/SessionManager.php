<?php
namespace Src\Core\Security;

/**
 * Secure Session Manager class
 * Handles secure session start, get/set/remove, and regeneration.
 */
class SessionManager
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.use_strict_mode', '1');
            ini_set('session.use_only_cookies', '1');
            ini_set('session.use_trans_sid', '0');

            $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? 0) == 443;
            $cookieParams = session_get_cookie_params();

            session_set_cookie_params([
                'lifetime' => 0,
                'path' => $cookieParams['path'] ?? '/',
                'domain' => $cookieParams['domain'] ?? '',
                'secure' => $secure,
                'httponly' => true,
                'samesite' => 'Lax', //Strict or Lax
            ]);

            session_start();
        }
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        if (session_status() !== PHP_SESSION_NONE) {
            session_unset();
            session_destroy();
        }
    }

    public static function regenerate(bool $deleteOld = true): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id($deleteOld);
        }
    }
    /**
     * Flash a message (one-time session)
     */
    public static function flash(string $key, string $value = null): mixed
    {
        self::start();

        // Setter mode
        if ($value !== null) {
            $_SESSION['_flash'][$key] = $value;
            return null;
        }

        // Getter mode (return and remove)
        if (isset($_SESSION['_flash'][$key])) {
            $message = $_SESSION['_flash'][$key];
            unset($_SESSION['_flash'][$key]);
            return $message;
        }

        return null;
    }
}
