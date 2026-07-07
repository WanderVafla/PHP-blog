<?php
namespace Wandervafla\PhpBlog\Filteres;

class MessageEmailFilter
{
    public function __invoke(string $email)
    {
        if (empty($email)) {
            return MESSAGE_EMAIL_REQUIRE;
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return MESSAGE_EMAIL_VALIDATE;
        }
    }
}