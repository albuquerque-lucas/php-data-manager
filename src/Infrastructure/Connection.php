<?php

namespace AlbuquerqueLucas\UserTaskManager\Infrastructure;

use PDO;
use PDOException;

class Connection
{
    public static function connect()
    {
        $hostName = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $dbName = $_ENV['DB_NAME'];
        $userName = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];
        try {
            return new PDO("mysql:host=$hostName;port=$port;dbname=$dbName", $userName, $password);
        } catch (PDOException $PDOException) {
            echo "Falha na conexão: " . $PDOException->getMessage() . "<br/>";
            echo "Arquivo: " . $PDOException->getFile() . "<br/>";
        }
    }
}