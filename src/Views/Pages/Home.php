<?php 
    require_once '../src/Views/components/head.php';
    require_once '../src/Views/components/post.php';
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
                <button><a href="/createPost">New Post</a></button>
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
