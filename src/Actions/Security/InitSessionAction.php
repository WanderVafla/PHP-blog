<?php
namespace Wandervafla\PhpBlog\Actions\Security;

class InitSessionAction
{
    public function __invoke()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION["csrf_token"])) {
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        }
    }
}
