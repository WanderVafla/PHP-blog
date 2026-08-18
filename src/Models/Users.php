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
        $stmt = $this->pdo->prepare(
            '
                INSERT INTO users (name, email, password)
                VALUES (:name, :email, :password)
                ',
        );
        $stmt->execute([
            "name" => $name,
            "email" => $email,
            "password" => $password,
        ]);
    }
    public function update(int $id, string $column, string $value)
    {
        $stmt = $this->pdo->prepare(
            "
                UPDATE users
                SET {$column} = :new_value
                WHERE id = :id
            ",
        );
        $stmt->execute([
            "new_value" => $value,
            "id" => $id
        ]);
    }
    public function select(string $email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([
            "email" => $email,
        ]);

        $data = $stmt->fetch();
        if (!$data) {
            return null;
        }
        return $data;
    }
}
