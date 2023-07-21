<?php

namespace AlbuquerqueLucas\UserTaskManager\Controllers;

use AlbuquerqueLucas\UserTaskManager\Exceptions\AuthException;
use AlbuquerqueLucas\UserTaskManager\Models\Session;
use AlbuquerqueLucas\UserTaskManager\Models\User;
use AlbuquerqueLucas\UserTaskManager\Utils\SessionManager;

class AuthController
{
    private $sessionModel;
    private $userModel;

    public function __construct()
    {
        $this->sessionModel = new Session();
        $this->userModel = new User();
    }

    public function createUserRequest()
{
    session_start();
    try {
        $userName = filter_input(INPUT_POST, 'username', FILTER_DEFAULT);
        $userMail = filter_input(INPUT_POST, 'email', FILTER_DEFAULT);
        $userPassword = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
        $userFirstName = filter_input(INPUT_POST, 'firstname', FILTER_DEFAULT);
        $userLastName = filter_input(INPUT_POST, 'lastname', FILTER_DEFAULT);
        $passwordHash = password_hash($userPassword, PASSWORD_ARGON2ID);

        $foundUser = $this->userModel->getByName($userName);
        if (!$foundUser) {
            $this->userModel->create($userName, $userMail, $passwordHash, $userFirstName, $userLastName);
            $createdUser = $this->userModel->getByName($userName);
            $_SESSION['createUserName'] = $userName;
            $_SESSION['createPassword'] = $userPassword;
            header('Location: /authenticate');
        } else {
            $message = "<span>Nome de usuário indisponível.</span>";
            throw new AuthException($message, 'errorMessage');
        }
    } catch (AuthException $exception) {
        $message = $exception->getMessage();
        $messageType = $exception->getMessageType();
        $_SESSION['errorMessage'] = $message;
        $_SESSION['errorMessageType'] = $messageType;
        header('Location: /register');
    }
}

    public function authenticate(): void
{
    $userName = filter_input(INPUT_POST, 'username', FILTER_DEFAULT);
    $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);

    try {
        if (!SessionManager::verifySessionState()) {
            if (empty($userName) || empty($password)) {
                if (isset($_SESSION['createUserName']) && isset($_SESSION['createPaswword'])) {
                    $userName = $_SESSION['createUserName'];
                    $password = $_SESSION['createPaswword'];
                }
            }
            var_dump($userName, $password);
            exit();
            if (!empty($userName) && !empty($password)) {
                $user = $this->userModel->getByNameAndPassword($userName, $password);

                if ($user['user_id'] > 0) {
                    $this->sessionModel->insert($user['user_id'], $user['user_username']);
                    $message = "<h4>Seja bem-vindo, {$user['user_fullname']}!</h4>";
                    $_SESSION['welcomeMessage'] = $message;
                    header('Location: /profile');
                    return;
                }
            }
            $message = "<span>Usuário ou senha inválidos.</span>";
            throw new AuthException($message, 'errorMessage');
        } else {
            $message = "<span>Você já está logado!</span>";
            throw new AuthException($message, 'authMessage');
        }
    } catch (AuthException $exception) {
        $message = $exception->getMessage();
        $messageType = $exception->getMessageType();
        $_SESSION['errorMessage'] = $message;
        $_SESSION['errorMessageType'] = $messageType;
        header('Location: /login');
    }
}
    //Delete Request
    public function deleteRequest(): void
    {
        $userId = filter_input(INPUT_POST, 'userid', FILTER_DEFAULT);
        $currentSession = $this->sessionModel->findById($userId);
        $this->sessionModel->delete($currentSession);
        SessionManager::deleteCookies();
        header('Location: /login');
    }
}