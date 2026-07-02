<?php
namespace Wandervafla\PhpBlog\Actions\Security;

use Exception;

class ValidateCsrfAction
{
    public function validateCSRFToken()
    {
        if (
            !isset($_POST["csrf_token"]) ||
            !isset($_SESSION["csrf_token"]) ||
            !hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"])
        ) {
            http_response_code(403);
            throw new Exception('CSRF token validation failed');
        }
        return true;
    }
}