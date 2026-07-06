<?php
namespace Wandervafla\PhpBlog\Filteres;

use Exception;

class PasswordComplexityFilter
{
    private static $pattern = '/^(?=.*[az])(?=.*[AZ])(?=.*\d)(?=.*[\W_]).{8,}$/';

    public function __invoke(string $password)
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
