<?php

namespace AlbuquerqueLucas\UserTaskManager\Controllers;

use AlbuquerqueLucas\UserTaskManager\Utils\SessionManager;

class ViewController
{

  public function renderHomeView()
  {
    $sessionData = SessionManager::getSessionData();
    list($status, $user) = $sessionData;
    $this->renderHtml('home.phtml', [
      'status' => $status,
      'user' => $user,
    ]);
  }

  public function renderLoginView()
  {
    session_start();
    $message = $_SESSION['errorMessage'];
    $this->renderHtml('login.phtml', [
      'message' => $message,
    ]);
  }

  public function renderRegisterView()
  {
    $this->renderHtml('register.phtml', []);
  }

  public function renderProfileView()
  {
    $sessionData = SessionManager::getSessionData();
    list($status, $user, $userAccess, $managementData, $allUsers) = $sessionData;
    $this->renderHtml('profile.phtml', [
      'status' => $status,
      'user' => $user,
      'userAccess' => $userAccess,
      'managementData' => $managementData,
      'allUsers' => $allUsers,
    ]);
  }

  public function renderNotImplemented()
  {
    $sessionData = SessionManager::getSessionData();
    list($status, $user) = $sessionData;
    $this->renderHtml('notImplemented.phtml', [
      'status' => $status,
      'user' => $user,
    ]);
  }

  public function renderNotFoundView()
  {
    $this->renderHtml('notFound.phtml', []);
  }

  public function renderHtml(string $templatePath, array $data)
  {
    extract($data);
    ob_start();
    require __DIR__ . "/../Views/phtml/$templatePath";
  }
}