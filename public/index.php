<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once "../src/constants.php";

use Wandervafla\PhpBlog\Controllers\PostController;
use Wandervafla\PhpBlog\Controllers\UserController;
use Wandervafla\PhpBlog\Core\Database;
use Wandervafla\PhpBlog\Core\Session;

(bool) $dbInit = false;

Session::initSession();

$schemaLockFile = "../db/db_init.lock";
if (!file_exists($schemaLockFile)) {
    $pdo = Database::Connection();
    Database::initSchema(pdo: $pdo);

    file_put_contents($schemaLockFile, date('Y-m-d H:i:s'));
}

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
        $controllerUsers->singout();
        break;
    case "/removePost":
        $controller->remove();
        break;
    case "/profil":
        $controllerUsers->profil();
        break;
}
if (!empty($_SESSION["last_action"])) {
    require "../src/Views/components/message.php";
    unset($_SESSION["last_action"]);
}
if (!empty($_SESSION['last_action'])) {
    require '../src/Views/components/message.php';
    unset($_SESSION['last_action']);
}
