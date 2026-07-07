<?php
namespace Wandervafla\PhpBlog\Actions;

class MessageErrorAction
{
    public function __invoke(array &$errors, string $key)
    {
        return htmlspecialchars($errors[$key] ?? "");
    }
}