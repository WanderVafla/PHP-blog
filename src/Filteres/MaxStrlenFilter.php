<?php
namespace Wandervafla\PhpBlog\Filteres;

class MaxStrlenFilter
{
    public function __invoke(string $value, int $max_characters)
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
            return false;
        }
        return true;
    }

}
