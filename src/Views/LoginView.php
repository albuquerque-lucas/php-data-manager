<?php

namespace AlbuquerqueLucas\UserTaskManager\Views;

class LoginView extends View
{
  public function renderLoginView(string $templatePath, array $templateData)
  {
    $this->renderHTML($templatePath, $templateData);
  }
}