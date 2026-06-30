<?php 
    require_once '../src/Views/components/head.php';
?>

<!doctype html>
<html lang="en">
    <?php head('PHP blog') ?>
    <body>
        <?php require '../src/Views/components/nav.php'; ?>
        <main class="main-default">
            <h1>PHP BLOG</h1>
        <div class="flex flex-col gap-5 items-end">

            <span class="px-5">
                <button><a href="post_creation.php">New Post</a></button>
            </span>

            <div class="grid grid-cols-4 gap-5">


            </div>
        </div>

        </main>
    </body>
</html>
