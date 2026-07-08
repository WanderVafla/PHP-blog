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
    private XssFilter $xssFilter;

    public function __construct()
    {
        $this->users = new Users();
        $this->messageEmailFilter = new MessageEmailFilter();
        $this->xssFilter = new XssFilter();
    }
// TODO: Faire test to login and singup pages!
    public function auth(string $authAction)
    {
        $errors = [];
        $passwordValidate = new PasswordComplexityFilter();

        // possible be 'login' or 'singup'
        // $authAction = ($this->xssFilter)($_GET["action"] ?? "login");

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            ValidateCsrfAction::validate();

            $email = ($this->xssFilter)($_POST["email"]);
            $password = trim($_POST["password"]);

            if (empty($email)) {
                $errors["email"] = MESSAGE_EMAIL_REQUIRE;
            }
            if (empty($password)) {
                $errors["password"] = MESSAGE_PASSWORD_REQUIRE;
            }

            if ($authAction === "login") {
                try {
                    if (empty($errors)) {
                        $userData = $this->users->select(email: $email);
                        if ($userData) {
                            if (
                                password_verify(
                                    $password,
                                    $userData["password"],
                                )
                            ) {
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
                    throw new PDOException($e->getMessage());
                }
            }

            if ($authAction === "singup") {
                $name = ($this->xssFilter)($_POST["username"]);
                $password = trim($_POST["password"]);
                $confirmPassword = trim($_POST["confirm-password"]);

                if (empty($name)) {
                    $errors["username"] = MESSAGE_USERNAME_REQUIRE;
                } elseif (str_word_count($name) > 1) {
                    $errors["username"] = MESSAGE_USERNAME_VALIDATE;
                }

                ($this->messageEmailFilter)($errors, $email);

                if (!$passwordValidate($password)) {
                    $errors["password"] = MESSAGE_PASSWORD_VALIDATE_COMPLEXITY;
                }

                if (empty($confirmPassword)) {
                    $errors[
                        "confirm-password"
                    ] = MESSAGE_CONFITM_PASSWORD_REQUIRE;
                } elseif ($password !== $confirmPassword) {
                    $errors[
                        "confirm-password"
                    ] = MESSAGE_CONFITM_PASSWORD_VALIDATE;
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
        }
        if ($authAction === "singup") {
            require self::$viewPageDir . "SingUp.php";
        }
        if ($authAction === "login") {
            require self::$viewPageDir . "Login.php";
        }
    }
}
