<?php

namespace AlbuquerqueLucas\UserTaskManager\Views;

class HomeView extends View
{
  public function renderHomeView(string $templatePath, array $templateData)
  {
    $this->renderHTML($templatePath, $templateData);
  }
}