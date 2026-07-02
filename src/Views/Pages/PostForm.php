<?php
require_once "../src/Views/components/input.php";
require_once "../src/Views/components/textarea.php";
require_once "../src/Views/components/head.php";
?>

<!doctype html>
<html lang="en">
    <?php head("Create new post"); ?>
    <body>
        <?php require_once "../src/Views/components/nav.php"; ?>
        <header>
        </header>
        <main class="main-default flex justify-between">
            <h1 class="whitespace-pre-line leading-18 items-start justify-start">
                Create
                <mark>New Post</mark>
            </h1>
            <form action="" method="post" enctype="multipart/form-data" class="flex flex-col gap-5 items-end">

                <?php input_with_border(
                    type: "text",
                    name: "title",
                    placeholder: "Title",
                ); ?>
                <?php textarea(name: "content", placeholder: "Description"); ?>
                <?php input_with_border(
                    type: "file",
                    name: "image",
                    placeholder: "Image",
                ); ?>
                <input type="hidden" name="csrf_token" value="<?php $_SESSION[
                    "csrf_token"
                ]; ?>">

                <button class="justify-end" type="submit">Publish</button>
            </form>
        </main>
    </body>
</html>
