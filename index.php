<?php

use AlbuquerqueLucas\UserTaskManager\Router\Router;
use AlbuquerqueLucas\UserTaskManager\Controllers\ViewController;
use AlbuquerqueLucas\UserTaskManager\Controllers\AuthController;
use AlbuquerqueLucas\UserTaskManager\Controllers\TaskController;

require __DIR__ . '/vendor/autoload.php';

$router = new Router();
$viewController = new ViewController();
$authController = new AuthController();
$taskController = new TaskController();

$router->get('/', [$viewController, 'renderHomeView']);
$router->get('/notFound', [$viewController, 'renderNotFoundView']);
$router->get('/login', [$viewController, 'renderLoginView']);
$router->get('/register', [$viewController, 'renderRegisterView']);
$router->get('/tasks', [$viewController, 'renderNotImplemented']);
$router->get('/messages', [$viewController, 'renderNotImplemented']);
$router->get('/profile', [$viewController, 'renderProfileView']);
$router->get('/tasks', [$taskController, 'renderTaskView']);
$router->post('/create-user', [$authController, 'createUserRequest']);
$router->post('/authenticate', [$authController, 'authenticate']);
$router->post('/logout', [$authController, 'deleteRequest']);
$router->post('/create-task', [$taskController, 'createRequest']);
$router->post('/delete-task', [$taskController, 'deleteRequest']);

$router->addNotFoundHandler(function () {
  header('Location: /notFound');
  exit();
});

$router->run();