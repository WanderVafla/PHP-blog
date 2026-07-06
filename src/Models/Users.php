<?php
namespace Wandervafla\PhpBlog\Models;

use PDOException;
use PDO;
use Wandervafla\PhpBlog\Core\Database;

class Users
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::Connection();
    }
    public function insert(string $name, string $email, string $password)
    {
        try {
            $stmt = $this->pdo->prepare(
                '
                INSERT INTO users (name, email, password)
                VALUES (:name, :email, :password)
                '
            );
            $stmt->execute([
                "name" => $name,
                "email" => $email,
                "password" => $password,
            ]);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }
}
