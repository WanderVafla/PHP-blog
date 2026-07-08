<?php
namespace Wandervafla\PhpBlog\Core;

use Wandervafla\PhpBlog\Filteres\PasswordComplexityFilter;

class DisplayErrors
{
    public static function emptyPasswordEmail(
        array &$errors,
        ?string $email,
        ?string $password,
    ) {
        if (empty($email)) {
            $errors["email"] = MESSAGE_EMAIL_REQUIRE;
        }
        if (empty($password)) {
            $errors["password"] = MESSAGE_PASSWORD_REQUIRE;
        }
    }
    public static function checkLogin(array &$errors, ?array &$userData)
    {
        if (empty($userData)) {
            $errors["form"] = MESSAGE_LOGIN_FAILED;
        }
    }
    public static function checkSingup(
        array &$errors,
        ?string &$name,
        ?string &$email,
        ?string &$password,
        ?string &$confirmPassword,
    ) {

        
        if (empty($name)) {
            $errors["username"] = MESSAGE_USERNAME_REQUIRE;
        } elseif (str_word_count($name) > 1) {
            $errors["username"] = MESSAGE_USERNAME_VALIDATE;
        }
        
        self::emptyPasswordEmail(errors: $errors, email: $email, password: $password);

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = MESSAGE_EMAIL_VALIDATE;
        }

        if (!empty($password) && !PasswordComplexityFilter::validate($password)) {
            $errors["password"] = MESSAGE_PASSWORD_VALIDATE_COMPLEXITY;
        }

        if (empty($confirmPassword)) {
            $errors["confirm-password"] = MESSAGE_CONFITM_PASSWORD_REQUIRE;
        } elseif ($password !== $confirmPassword) {
            $errors["confirm-password"] = MESSAGE_CONFITM_PASSWORD_VALIDATE;
        }
    }
}
