<?php
namespace Wandervafla\PhpBlog\Controllers;

use PDOException;
use Wandervafla\PhpBlog\Actions\Security\ValidateCsrfAction;
use Wandervafla\PhpBlog\Core\DisplayErrors;
use Wandervafla\PhpBlog\Filteres\XssFilter;
use Wandervafla\PhpBlog\Models\Users;
use Wandervafla\PhpBlog\Core\Session;
use Wandervafla\PhpBlog\Models\Posts;
use Wandervafla\PhpBlog\Actions\Security\EncryptPassword;

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
            Session::addAction(FLASH_MESSAGE_SINOUT);
            header("Location: /");
            exit();
    }
    public function profil()
    {
        // WARNING: array mush always respect order ['name', 'email', 'password']
        $allowedChangeParams = ['name', 'email', 'password'];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            ValidateCsrfAction::validate();
            $id = $_SESSION['user_id'];
            // TODO: change exeption
            $column = $_POST['profilAction'] ?? "";
            $value = $_POST['value'] ?? "";

            if (in_array($column, $allowedChangeParams)) {
                if ($column === $allowedChangeParams[3]) {
                    $password = $_POST("");
                    $value = EncryptPassword::encrypt($value);
                    $this->users->update(id: $id, column: $column, value: $value);
                    Session::addAction(sprintf("%s %s", ucfirst($column), FLASH_MESSAGE_CHANGED ));
                }
                $this->users->update(id: $id, column: $column, value: $value);
                Session::addAction(sprintf("%s %s", ucfirst($column), FLASH_MESSAGE_CHANGED ));
            }
        }

        $changeData = $_GET['change'] ?? "";
        if (in_array($changeData, $allowedChangeParams)) {
            require "../src/Views/components/modal.php";
            $array = [
                
            ];
            if ($changeData == 'password') {
                modal([
                    ["type" => "password", "name" => "new_password", "placeholder" => "New {$changeData}"],
                    ["type" => "password", "name" => "Repeat_password", "placeholder" => "Repeat {$changeData}"],
                ], true, $changeData);
                return;
            }
            modal([
                ["type" => "text", "name" => "value", "placeholder" => "New {$changeData}"],
            ], true, $changeData);
        }

        $user_id = Session::getUserId();
        if (!isset($user_id)) {
            Session::addAction(FLASH_MESSAGE_ERROR_PROFIL);
            header("Location: /");
            exit;
        }
        $username = Session::getUsername();
        $email = Session::getEmail();
        
        $posts = new Posts()->fetchAll('user_id', Session::getUserId());
        
        require self::$viewPageDir . "Profil.php";
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
                if (empty($errors)) {
                    $userData = $this->users->select(email: $email);
                    
                    $validPassword = password_verify($password, $userData["password"]);
                    if (!empty($userData) && $validPassword) {
                        $_SESSION["user_id"] = $userData["id"];
                        $_SESSION["username"] = $userData["name"];
                        $_SESSION['email'] = $userData['email'];
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
                            password: EncryptPassword::encrypt($password),
                        );
                        Session::addAction(FLASH_MESSAGE_SINGUP);
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
