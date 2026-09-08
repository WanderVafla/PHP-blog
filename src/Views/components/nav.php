<?php
use Wandervafla\PhpBlog\Core\Session;

?>

<nav class="flex relative justify-between items-center px-5 py-3">
    <p class="text-5xl font-bold"><a href="\">PHP blog</a></p>
    <span class="flex gap-3">
        <?php if (!Session::isLoggedIn()): ?>
            <button type="button"><a href="/login">Login</a></button>
            <button type="button"><a href="/singup">Reg in</a></button>
        <?php else: ?>
            <div class="size-10 bg-blue-500 rounded-4xl text-center justify-center items-center hover:[&>div]:flex" >
                U
                <div class="hidden flex-col">
                    <button type="button"><a href="/profil">Profil</a></button>
                    <button type="button"><a href="/logout">Log out</a></button>
                </div>
            </div>
        <?php endif; ?>
    </span>
    
</nav>
