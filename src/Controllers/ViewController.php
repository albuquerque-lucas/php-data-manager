<?php

namespace AlbuquerqueLucas\UserTaskManager\Controllers;

use AlbuquerqueLucas\UserTaskManager\Utils\SessionManager;

class ViewController
{

  public function renderHomeView()
  {
    $sessionData = SessionManager::getSessionData();
    list($userData, $managementData) = $sessionData;
    $this->renderHtml('home.phtml', [
      'status' => $userData['status'],
      'user' => $userData['user'],
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
    list($userData, $managementData) = $sessionData;
    $this->renderHtml('profile.phtml', [
      'status' => $userData['status'],
      'user' => $userData['user'],
      'userAccess' => $userData['userAccess'],
      'managementData' => $managementData['userCounting'],
      'allUsers' => $managementData['allUsers'],
    ]);
  }

  public function renderNotImplemented()
  {
    $sessionData = SessionManager::getSessionData();
    list($userData, $managementData) = $sessionData;
    $this->renderHtml('notImplemented.phtml', [
      'status' => $userData['status'],
      'user' => $userData['user'],
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