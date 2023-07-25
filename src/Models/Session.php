<?php

namespace AlbuquerqueLucas\UserTaskManager\Models;

use AlbuquerqueLucas\UserTaskManager\Infrastructure\Connection;
use AlbuquerqueLucas\UserTaskManager\Utils\SessionManager;
use DateTime;
use DateTimeZone;
use \PDO;
use PDOException;

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
      try {
          $this->connection->beginTransaction();
  
          $queryUpdateUsers = "UPDATE users SET user_sessions_id = NULL WHERE user_sessions_id = :session_id";
          $statementUpdateUsers = $this->connection->prepare($queryUpdateUsers);
          $statementUpdateUsers->bindValue(':session_id', $session['sessions_id'], PDO::PARAM_INT);
          $statementUpdateUsers->execute();
  
          $queryDeleteSession = "DELETE FROM sessions WHERE sessions_id = :session_id";
          $statementDeleteSession = $this->connection->prepare($queryDeleteSession);
          $statementDeleteSession->bindValue(':session_id', $session['sessions_id'], PDO::PARAM_INT);
          $statementDeleteSession->execute();
  
          $this->connection->commit();
  
      } catch (PDOException $e) {
          $this->connection->rollBack();
          // Aqui você pode lidar com o erro de alguma forma, por exemplo, lançando uma exceção ou registrando o erro em um arquivo de log.
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