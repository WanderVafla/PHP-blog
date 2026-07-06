<?php
// argument (string $type, name $name, string $placeholder)
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
                <label for="sing-in-email">Email address</label>
                <span class="error"><?= $errors['email'] ?></span>
                <?php input_with_border(
                    type: "email",
                    name: "email",
                    placeholder: "email",
                ); ?>

                <label for="sing-in-password">Password</label>
                <span class="error"><?= $errors['password'] ?></span>
                <?php input_with_border(
                    type: "password",
                    name: "password",
                    placeholder: "Password",
                ); ?>

                <button type="submit">Sing in</button>
            </form>
            <p>
                No account? <a href="/singUp" class="link_text">Create one</a> - Back to <a href="/" class="link_text">Home Page</a>
            </p>
        </main>
    </body>
</html>
