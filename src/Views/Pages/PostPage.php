<?php

require_once '../src/Views/components/head.php';

?>

<!doctype html>
<html lang="en">
    <?php head('Post page') ?>
    <body>
        <?php require '../src/Views/components/nav.php'; ?>
            <!--Image of Post (DIV is test)-->
            <div class="bg-gray-500 h-70 shadow-xl"></div>
        <main class="flex justify-between px-10 py-5 gap-5">
            <div class="flex-1">
                <h1 class="sticky top-10 text-balance">This blog is a demo application as an example</h1>
            </div>
            <p class="flex-2 text-balance whitespace-pre-line shadow-[-10px_10px_20px_rgb(0_0_0_/_0.1)_,_-10px_-10px_20px_rgb(0_0_0_/_0.1)] rounded-4xl px-5 my-20">
This blog is built with basic PHP techniques. No Object Oriented Programming or Frameworks.

Plain old php, some functions and an autoloader. The SQLite database is directly accessed by PDO.
This blog is built with basic PHP techniques. No Object Oriented Programming or Frameworks.

            </p>
        </main>
    </body>
</html>
