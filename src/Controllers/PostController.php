<?php
namespace Wandervafla\PhpBlog\Controllers;

use Wandervafla\PhpBlog\Models\Posts;
use Wandervafla\PhpBlog\Actions\UploadImageAction;
use Wandervafla\PhpBlog\Filteres\MaxStrlenFilter;

class PostController
{
    private static $viewPageDir = __DIR__ . "/../Views/Pages/";
    
    public function home()
    {
        $posts = new Posts()->fetchAll();
        require self::$viewPageDir . "Home.php";
    }
    public function create()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $title = $_POST["title"];
            $image = $_FILES["image"];

            if (!(new MaxStrlenFilter())($title, 20)) {
                die("Oversize title!");
            }

            $content = $_POST["content"];
            $created_at = "test";
            $user_id = 1;

            $destination = (new UploadImageAction())($image);                
            
            new Posts()->insertPost(
                title: $title,
                imagePath: $destination,
                content: $content,
                created_at: $created_at,
                user_id: $user_id,
            );
        }
        require self::$viewPageDir . "PostForm.php";
    }
}
