<?php
namespace Src\Core\Security;

use App\Models\User;
use Src\Core\Security\SessionManager;

class AuthUserProvider
{
    private static ?array $user = null;

    public static function init(): void
    {
        SessionManager::start();

        $sessionUser = SessionManager::get('user');
        if ($sessionUser && isset($sessionUser['id'])) {
            $user = User::find($sessionUser['id']);
            if ($user) {
                self::$user = [
                    'id' => $user->id,
                    'username' => $user->username,
                    'role' => $user->role,
                ];
            } else {
                SessionManager::remove('user');
            }
        }
    }

    public static function user(): ?array
    {
        return self::$user;
    }

    public static function check(): bool
    {
        return self::$user !== null;
    }

    public static function logout(): void
    {
        SessionManager::remove('user');
        SessionManager::destroy();
    }
}
