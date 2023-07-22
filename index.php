<?php

use AlbuquerqueLucas\UserTaskManager\Router\Router;
use AlbuquerqueLucas\UserTaskManager\Controllers\ViewController;
use AlbuquerqueLucas\UserTaskManager\Controllers\AuthController;

require __DIR__ . '/vendor/autoload.php';

$router = new Router();
$viewController = new ViewController();
$authController = new AuthController();

$router->get('/', [$viewController, 'renderHomeView']);
$router->get('/notFound', [$viewController, 'renderNotFoundView']);
$router->get('/login', [$viewController, 'renderLoginView']);
$router->get('/register', [$viewController, 'renderRegisterView']);
$router->get('/tasks', [$viewController, 'renderNotImplemented']);
$router->get('/messages', [$viewController, 'renderNotImplemented']);
$router->get('/profile', [$viewController, 'renderProfileView']);
$router->post('/create-user', [$authController, 'createUserRequest']);
$router->post('/authenticate', [$authController, 'authenticate']);
$router->post('/logout', [$authController, 'deleteRequest']);

$router->addNotFoundHandler(function () {
  header('Location: /notFound');
  exit();
});

$router->run();