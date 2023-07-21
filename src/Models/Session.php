<?php

namespace AlbuquerqueLucas\UserTaskManager\Models;

use AlbuquerqueLucas\UserTaskManager\Infrastructure\Connection;
use AlbuquerqueLucas\UserTaskManager\Utils\SessionManager;
use DateTime;
use DateTimeZone;
use \PDO;

class Session
{
  private \PDO $connection;
  public function __construct()
  {
    $this->connection = Connection::connect();
  }

  public function getAll()
  {
        $sessionsQuery = "SELECT * FROM sessions";
        $statement = $this->connection->prepare($sessionsQuery);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
  }

  public function findSession(int $id, string $token, string $serial)
  {
    $querySelectAll = "SELECT * FROM sessions WHERE sessions_userid = :userId AND sessions_token = :token AND sessions_serial = :serial;";
    $statement = $this->connection->prepare($querySelectAll);

    $id = $_COOKIE['sessions_userid'];
    $token = $_COOKIE['sessions_token'];
    $serial = $_COOKIE['sessions_serial'];

    $statement->bindValue(':userId', $id, PDO::PARAM_INT);
    $statement->bindValue(':token', $token, PDO::PARAM_INT);
    $statement->bindValue(':serial', $serial, PDO::PARAM_INT);

    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
    
  }

  public function findById(int $id):array
  {
    $querySearch = "SELECT * FROM sessions WHERE sessions_userid = :user_id";
    $searchStatement = $this->connection->prepare($querySearch);
    $searchStatement->bindValue(':user_id', $id);
    $searchStatement->execute();
    return $searchStatement->fetch(\PDO::FETCH_ASSOC);
  }

  public function insert($userId, $userName)
  {
    $queryInsert = "INSERT INTO sessions (sessions_userid, sessions_token, sessions_serial, sessions_datetime) VALUES (:userid, :token, :serial, :date)";
    $token = self::createString(32);
    $serial = self::createString(32);
    SessionManager::createCoockie($userName, $userId, $token, $serial);
    SessionManager::createSession($userName, $userId, $token, $serial);

    $statement = $this->connection->prepare($queryInsert);
    $statement->bindValue(':userid', $userId);
    $statement->bindValue(':token', $token);
    $statement->bindValue(':serial', $serial);
    $statement->bindValue(':date', $this->getDateTime());
    $statement->execute();
  }

  public function delete($session)
  {
    $queryDelete = "DELETE FROM sessions WHERE sessions_userid = :user_id;";
    $statement = $this->connection->prepare($queryDelete);
    $statement->bindValue(':user_id', $session['sessions_userid']);
    $statement->execute();
  }

  private static function createString(int $length): string
  {
      $string = "1qaz2wsx3edc4rfv5tgb6yhn7ujm8ik9ol0pQAZWSXEDCRFVTGBYHNUJMIKOLP";
      return substr(str_shuffle($string), 0, $length);
  }

  private function getDateTime()
  {
      $now = new DateTime('now');
      $now->setTimezone(new DateTimeZone('America/Sao_Paulo'));
      $dateTime = $now->format('Y-m-d H:i:s');
      return $dateTime;
  }
}

?>