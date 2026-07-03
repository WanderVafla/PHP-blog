<?php
require_once "../src/Views/components/head.php";
require_once "../src/Views/components/input.php";
require_once "../src/Views/components/textarea.php";
?>

<!doctype html>
<html lang="en">
    <?php head("Edit Post"); ?>
    <body>
        <?php require "../src/Views/components/nav.php"; ?>
        <img src="<?= "/" . $image_path ?>" class="h-60 w-lvw object-cover">
        <main class="flex justify-between px-10 py-5 gap-5">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="flex-1">
                    <span class="error">* <?= $errors["title"] ?></span>
                    <?php input_with_border(
                        type: "text",
                        name: "title",
                        value: $title,
                    ); ?>
                </div>
                <span class="error">* <?= $errors["content"] ?></span>
                <?php textarea(name: "content", value: $content); ?>
                <input type="hidden" name="csrf_token" value="<?= $_SESSION[
                    "csrf_token"
                ] ?>">
                <div>
                    <span class="error">* <?= $errors["image"] ?></span>
                    <?php input_with_border(type: "file", name: "image"); ?>
                    <button type="submit">Save Changes</button>
                </div>
            </form>
        </main>
    </body>
</html>
