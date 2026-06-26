<?php
// argument (string $type, name $name, string $placeholder)
require_once '../src/components/input.php';

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
        <h1>Sign in to your account</h1>
        <main class="main-form">
            <form class="flex flex-col w-110 gap-5">
                <label for="sing-in-email">Email address</label>
                <?php input_with_border(type: 'text', placeholder: "email"); ?>
                <!--<input id="sing-in-email" type="email" name="email" />-->
                
                <label for="sing-in-password">Password</label>
                <?php input_with_border(type: 'text', placeholder: 'Password'); ?>
                <!--<input id="sing-in-password" type="password" name="password" />-->

                <button type="submit">Sing in</button>
            </form>
            <p>
                No account? <a href="register_page.php" class="link_text">Create one</a> - Back to <a href="index.php" class="link_text">Home Page</a>
            </p>
        </main>
    </body>
</html>