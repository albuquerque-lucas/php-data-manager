<?php

namespace AlbuquerqueLucas\UserTaskManager\Controllers;

use AlbuquerqueLucas\UserTaskManager\Exceptions\AuthException;
use AlbuquerqueLucas\UserTaskManager\Models\Session;
use AlbuquerqueLucas\UserTaskManager\Models\User;
use AlbuquerqueLucas\UserTaskManager\Utils\SessionManager;
use AlbuquerqueLucas\UserTaskManager\Views\LoginView;
use AlbuquerqueLucas\UserTaskManager\Views\ProfileView;
use AlbuquerqueLucas\UserTaskManager\Views\RegisterView;

class AuthController
{
    private $sessionModel;
    private $userModel;

    public function __construct()
    {
        $this->sessionModel = new Session();
        $this->userModel = new User();
    }

    public function renderLoginRequest()
    {
        session_start();
        $loginView = new LoginView();
        $sessionData = SessionManager::getSessionData();
        list($userData) = $sessionData;
        $message = $_SESSION['errorMessage'];
        $loginView->renderLoginView('login.phtml', [
            'message' => $message,
            'status' => $userData['status']
        ]);
    }

    public function renderRegisterRequest()
    {
        $registerView = new RegisterView();
        $sessionData = SessionManager::getSessionData();
        list($userData) = $sessionData;
        $registerView->renderRegisterView('register.phtml', [
            'status' => $userData['status'],
        ]);
    }

    public function renderProfileRequest()
    {
        $profileView = new ProfileView();
        $sessionData = SessionManager::getSessionData();
        list($userData, $managementData) = $sessionData;
        $profileView->renderProfileView('profile.phtml', [
        'status' => $userData['status'],
        'user' => $userData['user'],
        'userAccess' => $userData['userAccess'],
        'managementData' => $managementData['userCounting'],
        'allUsers' => $managementData['allUsers']
        ]);
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
                $userCreated = $this->userModel->create($userName, $userMail, $passwordHash, $userFirstName, $userLastName);
                
                if ($userCreated) {
                    include __DIR__ . '/components/createUserRedirect.php';
                } else {
                    $message = 'Erro inesperado. Não foi possível criar o usuário.';
                    throw new AuthException($message, 'errorMessage');
                }

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
            $sessionData = SessionManager::verifySessionState();
            $status = $sessionData['status'];
            if ($status === false) {
                if (!empty($userName) && !empty($password)) {
                    $user = $this->userModel->getByNameAndPassword($userName, $password);
                    if (!empty($user)) {
                        echo 'Usuario encontrado.';
                        if ($user['user_sessions_id'] === null) {
                            $newSession = $this->sessionModel->create();
                            $user = $this->userModel->updateUserSession($newSession['sessions_id'], $user['user_id']);

                            SessionManager::createCoockies(
                                $user['user_username'],
                                $user['user_sessions_id'],
                                $newSession['sessions_token'],
                                $newSession['sessions_serial']
                            );
                            SessionManager::createSession(
                                $user['user_username'],
                                $user['user_sessions_id'],
                                $newSession['sessions_token'],
                                $newSession['sessions_serial']
                            );

                            header('Location: /profile');
                            return;
                        } else {
                            // O usuario ja possui uma sessao associada a conta mas nao consegue ter acesso.
                            // Neste caso possivelmente sera implementado um procedimento de correcao.
                            $message = "<span>Este usuário já possui uma sessão correspondente.</span>";
                            throw new AuthException($message, 'errorMessage');
                        }
                    } else {
                        // Caso nao tenha sido encontrado um usuario com os dados fornecidos.
                        $message = "<span>Usuário ou senha inválidos.</span>";
                        throw new AuthException($message, 'errorMessage');
                    }
                } else {
                    // Caso nao tenha sido indentificado valores válidos nos inputs.
                    $message = "<span>Todos os campos precisam ser preenchidos.</span>";
                    throw new AuthException($message, 'errorMessage');
                }
            } else {
                // Caso o usuario ja esteja logado.
                $message = "<span>Você já está logado.</span>";
                throw new AuthException($message, 'errorMessage');
            }
        } catch (AuthException $exception) {
            $message = $exception->getMessage();
            $messageType = $exception->getMessageType();
            $_SESSION['errorMessage'] = $message;
            $_SESSION['errorMessageType'] = $messageType;
            header('Location: /login');
        }
    }

    // Delete Request
    public function deleteRequest(): void
    {
        $userId = filter_input(INPUT_POST, 'userid', FILTER_DEFAULT);
        $currentSession = $this->sessionModel->getSession($_COOKIE['sessions_token'], $_COOKIE['sessions_serial']);
        $this->sessionModel->delete($currentSession);
        SessionManager::deleteCookies();
        header('Location: /login');
    }
}
