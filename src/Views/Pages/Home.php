<?php
use Wandervafla\PhpBlog\Core\Session;

require_once "../src/Views/components/head.php";
require_once "../src/Views/components/post.php";

$login_status = Session::isLoggedIn();
?>

<!doctype html>
<html lang="en">
    <?php head("PHP blog"); ?>
    <body>
        <?php require "../src/Views/components/nav.php"; ?>
        <main class="main-default">
            <h1>PHP BLOG</h1>
        <div class="flex flex-col gap-5 items-end">

            <span class="px-5">
                <?php if ($login_status): ?>
                    <button><a href="/createPost">New Post</a></button>
                <?php endif; ?>
            </span>

            <div class="grid grid-cols-4 gap-5">

                <?php foreach ($posts as $post): ?>
                    <?php post(
                        id: $post["id"],
                        content: htmlspecialchars($post["content"] ?? ""),
                        title: htmlspecialchars($post["title"] ?? ""),
                        path: htmlspecialchars($post["image"] ?? ""),
                        categorie: $post["categories_name"] ?? "",
                    );
                    ?>
                <?php endforeach; ?>


            </div>
        </div>

        </main>
    </body>
</html>
