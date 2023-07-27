<?php

namespace AlbuquerqueLucas\UserTaskManager\Views;

class ProfileView extends View
{
  public function renderProfileView(string $templatePath, array $templateData)
  {
    $this->renderHTML($templatePath, $templateData);
  }
}