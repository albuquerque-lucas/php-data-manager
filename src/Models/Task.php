<?php

namespace AlbuquerqueLucas\UserTaskManager\Models;

use AlbuquerqueLucas\UserTaskManager\Infrastructure\Connection;
use AlbuquerqueLucas\UserTaskManager\Utils\DateTimeManager;
use PDO;

class Task
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Connection::connect();
    }

    public function getAll()
    {
        $statement = $this->connection->query("SELECT * FROM tasks;");
        return $statement->fetchAll();
    }

    public function getById($id)
    {
        $querySelect = "SELECT * FROM tasks WHERE task_id = :id";
        $statement = $this->connection->prepare($querySelect);
        $statement->bindValue(':id', $id);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getByUser($userId)
    {
        $query = 'SELECT t.*, ts.task_status_name
        FROM tasks t
        JOIN task_status ts ON t.task_status_id = ts.task_status_id
        WHERE t.task_user_id = :userid';
        $statement = $this->connection->prepare($query);
        $statement->bindValue(':userid', $userId);
        $statement->execute();
        $tasks = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (!$tasks) {
            $tasks = [];
        }
        return $tasks;

    }

    public function create(
    $name,
    $description,
    $initStatus,
    $creationDate,
    $initDate,
    $conclusionDate,
    $userId
    )
    {
        $queryInsert = "INSERT into tasks(task_name, task_description, task_creation_date, task_init_date, task_conclusion_date, task_status_id, task_user_id)
        VALUES (:task_name , :task_description, :creationDate, :initDate, :conclusionDate, :statusCode, :userid)";
        $statement = $this->connection->prepare($queryInsert);
        $statement->bindValue(':task_name', $name);
        $statement->bindValue(':task_description', $description);
        $statement->bindValue(':creationDate', $creationDate);
        $statement->bindValue(':initDate', $initDate);
        $statement->bindValue(':conclusionDate', $conclusionDate);
        $statement->bindValue(':statusCode', $initStatus);
        $statement->bindValue(':userid', $userId);
        $statement->execute();
    }

    public function delete($id)
    {
        $queryDelete = "DELETE FROM tasks WHERE task_id = :id";
        $statement = $this->connection->prepare($queryDelete);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    }
    
    public function update($id, $data)
    {
        $formattedColumns = $this->getFormattedUpdateColumns($data);
        $queryUpdate = "UPDATE tasks SET $formattedColumns WHERE task_id = :id";
        $statement = $this->connection->prepare($queryUpdate);
        foreach($data as $key => $value) {
            $statement->bindValue("$key", $value);
        }
        $statement->bindValue(":id", $id);
        $statement->execute();
    }

    public function updateStatus($id)
    {
        $newStatus = $this->setNewStatus($id);
        $updateQuery = "UPDATE tasks SET task_status_id = :newStatus WHERE task_id = :id";
        $statement = $this->connection->prepare($updateQuery);
        $statement->bindValue(':newStatus', $newStatus);
        $statement->bindValue(':id', $id);
        $statement->execute();
    }

    public function updateDateTime($id)
    {
        $task = $this->getById($id);
        $result = $task['task_status_id'];
    
        if ($result == 2) {
            $updateQuery = "UPDATE tasks SET task_init_date = :initDate, task_conclusion_date = :conclusionDate WHERE task_id = :id";
            $newInitDate = DateTimeManager::getDateTime();
            $newConclusionDate = '---';
    
            $statement = $this->connection->prepare($updateQuery);
            $statement->bindValue(':id', $id);
            $statement->bindValue(':initDate', $newInitDate);
            $statement->bindValue(':conclusionDate', $newConclusionDate);
            $statement->execute();
        } elseif ($result == 3) {
            $updateQuery = "UPDATE tasks SET task_conclusion_date = :conclusionDate WHERE task_id = :id";
            $newConclusionDate = DateTimeManager::getDateTime();
    
            $statement = $this->connection->prepare($updateQuery);
            $statement->bindValue(':id', $id);
            $statement->bindValue(':conclusionDate', $newConclusionDate);
            $statement->execute();
        }
    }

    public function getTaskStatus($taskId)
    {
        $querySelect = "SELECT ts.task_status_name
        FROM tasks t
        JOIN task_status ts ON t.task_status_id = ts.task_status_id
        WHERE t.task_id = :taskId";        
    
        $statement = $this->connection->prepare($querySelect);
        $statement->bindValue(':taskId', $taskId, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    
        return $result[0]['task_status_name'];
    }

    private function getFormattedUpdateColumns($data)
    {
        $formattedColumns = array_map(function($key) {
            return "$key = :$key";
        }, array_keys($data));
        
        return implode(', ', $formattedColumns);
    }

    public function setNewStatus($taskId)
    {
        $querySelect = "SELECT task_status_id FROM tasks WHERE task_id = :id";
        $statement = $this->connection->prepare($querySelect);
        $statement->bindValue(':id', $taskId);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $final = null;
        if ($result[0]['task_status_id'] === 1){
            $final = 2;
        } else if ($result[0]['task_status_id'] === 2) {
            $final = 3;
        } else if ($result[0]['task_status_id'] === 3) {
            $final = 2;
        }
        return $final;
}

}