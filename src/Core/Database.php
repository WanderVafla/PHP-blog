<?php
namespace Wandervafla\PhpBlog\Core;

use Error;
use Exception;
use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    private static $dbPath = __DIR__ . "/../../db/database.db";
    private static $schemaFile = __DIR__ . '/../../db/schema.sql';
    
    public static function Connection(): PDO
    {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO("sqlite:" . self::$dbPath);
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
    public static function initSchema(PDO $pdo)
    {
        if (!file_exists(self::$schemaFile)) {
            throw new Exception(SQLSchemaNotFound);
        }

        $sqlSchema = file_get_contents(self::$schemaFile);
        if (isset($pdo)) {
            $pdo->exec($sqlSchema);
        }
    }
}
