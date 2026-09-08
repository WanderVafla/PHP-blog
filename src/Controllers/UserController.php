<?php

namespace Wandervafla\PhpBlog\Controllers;

use PDOException;
use Wandervafla\PhpBlog\Actions\Security\ValidateCsrfAction;
use Wandervafla\PhpBlog\Core\DisplayErrors;
use Wandervafla\PhpBlog\Models\Users;
use Wandervafla\PhpBlog\Core\Session;
use Wandervafla\PhpBlog\Models\Posts;
use Wandervafla\PhpBlog\Actions\Security\EncryptPassword;

class UserController
{
    private static $viewPageDir = __DIR__ . "/../Views/Pages/";

    private Users $users;

    public function __construct()
    {
        $this->users = new Users();
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
        Session::notLoggedRedirect();

        // WARNING: array mush always respect order ['name', 'email', 'password'],
        // WARNING: Better save same names like in Database
        $allowedChangeParams = ['name', 'email', 'password'];
        $passwordNames = [
            "OldPassword" => "old_password",
            "NewPassword" => "new_password",
            "RepeatPassword" => "repeat_password"
        ];

        $errors = [];
        if (!empty($errors)) {
            $errors = [];
        }
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            ValidateCsrfAction::validate();
            $diaplayMessage = new DisplayErrors();

            $id = Session::getUserId();
            // TODO: change exeption
            $column = $_POST['profilAction'] ?? "";
            $value = $_POST['value'] ?? "";

            if (in_array($column, $allowedChangeParams)) {
                if ($column === $allowedChangeParams[2]) {
                    // TODO: Refactoring DRY, KISS
                    // TODO: A lot of same arrays
                    // TODO: replace magic string on variables, like "old_password"

                    // WARNING: order is important
                    $passwordValue = trim($_POST[$passwordNames['OldPassword']]) ?? null;
                    $newPasswordValue = $_POST[$passwordNames['NewPassword']] ?? null;
                    $repeatPasswordValue = $_POST[$passwordNames['RepeatPassword']] ?? null;

                    $diaplayMessage->checkEmptyInputs(
                        errors: $errors,
                        inputs: [
                            [
                                "name" => $passwordNames['OldPassword'],
                                "value" => "{$passwordValue}",
                            ],
                            [
                                "name" => $passwordNames['NewPassword'],
                                "value" => "{$newPasswordValue}",
                            ],
                            [
                                "name" => $passwordNames['RepeatPassword'],
                                "value" => "{$repeatPasswordValue}",
                            ],
                        ],
                    );

                    if (!empty(trim($newPasswordValue))) {

                        $diaplayMessage->checkForcePassword($errors, [
                            "name" => $passwordNames['NewPassword'],
                            "value" => "{$newPasswordValue}",
                        ]);
                    }
                    if (!empty(trim($newPasswordValue)) && !empty(trim($repeatPasswordValue))) {
                        $diaplayMessage->checkConfirmPassword($errors, [
                            [
                                "name" => $passwordNames['NewPassword'],
                                "value" => "{$newPasswordValue}",
                            ],
                            [
                                "name" => $passwordNames['RepeatPassword'],
                                "value" => "{$repeatPasswordValue}",
                            ],
                        ]);
                    }


                    if (!empty(trim($passwordValue))) {
                        $email = Session::getEmail();
                        $userData = $this->users->select(email: $email);

                        $validPassword = password_verify(
                            $passwordValue,
                            $userData["password"],
                        );
                        if (!$validPassword) {
                            $diaplayMessage->diaplay(
                                $errors,
                                inputNmae: $passwordNames['OldPassword'],
                                nameError: MESSAGE_PASSWORD_INCORRECT,
                            );
                        }
                    }

                    if (empty($errors)) {
                        $value = EncryptPassword::encrypt($newPasswordValue);
                        $this->users->update(
                            id: $id,
                            column: $column,
                            value: $value,
                        );
                        $updatedUserData = $this->users->selectById($id);
                        Session::updateCurrentUserData($updatedUserData);
                        Session::addAction(
                            sprintf(
                                "%s %s",
                                ucfirst($column),
                                FLASH_MESSAGE_CHANGED,
                            ),
                        );
                        header("Location: /profil");
                    }
                }

                (array) $currentInput = ["name" => 'value', "value" => $value];



                $diaplayMessage->checkEmptyInputs(
                    errors: $errors,
                    inputs: $currentInput
                );
                if ($column === $allowedChangeParams[1]) {
                    $diaplayMessage->checkEmail($errors, $currentInput);
                }

                if (empty($errors)) {
                    try {
                        $this->users->update(
                            id: $id,
                            column: $column,
                            value: $value,
                        );
                        $updatedUserData = $this->users->selectById($id);
                        Session::updateCurrentUserData($updatedUserData);
                        Session::addAction(
                            sprintf(
                                "%s %s",
                                ucfirst($column),
                                FLASH_MESSAGE_CHANGED,
                            ),
                        );
                        header("Location: /profil");
                    } catch (PDOException $e) {
                        $errorMessage = $e->getMessage();
                        echo $column;
                        if (str_contains($errorMessage, "users.name")) {
                            $errors["value"] = MESSAGE_USERNAME_EXIST;
                        }
                        if (str_contains($errorMessage, "users.email")) {
                            $errors["value"] = MESSAGE_EMAIL_EXIST;
                        }
                    }
                }
            }
        }

        $changeData = $_GET['change'] ?? "";
        if (in_array($changeData, $allowedChangeParams)) {
            require "../src/Views/components/modal.php";
            // $allowedChangeParams[2] it is 'password';
            if ($changeData == $allowedChangeParams[2]) {
                modal(
                    [
                        [
                            "type" => "password",
                            "name" => $passwordNames['OldPassword'],
                            "placeholder" => "Old {$changeData}",
                        ],
                        [
                            "type" => "password",
                            "name" => $passwordNames['NewPassword'],
                            "placeholder" => "New {$changeData}",
                        ],
                        [
                            "type" => "password",
                            "name" => $passwordNames['RepeatPassword'],
                            "placeholder" => "Repeat {$changeData}",
                        ],
                    ],
                    $changeData,
                    $errors,
                    true,
                );
            } else {
                modal(
                    [[
                        "type" => ($type =
                            $changeData === $allowedChangeParams[1]
                            ? "email"
                            : "text"),
                        "name" => "value",
                        "placeholder" => "New {$changeData}",
                    ]],
                    $changeData,
                    $errors,
                    true,
                );
            }
        }

        $user_id = Session::getUserId();
        if (!isset($user_id)) {
            Session::addAction(FLASH_MESSAGE_ERROR_PROFIL);
            header("Location: /");
            exit();
        }
        $username = Session::getUsername();
        $email = Session::getEmail();

        $posts = new Posts()->fetchAll('user_id', $user_id);

        require self::$viewPageDir . "Profil.php";
    }
    // TODO: Chnage name of function
    public function auth(string $authAction)
    {
        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            ValidateCsrfAction::validate();

            $email = htmlspecialchars($_POST["email"]);
            $password = trim($_POST["password"]);

            DisplayErrors::emptyPasswordEmail(
                $errors,
                email: $email,
                password: $password,
            );

            if ($authAction === "login") {
                if (empty($errors)) {
                    try {
                        $validPassword = null;
                        $userData = $this->users->select(email: $email);
                        $userId = $userData["id"] ?? null;

                        if ($userData) {
                            $validPassword = password_verify(
                                $password,
                                $userData["password"],
                            );
                        }
                        if (!empty($userData) && $validPassword) {
                            $_SESSION["user_id"] = $userId;
                            $updatedUserData = $this->users->selectById(
                                $userId,
                            );
                            Session::updateCurrentUserData($updatedUserData);

                            Session::addAction(FLASH_MESSAGE_LOGGED);
                            header("Location: /");
                            exit();
                        } else {
                            DisplayErrors::checkLogin(
                                errors: $errors,
                                userData: $userData,
                            );
                        }
                    } catch (PDOException $e) {
                        $errorMessage = $e->getMessage();
                    }
                }
            }

            if ($authAction === "singup") {
                // TODO: fix supoort special character like _
                $name = htmlspecialchars($_POST["username"]);
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
