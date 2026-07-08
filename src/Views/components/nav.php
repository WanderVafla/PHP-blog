<?php
use Wandervafla\PhpBlog\Core\Session;

// if ($_SERVER["REQUEST_METHOD"] === "POST") {
//     Session::destroySession();
//     $_SESSION['last_action'] = FLASH_MESSAGE_SINOUT;
//     header("Location: /");
//     exit;
// }
?>

<nav class="flex justify-between items-center px-5 py-3">
    <p class="text-5xl font-bold"><a href="\">PHP blog</a></p>
    <span class="flex gap-3">
        <?php if (!Session::isLoggedIn()): ?>
            <button type="button"><a href="/login">Login</a></button>
            <button type="button"><a href="/singup">Reg in</a></button>
        <?php else: ?>
            <form action="/logout" method="post">
                <button type="submit">Log out</button>
            </form>
        <?php endif; ?>
    </span>
</nav>
