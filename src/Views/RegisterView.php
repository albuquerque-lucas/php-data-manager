<?php

namespace AlbuquerqueLucas\UserTaskManager\Views;

class RegisterView extends View
{
  public function renderRegisterView(string $templatePath, array $templateData)
  {
    $this->renderHTML($templatePath, $templateData);
  }
}