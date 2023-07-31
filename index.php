<?php

use AlbuquerqueLucas\UserTaskManager\Controllers\HomeController;
use AlbuquerqueLucas\UserTaskManager\Router\Router;
use AlbuquerqueLucas\UserTaskManager\Controllers\ErrorController;
use AlbuquerqueLucas\UserTaskManager\Controllers\AuthController;
use AlbuquerqueLucas\UserTaskManager\Controllers\TaskController;

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$router = new Router();
$errorController = new ErrorController();
$authController = new AuthController();
$taskController = new TaskController();
$homeController = new HomeController();

$router->get('/', [$homeController, 'renderHomeRequest']);
$router->get('/notFound', [$errorController, 'renderNotFound']);
$router->get('/login', [$authController, 'renderLoginRequest']);
$router->get('/register', [$authController, 'renderRegisterRequest']);
$router->get('/messages', [$errorController, 'renderNotImplemented']);
$router->get('/profile', [$authController, 'renderProfileRequest']);
$router->get('/tasks', [$taskController, 'renderTasksRequest']);
$router->post('/create-user', [$authController, 'createUserRequest']);
$router->post('/authenticate', [$authController, 'authenticate']);
$router->post('/logout', [$authController, 'deleteRequest']);
$router->post('/create-task', [$taskController, 'createRequest']);
$router->post('/delete-task', [$taskController, 'deleteRequest']);
$router->post('/update-task', [$taskController, 'updateRequest']);
$router->post('/update-task-status', [$taskController, 'updateStatusRequest']);

$router->addNotFoundHandler(function () {
  header('Location: /notFound');
  exit();
});

$router->run();