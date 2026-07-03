<?php
namespace Wandervafla\PhpBlog\Controllers;

use Wandervafla\PhpBlog\Actions\Security\ValidateCsrfAction;
use Wandervafla\PhpBlog\Models\Posts;
use Wandervafla\PhpBlog\Actions\UploadImageAction;
use Wandervafla\PhpBlog\Filteres\MaxStrlenFilter;

use Exception;

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
            new ValidateCsrfAction();
            
            $title = $_POST["title"];
            $image = $_FILES["image"];

            if (!new MaxStrlenFilter()($title, 20)) {
                die("Oversize title!");
            }

            $content = $_POST["content"];
            $created_at = "test";
            $user_id = 1;

            $destination = new UploadImageAction()($image);

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
    public function edit()
    {
        $postModel = new Posts();
        (int) ($id = $_GET["id"]);
        // get old datas form db for display it on page

        $data = $postModel->fetchOne($id);
        if (!$data) {
            http_response_code(404);
            exit("Post not found");
        }
        (string) ($title = $data["title"]);
        (string) ($content = $data["content"]);
        (string) ($image = $data["image"]);

        if (!file_exists($image) || !$image) {
            $image = "asset/notImage.png";
        }
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            new ValidateCsrfAction();

            $title_post = $_POST["title"];
            $content_post = $_POST["content"];
            $image_post = $_FILES["image"];

            if (!new MaxStrlenFilter()($title, 20)) {
                die("Oversize title!");
            }
            if (
                isset($image_post["error"]) &&
                $image_post["error"] === UPLOAD_ERR_OK
            ) {
                $destination = new UploadImageAction()($image_post);
                if (!empty($image) && file_exists($image)) {
                    unlink($image);
                }
            }
            $postModel->update(
                id: $id,
                title: $title_post ?? $title,
                imagePath: $destination ?? $image,
                content: $content_post ?? $content,
            );
        }
        require self::$viewPageDir . "EditPostPage.php";
    }
}
