<?php
namespace Wandervafla\PhpBlog\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function Connection(): PDO
    {
        if (self::$instance === null) {
            $dbPath = __DIR__ . "/../../db/database.db";

            try {
                    
                self::$instance = new PDO("sqlite:" . $dbPath);
                self::$instance->setAttribute(
                    PDO::ATTR_ERRMODE,
                    PDO::ERRMODE_EXCEPTION,
                );
                self::$instance->exec("PRAGMA foreign_keys = ON;");
            } catch (PDOException $e) {
                die('Database connection failed: ' . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
