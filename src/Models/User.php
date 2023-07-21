<?php

namespace AlbuquerqueLucas\UserTaskManager\Models;

use AlbuquerqueLucas\UserTaskManager\Infrastructure\Connection;
use \PDO;

class User
{
  private \PDO $connection;
  public function __construct()
  {
    $this->connection = Connection::connect();
  }

  public function getAll()
  {
    $querySelect = "SELECT * FROM users";
    $statement = $this->connection->prepare($querySelect);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  public function create(
  $userName, 
  $userMail,
  $userPassword,
  $userFirstName,
  $userLastName
)
  {
      $queryCreate = "INSERT INTO users (user_username, user_email, user_password_hash, user_access_level_code, user_firstname, user_lastname, user_fullname)
        VALUES (:username, :usermail, :passwordhash, :useraccess, :firstname, :lastname, :fullname)";
        $filteredFirstName = $this->sanitizeString($userFirstName);
        $filteredLastName = $this->sanitizeString($userLastName);
        $fullName = "$filteredFirstName $filteredLastName";
        $basicAccessLevel = 1;
        $statement = $this->connection->prepare($queryCreate);
        $statement->bindValue(':username', $userName);
        $statement->bindValue(':usermail', $userMail);
        $statement->bindValue(':passwordhash', $userPassword);
        $statement->bindValue(':useraccess', $basicAccessLevel);
        $statement->bindValue(':firstname', $filteredFirstName);
        $statement->bindValue(':lastname', $filteredLastName);
        $statement->bindValue(':fullname', $fullName);
        $statement->execute();
    }

public function getByName($userName)
{
  $querySelectByName = "SELECT * FROM users WHERE user_username = :username";

  $statement = $this->connection->prepare($querySelectByName);
  $statement->bindValue(':username', $userName);
  $statement->execute();

  return $statement->fetch(PDO::FETCH_ASSOC);
}

public function getByNameAndPassword($userName, $password)
{
  $querySelect = "SELECT * FROM users WHERE user_username = :username";

  $statement = $this->connection->prepare($querySelect);
  $statement->bindValue(':username', $userName);
  $statement->execute();
  $result = $statement->fetch(PDO::FETCH_ASSOC);
  if (!$result) {
    return false;
  }
  $userPasswordHash = $result['user_password_hash'];
  if (password_verify($password, $userPasswordHash)) {
    return $result;
} else if ($password === $userPasswordHash) {
  return $result;
}
  else {
    $message = "<span>Usuário ou senha inválidos. Errinho da classe User.</span>";
    $_SESSION['errorMessage'] = $message;
    $_SESSION['errorMessageType'] = 'errorMessage';
    header('Location: /login');
}
}

public function getUserSession($id)
{
  $querySelectSession = "SELECT u.* FROM users u JOIN sessions s ON u.user_id = :id";
  $statement = $this->connection->prepare($querySelectSession);
  $statement->bindValue(':id', $id);
  $statement->execute();
  return $statement->fetch(PDO::FETCH_ASSOC);
}

public function getUserAccess($id)
{
  $querySelect = "SELECT u.user_access_level_code, al.access_level_name
  FROM users u
  JOIN access_levels al ON u.user_access_level_code = al.access_level_code
  WHERE u.user_id = :id";

$statement = $this->connection->prepare($querySelect);
$statement->bindValue(':id', $id);
$statement->execute();

$result = $statement->fetch(PDO::FETCH_ASSOC);
return $result;
}

public function getUserTasks($userId)
{
  $querySelect = "SELECT t.*
  FROM tasks t
  WHERE t.task_user_id = :userId";

  $statement = $this->connection->prepare($querySelect);
  $statement->bindValue(':userId', $userId);
  $statement->execute();

  $result = $statement->fetchAll(PDO::FETCH_ASSOC);
  return $result;
}

public function getUserManagementData()
{
  $querySelect = "SELECT COUNT(*) AS users_total_count,
  SUM(CASE WHEN al.access_level_name = 'Basic' THEN 1 ELSE 0 END) AS users_basic,
  SUM(CASE WHEN al.access_level_name = 'Reviewer' THEN 1 ELSE 0 END) AS users_reviewer,
  SUM(CASE WHEN al.access_level_name = 'Administrator' THEN 1 ELSE 0 END) AS users_administrator,
  SUM(CASE WHEN al.access_level_name = 'Chief Administrator' THEN 1 ELSE 0 END) AS users_chief_administrator
FROM users u
JOIN access_levels al ON u.user_access_level_code = al.access_level_code";

$statement = $this->connection->prepare($querySelect);
$statement->execute();

$result = $statement->fetch(PDO::FETCH_ASSOC);
return $result;
}

public function sanitizeString($string)
{
    $filteredString = ucfirst(strtolower(trim($string)));
    return $filteredString;
}

}