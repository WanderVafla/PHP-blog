<?php

require_once "../src/Views/components/head.php";
require_once "../src/Views/components/post.php";
?>

<!doctype html>
<html lang="en">
    <?php head("Post page"); ?>
    <body>
        <?php require "../src/Views/components/nav.php"; ?>
        <main class="flex flex-col px-10 py-5">
            <p><?= htmlspecialchars($username ?? "Username Not Found") ?></p>
            <p><?= htmlspecialchars($email ?? "Email Not Found") ?></p>
            <?php if (!empty($posts)): ?>
            <div class="grid grid-cols-4 gap-5">
                <?php foreach ($posts as $post): ?>
                    <?php post(
                        id: $post["id"],
                        content: htmlspecialchars($post["content"] ?? ""),
                        title: htmlspecialchars($post["title"] ?? ""),
                        path: htmlspecialchars($post["image"] ?? ""),
                    ); ?>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
                <p>You not have any posts!</p>
            <?php endif; ?>
        </main>
    </body>
</html>
