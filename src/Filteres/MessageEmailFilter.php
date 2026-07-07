<?php
namespace Wandervafla\PhpBlog\Filteres;

class MessageEmailFilter
{
    public function __invoke(array &$errors, string $email)
    {
        if (empty($email)) {
            $errors['email'] = MESSAGE_EMAIL_REQUIRE;
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = MESSAGE_EMAIL_VALIDATE;
        }
    }
}