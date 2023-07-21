<?php

namespace AlbuquerqueLucas\UserTaskManager\Utils;

use AlbuquerqueLucas\UserTaskManager\Models\Session;
use AlbuquerqueLucas\UserTaskManager\Models\User;

class SessionManager
{
    public static function verifySessionState(): bool
    {
        if(!isset($_SESSION)){
            session_start();
        }
        if(isset($_COOKIE['sessions_userid']) && isset($_COOKIE['sessions_token']) && isset($_COOKIE['sessions_serial'])){
            
            $id = $_COOKIE['sessions_userid'];
            $token = $_COOKIE['sessions_token'];
            $serial = $_COOKIE['sessions_serial'];
            $newSession = new Session();
            $session = $newSession->findSession($id, $token, $serial);
            if ($session['sessions_id'] > 0) {
                if($session['sessions_userid'] == $_COOKIE['sessions_userid']
                && $session['sessions_token'] == $_COOKIE['sessions_token']
                && $session['sessions_serial'] == $_COOKIE['sessions_serial']) {
                        if($session['sessions_userid'] == $_SESSION['sessions_id']
                        && $session['sessions_token'] == $_SESSION['sessions_token']
                        && $session['sessions_serial'] == $_SESSION['sessions_serial']) {
                            return true;
                        } else{
                            self::createSession(
                                $_COOKIE['user_username'],
                                $id,
                                $token,
                                $serial
                            );
                            return true;
                        }
                } else {
                    return false;
                }
            } else {
                return false;
            }
            
        }
        return false;
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

    public static function getSessionData()
    {
        $newSession = new Session();
        $newUser = new User();
        $result = $newSession->getAll();
        $status = self::verifySessionState();
        if(!$result){
            return false;
        } else {
            $userId = $result['sessions_userid'];
            $user = $newUser->getUserSession($userId);
            $userAccess = $newUser->getUserAccess($userId);
            $managementData = $newUser->getUserManagementData();
            $userTasks = $newUser->getUserTasks($userId);
            $allUsers = $newUser->getAll();
        }
        return [$status, $user, $userAccess, $managementData, $allUsers, $userTasks];
    }

    public static function createCoockie($userName, $userId, $token, $serial): void
    {
        $expirationTime = time() + (30 * 24 * 60 * 60);
        setcookie('sessions_userid', $userId, time() + $expirationTime, "/");
        setcookie('sessions_username', $userName, time() + $expirationTime, "/");
        setcookie('sessions_token', $token, time() + $expirationTime, "/");
        setcookie('sessions_serial', $serial, time() + $expirationTime, "/");
    }

    public static function deleteCookies(): void
    {
        setcookie('sessions_userid', '', time() - 1, "/");
        setcookie('sessions_username', '', time() - 1, "/");
        setcookie('sessions_token', '', time() - 1, "/");
        setcookie('sessions_serial', '', time() - 1, "/");
        header('Location: /home');
    }
}