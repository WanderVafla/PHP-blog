<?php
require_once "../src/Views/components/input.php";
require_once "../src/Views/components/head.php";
?>

<!doctype html>
<html lang="en">
    <?php head("Login"); ?>
    <body class="flex flex-col justify-center items-center w-lvw h-lvh gap-10">
        <h1>Sign in to your account</h1>
        <main class="main-form">
            <form method="POST" class="flex flex-col w-110 gap-5">
                <span class="error"><?= htmlspecialchars($errors['form'] ?? "") ?></span>
                <?php input_with_border(
                    [
                    "type" => "email",
                    "name" => "email",
                    "placeholder" => "email",
                    ], label: "Email address", error: $errors['email'] ?? null
                ); ?>
                <?php input_with_border(
                    [
                    "type" => "password",
                    "name" => "password",
                    "placeholder" => "Password",
                    ],
                    label: "Password",
                    error: $errors['password'] ?? null
                ); ?>
                <input type="hidden" name="authAction" value="login">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION[
                    "csrf_token"
                ] ?>">
                <button type="submit">Sing in</button>
            </form>
            <p>
                No account? <a href="/singup" class="link_text">Create one</a> - Back to <a href="/" class="link_text">Home Page</a>
            </p>
        </main>
    </body>
</html>
