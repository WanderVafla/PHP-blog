<?php
namespace Wandervafla\PhpBlog\Models;

use Exception;
use PDO;
use PDOException;
use Wandervafla\PhpBlog\Core\Database;

class Posts
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::Connection();
    }
    public function fetchAll(): array
    {
        return $this->pdo
            ->query("SELECT * FROM posts")
            ->fetchAll(PDO::FETCH_ASSOC);
    }
    public function fetchOne(int $id): array|null
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM posts WHERE id=:id");
            $stmt->execute(["id" => $id]);
            $data = $stmt->fetch();
            if (!$data) {
                return null;
            }
            return $data;
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }
    public function delete(int $id)
    {
        $stmt = $this->pdo->prepare(
            'DELETE FROM posts WHERE id = :id'
        );
        $stmt->execute(['id' => $id]);
    }
    public function upsert(
        int|null $id,
        string $title,
        string $imagePath,
        string $content,
        string $created_at,
        int $user_id,
    ) {
        try {
            $stmt = $this->pdo->prepare(
            'INSERT INTO posts
            (id, title, image, content, created_at, user_id) 
            VALUES 
            (:id, :title, :image, :content, :created_at, :user_id)
            
            ON CONFLICT(id)
            DO UPDATE SET
                title = EXCLUDED.title,
                content = EXCLUDED.content,
                image = EXCLUDED.image',
            );
            $stmt->execute([
                ":id" => $id,
                ":title" => $title,
                ":image" => $imagePath,
                ":content" => $content,
                ":created_at" => $created_at,
                ":user_id" => $user_id,
            ]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }
}
