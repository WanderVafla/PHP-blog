<?php
namespace Wandervafla\PhpBlog\Controllers;

use PDOException;
use Wandervafla\PhpBlog\Actions\Security\ValidateCsrfAction;
use Wandervafla\PhpBlog\Filteres\PasswordComplexityFilter;
use Wandervafla\PhpBlog\Filteres\XssFilter;
use Wandervafla\PhpBlog\Models\Users;

class UserController
{
    private static $viewPageDir = __DIR__ . "/../Views/Pages/";
    private Users $users;

    public function __construct()
    {
        $this->users = new Users();
    }
    public function create()
    {
        $errors = [];

        $users = new Users();
        $xssFilter = new XssFilter();
        $passwordValidate = new PasswordComplexityFilter();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            new ValidateCsrfAction();

            $name = $xssFilter($_POST["username"]);
            $email = $xssFilter($_POST["email"]);
            $password = trim($_POST["password"]);
            $confirmPassword = trim($_POST["confirm-password"]);

            if (empty($name)) {
                $errors["username"] = MESSAGE_USERNAME_REQUIRE;
            }
            if (empty($email)) {
                $errors["email"] = MESSAGE_EMAIL_REQUIRE;
            }
            if (empty($password)) {
                $errors["password"] = MESSAGE_PASSWORD_REQUIRE;
            }
            if (empty($password)) {
                $errors["confirm-password"] = MESSAGE_CONFITM_PASSWORD_REQUIRE;
            }

            if (str_word_count($name) > 1) {
                $errors["username"] = MESSAGE_USERNAME_VALIDATE;
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($email)) {
                $errors["email"] = MESSAGE_EMAIL_VALIDATE;
            }
            if (!$passwordValidate($password) && !empty($password)) {
                $errors["password"] = MESSAGE_PASSWORD_VALIDATE_COMPLEXITY;
            }

            if ($password !== $confirmPassword && !empty($confirmPassword)) {
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
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = $_POST["email"];
            $password = trim($_POST["password"]);

            // empty strings errors message;
            if (empty($email)) {
                $errors["email"] = MESSAGE_EMAIL_REQUIRE;
            }
            if (empty($password)) {
                $errors["password"] = MESSAGE_PASSWORD_REQUIRE;
            }
            // not valided datas
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors["email"] = MESSAGE_EMAIL_VALIDATE;
            }

            try {
                if (empty($errors)) {
                    $userData = $this->users->select(email: $email);
                    if ($userData) {
                        if (password_verify($password, $userData["password"])) {
                            echo "password valid";
                        } else {
                            $errors['form'] = MESSAGE_LOGIN_FAILED;
                        }
                    }
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        require self::$viewPageDir . "Login.php";
    }
}
