<?php
namespace Wandervafla\PhpBlog\Controllers;

use PDOException;
use Wandervafla\PhpBlog\Actions\Security\ValidateCsrfAction;
use Wandervafla\PhpBlog\Filteres\MessageEmailFilter;
use Wandervafla\PhpBlog\Filteres\PasswordComplexityFilter;
use Wandervafla\PhpBlog\Filteres\XssFilter;
use Wandervafla\PhpBlog\Models\Users;

class UserController
{
    private static $viewPageDir = __DIR__ . "/../Views/Pages/";
    private Users $users;
    private MessageEmailFilter $messageEmailFilter;

    public function __construct()
    {
        $this->users = new Users();
        $this->messageEmailFilter = new MessageEmailFilter();
    }
    public function create()
    {
        $errors = [];

        $xssFilter = new XssFilter();
        $passwordValidate = new PasswordComplexityFilter();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            ValidateCsrfAction::validate();

            $name = $xssFilter($_POST["username"]);
            $email = $xssFilter($_POST["email"]);
            $password = trim($_POST["password"]);
            $confirmPassword = trim($_POST["confirm-password"]);

            if (empty($name)) {
                $errors["username"] = MESSAGE_USERNAME_REQUIRE;
            } elseif (str_word_count($name) > 1) {
                $errors["username"] = MESSAGE_USERNAME_VALIDATE;
            }

            ($this->messageEmailFilter)($errors, $email);

            if (empty($email)) {
                $errors["email"] = MESSAGE_EMAIL_REQUIRE;
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors["email"] = MESSAGE_EMAIL_VALIDATE;
            }

            if (empty($password)) {
                $errors["password"] = MESSAGE_PASSWORD_REQUIRE;
            } elseif (!$passwordValidate($password)) {
                $errors["password"] = MESSAGE_PASSWORD_VALIDATE_COMPLEXITY;
            }

            if (empty($confirmPassword)) {
                $errors["confirm-password"] = MESSAGE_CONFITM_PASSWORD_REQUIRE;
            } elseif ($password !== $confirmPassword) {
                $errors["confirm-password"] = MESSAGE_CONFITM_PASSWORD_VALIDATE;
            }

            try {
                if (empty($errors)) {
                    $this->users->insert(
                        name: $name,
                        email: $email,
                        password: password_hash($password, PASSWORD_BCRYPT),
                    );
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
        require self::$viewPageDir . "SingUp.php";
    }
    public function login()
    {
        $errors = [];
        $xssFilter = new XssFilter();
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            ValidateCsrfAction::validate();

            $email = $xssFilter($_POST["email"]);
            $password = trim($_POST["password"]);
            ($this->messageEmailFilter)($errors, $email);

            if (empty($password)) {
                $errors["password"] = MESSAGE_PASSWORD_REQUIRE;
            }

            try {
                if (empty($errors)) {
                    $userData = $this->users->select(email: $email);
                    if ($userData) {
                        if (password_verify($password, $userData["password"])) {
                            $_SESSION["user_id"] = $userData["id"];
                            $_SESSION["username"] = $userData["name"];
                            header("Location: /");
                            exit();
                        } else {
                            $errors["form"] = MESSAGE_LOGIN_FAILED;
                        }
                    } else {
                        $errors["form"] = MESSAGE_LOGIN_FAILED;
                    }
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        require self::$viewPageDir . "Login.php";
    }
}
