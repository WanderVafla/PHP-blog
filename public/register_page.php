<?php
// argument (string $type, name $name, string $placeholder)
require_once "../src/components/input.php"; 

?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>PHP blog</title>
        <link href="output.css" rel="stylesheet" />
    </head>
    <body class="flex flex-col justify-center items-center w-lvw h-lvh gap-10">
        <h1>Create a new account</h1>
        <main class="main-form">
            <form class="flex flex-col w-110 gap-5">
                <label for="username-register">Usename</label>
                <?php input_with_border(
                    type: "text",
                    id: "username-register",
                    name: "username",
                    placeholder: "Username",
                ); ?>

                <label for="email-register">Email address</label>
                <?php input_with_border(
                    type: "text",
                    id: "email-register",
                    name: "email",
                    placeholder: "email",
                ); ?>

                <label for="sing-in-password">Password</label>
                <?php input_with_border(
                    type: "text",
                    id: "password-register",
                    name: "password",
                    placeholder: "Password",
                ); ?>

                <label for="confirm-password-register">Confitm Password</label>
                <?php input_with_border(
                    type: "password",
                    id: "confirm-password-register",
                    name: "confirm-password",
                    placeholder: "Password",
                ); ?>

                <button type="submit">Sing in</button>
            </form>
            <p>
                Have account? <a href="login.php" class="link_text">Log in</a> - Back to <a href="index.php" class="link_text">Home Page</a>
            </p>
        </main>
    </body>
</html>
