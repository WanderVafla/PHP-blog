<?php
namespace Wandervafla\PhpBlog\Filteres;

use Exception;

class MaxStrlenFilter
{
    public function __construct(string $value, int $max_characters)
    {
        if (!filter_var($value, FILTER_CALLBACK, [
                "options" => function () use ($value, $max_characters) {
                    if (\mb_strlen($value) > $max_characters) {
                        return false;
                    } else {
                        return true;
                    }
                },
            ])
        ) {
            throw new Exception('Oversize title!');
        }
    }

}
