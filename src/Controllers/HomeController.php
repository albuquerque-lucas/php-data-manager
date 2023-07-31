<?php

namespace AlbuquerqueLucas\UserTaskManager\Controllers;

use AlbuquerqueLucas\UserTaskManager\Utils\SessionManager;
use AlbuquerqueLucas\UserTaskManager\Views\HomeView;

class HomeController
{

    public function renderHomeRequest()
    {
      $homeView = new HomeView();
      $sessionData = SessionManager::getSessionData();
      list($userData) = $sessionData;
      $homeView->renderHomeView('home.phtml', [
        'status' => $userData['status'],
        'user' => $userData['user'],
      ]);
    }
}