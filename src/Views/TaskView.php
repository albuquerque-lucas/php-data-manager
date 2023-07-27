<?php

namespace AlbuquerqueLucas\UserTaskManager\Views;

class TaskView extends View
{
  public function renderTaskView(string $templatePath, array $templateData)
  {
    $this->renderHTML($templatePath, $templateData);
  }
}