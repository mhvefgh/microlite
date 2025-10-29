<?php
namespace App\Models;

use Src\Core\Model;

class User extends Model {
    protected string $table = 'users';

    public function findByEmail(string $email) {
        $stmt = self::pdo()->prepare('SELECT * FROM `users` WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }
}
