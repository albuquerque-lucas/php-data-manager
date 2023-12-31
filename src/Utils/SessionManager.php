<?php

namespace AlbuquerqueLucas\UserTaskManager\Utils;

use AlbuquerqueLucas\UserTaskManager\Models\Session;
use AlbuquerqueLucas\UserTaskManager\Models\User;

class SessionManager
{
    public static function verifySessionState(): array
    {
        if(!isset($_SESSION)){
                session_start();
            }
        if (
            isset($_COOKIE['sessions_id'])
            && isset($_COOKIE['sessions_token'])
            && isset($_COOKIE['sessions_serial'])
            )
            {
                $id = $_COOKIE['sessions_id'];
                $token = $_COOKIE['sessions_token'];
                $serial = $_COOKIE['sessions_serial'];
                $username = $_COOKIE['sessions_username'];
                $sessionModel = new Session();
                $userModel = new User();
                $session = $sessionModel->getSession($token, $serial);
                $user = $userModel->getByUserName($username);
                if ($session['sessions_id'] > 0) {
                if($user['user_sessions_id'] == $_COOKIE['sessions_id']
                && $session['sessions_token'] == $_COOKIE['sessions_token']
                && $session['sessions_serial'] == $_COOKIE['sessions_serial']) {
                        if($user['user_sessions_id'] == $_SESSION['sessions_id']
                        && $session['sessions_token'] == $_SESSION['sessions_token']
                        && $session['sessions_serial'] == $_SESSION['sessions_serial']) {
                            return [
                                'status' => true,
                                'token' => $token,
                                'serial' => $serial,
                            ];
                        } else{
                            // Caso nao sejam encontradas correspondencias entre os dados das tabelas e os dados de sessao.
                            // Neste caso sao criados novos dados de sessao de acordo com os cookies.
                            self::createSession($username, $id, $token, $serial);
                            return [
                                'status' => true,
                                'token' => $token,
                                'serial' => $serial,
                            ];
                        }
                } else {
                    // Caso nao sejam encontradas correspondencias entre os dados das tabelas e os cookies.
                    return [
                        'status' => false,
                        'token' => '',
                        'serial' => '',
                    ];
                }
            } else {
                // Caso nao seja encontrada uma sessao valida.
                return [
                    'status' => false,
                    'token' => '',
                    'serial' => '',
                ];
            }
            
        }
        // Caso os cookies nao estejam setados.
        return [
            'status' => false,
            'token' => '',
            'serial' => '',
        ];
    }

    public static function getSessionData(): array
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $sessionModel = new Session();
        $newUser = new User();
        $sessionData = self::verifySessionState();
        $token = $sessionData['token'];
        $serial = $sessionData['serial'];
        $session = $sessionModel->getSession($token, $serial);
        if(empty($session)){
            $userData = [
                'status' => false,
                'user' => [
                    'user_username' => 'guest',
                    'user_id' => 0
                ],
                'userAccess' => 'visitor' 
            ];

            $managementData = [
                'message' => 'Você não tem acesso aos dados de gerenciamento'
            ];
            return [$userData, $managementData];
        } else {
            $status = true;
            $sessionsId = $session['sessions_id'];
            $user = $newUser->getSessionUser($sessionsId);
            $userAccess = $newUser->getUserAccess($user['user_id']);
            //$userTasks = $newUser->getUserTasks($userId);
            $managementData = $newUser->getUserCountByLevel();
            $allUsers = $newUser->getAll();
        }

        $userData = [
            'status' => $status,
            'user' => $user,
            'userAccess' => $userAccess,
            // 'userTasks' => $userTasks,
        ];

        $managementData = [
            'userCounting' => $managementData,
            'allUsers' => $allUsers,
        ];
        return [$userData, $managementData];
    }

    public static function createSession($userName, $userId, $token, $serial)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['sessions_username'] = $userName;
        $_SESSION['sessions_id'] = $userId;
        $_SESSION['sessions_token'] = $token;
        $_SESSION['sessions_serial'] = $serial;
    }


    public static function createCoockies($userName, $userId, $token, $serial): void
    {
        $expirationTime = time() + (30 * 24 * 60 * 60);
        setcookie('sessions_username', $userName, time() + $expirationTime, "/");
        setcookie('sessions_id', $userId, time() + $expirationTime, "/");
        setcookie('sessions_token', $token, time() + $expirationTime, "/");
        setcookie('sessions_serial', $serial, time() + $expirationTime, "/");
    }

    public static function deleteCookies(): void
    {
        setcookie('sessions_username', '', time() - 1, "/");
        setcookie('sessions_id', '', time() - 1, "/");
        setcookie('sessions_token', '', time() - 1, "/");
        setcookie('sessions_serial', '', time() - 1, "/");
    }
}