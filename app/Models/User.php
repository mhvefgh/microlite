<?php
namespace App\Models;

use Src\Core\Model;

class User extends Model
{
    protected string $table = 'users';
    protected string $primaryKey = 'id';

    protected array $fillable = ['name', 'family', 'national_id', 'mobile', 'email', 'username', 'password', 'shahrdari_id', 'role', 'accept', 'position', 'address', 'postal_code'];

    protected array $hidden = ['password'];

}
