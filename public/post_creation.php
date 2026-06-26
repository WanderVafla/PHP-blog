<?php
// argument (string $type, name $name, string $placeholder)
require_once "../src/components/input.php";
require_once "../src/components/textarea.php";
require_once "../src/components/head.php";

?>

<!doctype html>
<html lang="en">
    <?php head('Create new post') ?>
    <body>
        <?php require_once "../src/components/nav.php"; ?>
        <header>
        </header>
        <main class="main-default flex justify-between">
            <h1 class="whitespace-pre-line leading-18 items-start justify-start">
                Create
                <mark>New Post</mark>
            </h1>
            <form action="single-detail-page.php" class="flex flex-col gap-5 items-end">

                <?php input_with_border(
                    type: "text",
                    name: "title",
                    placeholder: "Title",
                ) ?>
                <?php textarea(placeholder: 'Description'); ?>
                <?php input_with_border(
                    type: "file",
                    name: "title",
                    placeholder: "Image",
                ) ?>
                
                <button class="justify-end" type="submit">Publish</button>
            </form>
        </main>
    </body>
</html>
