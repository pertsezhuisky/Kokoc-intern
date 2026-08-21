<?php

namespace App\Connections;

use PDO;

class DatabaseConnection
{
    public function getConnection(): PDO
    {
        return new PDO(
            'mysql:host=mysql;port=3306;dbname=app;charset=utf8mb4',
            'app',
            'app',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }
}