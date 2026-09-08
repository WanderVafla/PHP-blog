<?php

namespace Wandervafla\PhpBlog\Filteres;

class PasswordComplexityFilter
{
    private static $pattern = '/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[\W_]).{8,}$/';

    public static function validate(string $password)
    {
        if (
            filter_var($password, FILTER_CALLBACK, [
                "options" => function ($value) {
                    if (preg_match(self::$pattern, $value)) {
                        return true;
                    }
                    return false;
                },
            ])
        ) {
            return true;
        }
        return false;
    }
}
