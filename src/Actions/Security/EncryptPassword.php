<?php
namespace Wandervafla\PhpBlog\Actions\Security;

class EncryptPassword
{
    public static function encrypt(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}
?>