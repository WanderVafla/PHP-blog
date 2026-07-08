<?php

require_once '../src/Views/components/head.php';

?>

<!doctype html>
<html lang="en">
    <?php head('Post page') ?>
    <body>
        <?php require '../src/Views/components/nav.php'; ?>
        <img src="<?= htmlspecialchars($image_path) ?>" alt="Image Post" class="h-60 w-lvw object-cover shadow-xl" />
        <?php if ($isCreatedByCurrentUser): ?>
        <button><a href="/createPost?id=<?= strval($id) ?>">Edit</a></button>
        <?php endif; ?>
        <main class="flex justify-between px-10 py-5 gap-5">
            <div class="flex-1">
                <h1 class="sticky top-10 text-balance"><?= htmlspecialchars($title) ?></h1>
            </div>
            <p class="flex-2 text-balance whitespace-pre-line shadow-[-10px_10px_20px_rgb(0_0_0_/_0.1)_,_-10px_-10px_20px_rgb(0_0_0_/_0.1)] rounded-4xl px-5 my-20">
                <?= htmlspecialchars($content) ?>
            </p>
        </main>
    </body>
</html>
