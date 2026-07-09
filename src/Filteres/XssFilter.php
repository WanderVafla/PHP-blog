<?php
namespace Wandervafla\PhpBlog\Filteres;

class XssFilter
{
    public function __invoke($data)
    {
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8', false);
        return $data;
    }
}