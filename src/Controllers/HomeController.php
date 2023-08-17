<?php

namespace AlbuquerqueLucas\UserTaskManager\Controllers;

use AlbuquerqueLucas\UserTaskManager\Utils\SessionManager;
use AlbuquerqueLucas\UserTaskManager\Views\HomeView;

class HomeController
{

    public function renderHomeRequest()
    {
      echo "Pagina inicial";
      var_dump($_SERVER);
      echo "Dump somente da Request URI:";
      var_dump($_SERVER['REQUEST_URI']);
      echo "Fazer um dump de PATH INFO se houver";
      var_dump($_SERVER['PATH_INFO']);
      echo "Dump do PHP Self:";
      var_dump($_SERVER['PHP_SELF']);
      // $homeView = new HomeView();
      // $sessionData = SessionManager::getSessionData();
      // list($userData) = $sessionData;
      // $homeView->renderHomeView('home.phtml', [
      //   'status' => $userData['status'],
      //   'user' => $userData['user'],
      // ]);
    }
}