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
            <p class="flex w-5 justify-between">
                <?= htmlspecialchars($username ?? "Username Not Found") ?>
                <a href="/profil?change=username"><button>Chenge</button></a>
            </p>
            <p class="flex w-5 justify-between">
                <?= htmlspecialchars($email ?? "Email Not Found") ?>
                <a href="/profil?change=email"><button>Chenge</button></a>
            </p>
            <a href="/profil?change=password"><button>Change Password</button></a>
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
