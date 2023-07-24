<?php

namespace AlbuquerqueLucas\UserTaskManager\Models;

use AlbuquerqueLucas\UserTaskManager\Infrastructure\Connection;
use AlbuquerqueLucas\UserTaskManager\Utils\SessionManager;
use DateTime;
use DateTimeZone;
use \PDO;

class Session
{
  private PDO $connection;
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

  public function getSession(string $token, string $serial)
  {
    $querySelectAll = "SELECT * FROM sessions WHERE sessions_token = :token AND sessions_serial = :serial;";
    $statement = $this->connection->prepare($querySelectAll);
    $statement->bindValue(':token', $token, PDO::PARAM_INT);
    $statement->bindValue(':serial', $serial, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    return $result;
    
  }

  // public function getByToken(string $sessionToken)
  // {
  //   $query = "SELECT * FROM sessions WHERE sessions_token = :token";
  //   $statement = $this->connection->prepare($query);
  //   $statement->bindValue(':token', $sessionToken);
  //   $statement->execute();
  //   $result = $statement->fetch(\PDO::FETCH_ASSOC);

  //   return $result;
  // }

  public function create()
  {
    $queryInsert = "INSERT INTO sessions (sessions_token, sessions_serial, sessions_datetime) VALUES (:token, :serial, :date)";
    $token = self::createString(32);
    $serial = self::createString(32);
    $date = $this->getDateTime();

    $statement = $this->connection->prepare($queryInsert);
    $statement->bindValue(':token', $token);
    $statement->bindValue(':serial', $serial);
    $statement->bindValue(':date', $date);
    $statement->execute();
    $result = $this->getSession($token, $serial);
    return $result;
  }

  public function delete($session)
  {
    $querySelect = "SELECT user_sessions_id FROM users WHERE user_id = :user_id;";
    $statement = $this->connection->prepare($querySelect);
    $statement->bindValue(':user_id', $session['sessions_user_id']);
    $statement->execute();

    $result = $statement->fetch();
    $userSessionsId = $result['user_sessions_id'];

    if ($userSessionsId) {
        $queryDelete = "DELETE FROM sessions WHERE sessions_id = :sessions_id;";
        $statement = $this->connection->prepare($queryDelete);
        $statement->bindValue(':sessions_id', $userSessionsId);
        $statement->execute();
    }
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