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

        $foundUser = $this->userModel->getByUserName($userName);
        if (!$foundUser) {
            $this->userModel->create($userName, $userMail, $passwordHash, $userFirstName, $userLastName);
            $createdUser = $this->userModel->getByUserName($userName);
            $_SESSION['createUserName'] = $userName;
            $_SESSION['createPassword'] = $userPassword;
            include __DIR__ . '/components/createUserRedirect.php';
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

    $sessionData = SessionManager::verifySessionState();
    $isSessionOn = $sessionData['status'];

    $token = $sessionData['token'];
    $serial = $sessionData['serial'];

    if (!$isSessionOn) {
        echo 'User is not logged in.';
        if (!empty($userName) && !empty($password)) {

            $user = $this->userModel->getByNameAndPassword($userName, $password);
            if (!empty($user)) {
                $newSessionData = $this->sessionModel->create();
                $newSession = $this->sessionModel->getSession($newSessionData['token'], $newSessionData['serial']);
                $updatedUser = $this->userModel->updateUserSession($newSession['sessions_id'], $user['user_id']);
                SessionManager::createCoockies(
                    $updatedUser['user_username'],
                    $updatedUser['user_id'],
                    $token,
                    $serial
                );
                SessionManager::createSession(
                    $updatedUser['user_username'],
                    $updatedUser['user_id'],
                    $token,
                    $serial
                );
                header("Location: /profile");
                // header("Location: /profile?code={$updatedUser['user_id']}");
                return;
            } else {
                var_dump('Usuario nao encontrado.');
            }
        }




    } else {
        echo 'User is already logged in.';
    }

    exit();
    
    // try {
    //     if (!SessionManager::verifySessionState()) {
            
    //         if (empty($userName) || empty($password)) {
    //             if (isset($_SESSION['createUserName']) && isset($_SESSION['createPassword'])) {
    //                 $userName = $_SESSION['createUserName'];
    //                 $password = $_SESSION['createPassword'];
    //             }
    //         }
    //         if (!empty($userName) && !empty($password)) {
    //             $user = $this->userModel->getByNameAndPassword($userName, $password);

    //             if (isset($user)) {
    //                 $this->sessionModel->insert($user['user_id'], $user['user_username']);
    //                 $message = "<h4>Seja bem-vindo, {$user['user_fullname']}!</h4>";
    //                 $_SESSION['welcomeMessage'] = $message;
    //                 header('Location: /profile');
    //                 return;
    //             } else {
    //                 $message = "<span>Usuário ou senha inválidos.</span>";
    //                 throw new AuthException($message, 'errorMessage');
    //             }
    //         }
    //     } else {
    //         $message = "<span>Você já está logado!</span>";
    //         throw new AuthException($message, 'authMessage');
    //     }
    // } catch (AuthException $exception) {
    //     $message = $exception->getMessage();
    //     $messageType = $exception->getMessageType();
    //     $_SESSION['errorMessage'] = $message;
    //     $_SESSION['errorMessageType'] = $messageType;
    //     header('Location: /login');
    // }
}
    //Delete Request
    public function deleteRequest(): void
    {
        echo 'Delete Request.';
        exit();
        // $userId = filter_input(INPUT_POST, 'userid', FILTER_DEFAULT);
        // $currentSession = $this->userModel->getUserSession($userId);
        // $this->sessionModel->delete($currentSession);
        // SessionManager::deleteCookies();
        // header('Location: /login');
    }
}