<?php

namespace AlbuquerqueLucas\UserTaskManager\Controllers;

use AlbuquerqueLucas\UserTaskManager\Models\Task;
use AlbuquerqueLucas\UserTaskManager\Views\TaskView;
use AlbuquerqueLucas\UserTaskManager\Utils\DateTimeManager;
use AlbuquerqueLucas\UserTaskManager\Utils\SessionManager;

class TaskController
{
    private TaskView $taskView;
    private Task $Task;

    public function __construct()
    {
        $this->Task = new Task();
    }

    public function renderTaskView()
    {
        $taskView = new TaskView();
        $sessionData = SessionManager::getSessionData();
        list($userData) = $sessionData;
        $taskView->renderTaskView('tasks.phtml', []);
    }

    public function updateStatusRequest($id)
    {
        $this->Task->updateStatus($id);
        $this->Task->updateDateTime($id);
        header('Location: /tasks');
    }

    public function createRequest():void
    {

        $name = filter_input(INPUT_POST, 'task_name', FILTER_DEFAULT);
        $description = filter_input(INPUT_POST, 'task_description', FILTER_DEFAULT);
        $userId = filter_input(INPUT_POST, 'task_user_id', FILTER_DEFAULT);
        $initialStatus = 1;
        $creationDate = DateTimeManager::getDateTime();
        $initDate = '---';
        $conclusionDate = '---';
        $this->Task->create(
        $name,
        $description,
        $initialStatus,
        $creationDate,
        $initDate,
        $conclusionDate,
        $userId
    );
        header('Location: /tasks');
    }

    public function updateRequest($data)
    {

        $taskId = $data['taskId'];
        $text = $data['text'];
        $column = $data['column'];
        $dataToUpdate = [$column => $text];
        $this->Task->update($taskId, $dataToUpdate);
    }

    public function removeRequest(int $id):void
    {
        $this->Task->delete($id);
        header('Location: /tasks');
    }
}