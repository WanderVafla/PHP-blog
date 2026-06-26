<?php
require_once "../src/components/post.php";
require_once "../src/components/head.php";
// For test
$content = "
If the CSS won't load at all—meaning you inspect the page in your browser and you either get a 404 Not Found error or none of the styles are applying—the issue is almost always how your browser/web server is resolving the file path.
Because you are writing a raw PHP project, pathing can get tricky depending on how you are serving the files (e.g., using localhost:8000, Apache, or just opening files).
Here is how to isolate and fix the connection completely:
";
?>

<!doctype html>
<html lang="en">
    <?php head('PHP blog') ?>
    <body>
        <?php require "../src/components/nav.php"; ?>
        <main class="main-default">
        <div class="flex flex-col gap-5 items-end">

            <span class="px-5">
                <button><a href="post_creation.php">New Post</a></button>
            </span>

            <div class="grid grid-cols-4 gap-5">

                <?php for ($i = 0; $i < 120; $i++): ?>
                    <?php post(content: $content, title: 'Title post'); ?>
                <?php endfor; ?>

            </div>
        </div>

        </main>
    </body>
</html>
