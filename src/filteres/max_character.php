<?php
function filter_max_characters(string $value, int $max_characters)
{
    if (!filter_var($value, FILTER_CALLBACK, [
            "options" => function () use ($value, $max_characters) {
                if (strlen($value) > $max_characters) {
                    return false;
                } else {
                    return true;
                }
            },
        ])
    ) {
        die("Oversize title!");
    }
}
