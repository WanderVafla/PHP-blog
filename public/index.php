<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Wandervafla\PhpBlog\Actions\Actions;
use Wandervafla\PhpBlog\Controllers\PostController;
use Wandervafla\PhpBlog\Actions\Security\InitSessionAction;

new InitSessionAction();

$controller = new PostController();


$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$viewPagesDir = '../src/Views/Pages/';

switch ($request) {
    case '':
    case '/':
        $controller->home();
        break;
    case '/login':
        require $viewPagesDir . 'Login.php';
        break;
    case '/singUp':
        require $viewPagesDir . 'SingUp.php';
        break;
    case '/createPost':
        $controller->create();
        break;
    case '/post':
        require $viewPagesDir . 'PostPage.php';
        break;
    case '/post/edit':
        require $viewPagesDir . 'EditPostPage.php';
        break;
}

?>
