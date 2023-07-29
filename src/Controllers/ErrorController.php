<?php

namespace AlbuquerqueLucas\UserTaskManager\Controllers;

use AlbuquerqueLucas\UserTaskManager\Utils\SessionManager;

class ErrorController
{

  public function renderNotImplemented()
  {
    $sessionData = SessionManager::getSessionData();
    list($userData) = $sessionData;
    $this->renderHtml('notImplemented.phtml', [
      'status' => $userData['status'],
      'user' => $userData['user']
    ]);
  }

  public function renderNotFound()
  {
    $sessionData = SessionManager::getSessionData();
    list($userData) = $sessionData;
    $this->renderHtml('notFound.phtml', [
      'status' => $userData['status']
    ]);
  }

  public function renderHtml(string $templatePath, array $data)
  {
    extract($data);
    ob_start();
    require __DIR__ . "/../Views/phtml/$templatePath";
  }
}