<?php
require_once '../src/components/post.php';
require_once '../src/components/head.php';

require_once "../src/db.php";

try {
    $posts = $pdo->query('SELECT * FROM posts');
} catch (PDOException $e) {
    die('Query failed: ' . $e->getMessage());
}

?>

<!doctype html>
<html lang="en">
    <?php head('PHP blog') ?>
    <body>
        <?php require '../src/components/nav.php'; ?>
        <main class="main-default">
        <div class="flex flex-col gap-5 items-end">

            <span class="px-5">
                <button><a href="post_creation.php">New Post</a></button>
            </span>

            <div class="grid grid-cols-4 gap-5">

                <?php foreach ($posts as $post): ?>
                    <?php post(content: $post['content'], title: $post['title'], path: $post['image']); ?>
                <?php endforeach; ?>

            </div>
        </div>

        </main>
    </body>
</html>
