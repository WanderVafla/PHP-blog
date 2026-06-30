<?php
session_start();

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$viewPagesDir = '../src/Views/Pages/';

switch ($request) {
    case '':
    case '/':
        require $viewPagesDir . 'Home.php';
        break;
    case '/login':
        require $viewPagesDir . 'Login.php';
        break;
    case '/singUp':
        require $viewPagesDir . 'SingUp.php';
        break;
    case '/createPost':
        require $viewPagesDir . 'PostForm.php';
        break;
    case '/post':
        require $viewPagesDir . 'PostPage.php';
        break;
    case '/post/edit':
        require $viewPagesDir . 'EditPostPage.php';
        break;
}

?>
