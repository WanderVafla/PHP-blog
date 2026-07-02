<?php
namespace Wandervafla\PhpBlog\Models;

use PDO;
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
        return $this->pdo->query("SELECT * FROM posts")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function insertPost(string $title, string $imagePath, string $content, string $created_at, int $user_id) {
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
}