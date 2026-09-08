<?php
require_once "../src/Views/components/input.php";
require_once "../src/Views/components/head.php";
?>
<!doctype html>
<html lang="en">
    <?php head("Registration"); ?>
    <body class="flex flex-col justify-center items-center w-lvw h-lvh gap-10">
        <h1>Create a new account</h1>
        <main class="main-form">
            <form method="POST" class="flex flex-col w-110 gap-5">
                <?php input_with_border(
                    [
                    "type" => "text",
                    "id" => "username-register",
                    "name" => "username",
                    "placeholder" => "Username"
                    ], label: "Username", error: $errors["username"] ?? null
                ); ?>


                <?php input_with_border(
                    [
                    "id" => "email-register",
                    "name" => "email",
                    "placeholder" => "email",
                    ], label: "Email address", error: $errors["email"] ?? null
                ); ?>

                <?php input_with_border(
                    [
                    "type" => "password",
                    "id" => "password-register",
                    "name" => "password",
                    "placeholder" => "Password"
                    ], label: "Password", error: $errors["password"] ?? null,
                ); ?>

                <?php input_with_border(
                    [
                     "type" => "password",
                     "id" => "confirm-password-register",
                     "name" => "confirm-password",
                     "placeholder" => "Password"
                    ], label: "Confirm Password", error: $errors["confirm-password"] ?? null
                ); ?>
                <input type="hidden" name="authAction" value="singup">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION[
                    "csrf_token"
                ] ?>">
                <button type="submit">Sing in</button>
            </form>
            <p>
                Have account? <a href="/login" class="link_text">Log in</a> - Back to <a href="/" class="link_text">Home Page</a>
            </p>
        </main>
    </body>
</html>
