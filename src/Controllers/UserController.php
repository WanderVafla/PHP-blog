<?php
namespace Wandervafla\PhpBlog\Controllers;

use PDOException;
use Wandervafla\PhpBlog\Actions\Security\ValidateCsrfAction;
use Wandervafla\PhpBlog\Core\DisplayErrors;
use Wandervafla\PhpBlog\Filteres\XssFilter;
use Wandervafla\PhpBlog\Models\Users;
use Wandervafla\PhpBlog\Core\Session;

class UserController
{
    private static $viewPageDir = __DIR__ . "/../Views/Pages/";

    private Users $users;
    private XssFilter $xssFilter;

    public function __construct()
    {
        $this->users = new Users();
        $this->xssFilter = new XssFilter();
    }
    public function singout()
    {
            Session::destroySession();
            $_SESSION["last_action"] = FLASH_MESSAGE_SINOUT;
            header("Location: /");
            exit();
    }
    public function auth(string $authAction)
    {
        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            ValidateCsrfAction::validate();

            $email = ($this->xssFilter)($_POST["email"]);
            $password = trim($_POST["password"]);

            DisplayErrors::emptyPasswordEmail(
                $errors,
                email: $email,
                password: $password,
            );

            if ($authAction === "login") {
                $userData = $this->users->select(email: $email);
                DisplayErrors::checkLogin($errors, userData: $userData);
                if (empty($errors)) {
                    if (password_verify($password, $userData["password"])) {
                        $_SESSION["user_id"] = $userData["id"];
                        $_SESSION["username"] = $userData["name"];
                        $_SESSION["role"] = $userData['role'];
                        $_SESSION["last_action"] = FLASH_MESSAGE_LOGGED;
                        header("Location: /");
                        exit();
                    } else {
                        DisplayErrors::checkLogin(
                            errors: $errors,
                            userData: $userData,
                        );
                    }
                }
            }

            if ($authAction === "singup") {
                $name = ($this->xssFilter)($_POST["username"]);
                $password = trim($_POST["password"]);
                $confirmPassword = trim($_POST["confirm-password"]);

                DisplayErrors::checkSingup(
                    errors: $errors,
                    name: $name,
                    email: $email,
                    password: $password,
                    confirmPassword: $confirmPassword,
                );

                try {
                    if (empty($errors)) {
                        $this->users->insert(
                            name: $name,
                            email: $email,
                            password: password_hash($password, PASSWORD_BCRYPT),
                        );
                        $_SESSION["last_action"] = FLASH_MESSAGE_SINGUP;
                        header("Location: /login");
                        exit();
                    }
                } catch (PDOException $e) {
                    $errorMessage = $e->getMessage();
                    if (str_contains($errorMessage, "users.name")) {
                        $errors["username"] = MESSAGE_USERNAME_EXIST;
                    }
                    if (str_contains($errorMessage, "users.email")) {
                        $errors["email"] = MESSAGE_EMAIL_EXIST;
                    }
                }
            }
        }
        if ($authAction === "singup") {
            require self::$viewPageDir . "SingUp.php";
        }
        if ($authAction === "login") {
            require self::$viewPageDir . "Login.php";
        }
    }
}
