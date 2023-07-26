<?php

namespace AlbuquerqueLucas\UserTaskManager\Views;

class View
{

  public function renderHTML(string $templatePath, array $templateData)
  {
    extract($templateData);
    ob_start();
    require __DIR__ . '/../Views/phtml/' . $templatePath;
  }

}