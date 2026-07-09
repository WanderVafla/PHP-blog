<?php
require_once __DIR__ . "/../vendor/autoload.php";
require_once "../src/constants.php";

use Wandervafla\PhpBlog\Controllers\PostController;
use Wandervafla\PhpBlog\Controllers\UserController;
use Wandervafla\PhpBlog\Core\Session;

Session::initSession();

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
        $controllerUsers->auth('login');
        break;
    case '/singup':
        $controllerUsers->auth('singup');
        break;
    case '/createPost':
        $controller->upster();
        break;
    case '/post':
        $controller->open();
        break;
    case "/logout":
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            Session::destroySession();
            $_SESSION["last_action"] = FLASH_MESSAGE_SINOUT;
            header("Location: /");
            exit();
        }
    case "/removePost":
        $controller->remove();
        break;
}
if (!empty($_SESSION["last_action"])) {
    require "../src/Views/components/message.php";
    unset($_SESSION["last_action"]);
}

?>
