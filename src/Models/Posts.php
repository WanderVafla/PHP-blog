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
}