<?php

namespace App\Core;

use PDO;

abstract class Model
{
    /**
     * @var PDO|null The database connection instance.
     */
    private static $db;

    /**
     * Get the singleton PDO database connection.
     *
     * @return PDO The PDO database connection instance.
     */
    protected static function db(): PDO
    {
        if (!self::$db) {
            $config = require __DIR__ . '/../../config/database.php';
            self::$db = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
                $config['user'],
                $config['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        }

        return self::$db;
    }
}
