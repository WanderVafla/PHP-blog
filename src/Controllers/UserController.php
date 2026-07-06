<?php
namespace Wandervafla\PhpBlog\Controllers;

use PDOException;
use Wandervafla\PhpBlog\Actions\Security\ValidateCsrfAction;
use Wandervafla\PhpBlog\Models\Users;

class UserController
{
    private static $viewPageDir = __DIR__ . "/../Views/Pages/";
    
    public function create()
    {
        $users = new Users();
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            new ValidateCsrfAction();

            $name = $_POST["name"];
            $email = $_POST["email"];
            $password = $_POST["password"];

            try {
                $users->insert(
                    name: $name,
                    email: $email,
                    password: password_hash($password, PASSWORD_BCRYPT),
                );
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }
        require self::$viewPageDir . 'SingUp.php';
    }
}
