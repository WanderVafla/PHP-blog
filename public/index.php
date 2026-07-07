<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once '../src/constants.php';

use Wandervafla\PhpBlog\Actions\Actions;
use Wandervafla\PhpBlog\Controllers\PostController;
use Wandervafla\PhpBlog\Actions\Security\InitSessionAction;
use Wandervafla\PhpBlog\Controllers\UserController;

new InitSessionAction();


$controller = new PostController();
$controllerUsers = new UserController();

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$viewPagesDir = '../src/Views/Pages/';

switch ($request) {
    case '':
    case '/':
        $controller->home();
        break;
    case '/login':
        $controllerUsers->login();
        break;
    case '/singup':
        $controllerUsers->create();
        break;
    case '/createPost':
        $controller->upster();
        break;
    case '/post':
        $controller->open();
        break;
}

?>
