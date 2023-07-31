<?php

namespace AlbuquerqueLucas\UserTaskManager\Infrastructure;

use PDO;
use PDOException;

class Connection
{
    public static function connect()
    {
        $hostName = 'containers-us-west-89.railway.app';
        $port = '7241';
        $dbName = 'task_manager';
        $userName = 'root';
        $password = 'FUdfoWuE5r1DJxJTBwhQ';
        try {
            return new PDO("mysql:host=$hostName;port=$port;dbname=$dbName", $userName, $password);
        } catch (PDOException $PDOException) {
            echo "Falha na conexão: " . $PDOException->getMessage() . "<br/>";
            echo "Arquivo: " . $PDOException->getFile() . "<br/>";
        }
    }
}