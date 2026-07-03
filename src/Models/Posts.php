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
    public function fetchOne(int $id): array | null
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
    public function insertPost(
        string $title,
        string $imagePath,
        string $content,
        string $created_at,
        int $user_id,
    ) {
        $query = 'INSERT INTO posts
        (title, image, content, created_at, user_id) VALUES
        (:title, :image, :content, :created_at, :user_id)';

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([
            ":title" => $title,
            ":image" => $imagePath,
            ":content" => $content,
            ":created_at" => $created_at,
            ":user_id" => $user_id,
        ]);
    }
    public function update(
        int $id,
        string $title,
        string $imagePath,
        string $content,
    ) {
        try {
            $stmt = $this->pdo->prepare(
                "UPDATE posts SET title = :title, content = :content, image = :image WHERE id = :id",
            );
            $stmt->execute([
                "id" => $id,
                "title" => $title,
                "content" => $content,
                "image" => $imagePath,
            ]);
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }
}
