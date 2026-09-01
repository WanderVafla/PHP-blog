<?php
namespace Wandervafla\PhpBlog\Controllers;

use Wandervafla\PhpBlog\Actions\Security\ValidateCsrfAction;
use Wandervafla\PhpBlog\Models\Posts;
use Wandervafla\PhpBlog\Actions\UploadImageAction;
use Wandervafla\PhpBlog\Filteres\MaxStrlenFilter;
use Wandervafla\PhpBlog\Core\Session;

use Exception;

class PostController
{
    private static $viewPageDir = __DIR__ . "/../Views/Pages/";

    public function home()
    {
        $postsClass = new Posts();
        $posts = $postsClass->fetchAll();
        
        $categories_post = $postsClass->fetchAllCatogories_post();
        $categoriesNames = $postsClass->fetchAllCatogories();

        
        foreach ($categories_post as $categorie_post) {
            $post_id = $categorie_post['post_id'];
            $categorie_id = $categorie_post['categories_id'];
            $key = array_search($post_id, array_column($posts, 'id'));
            $posts[$key]["categorieName"] = $categoriesNames[$categorie_id]['title'];
        }
        
        $categorie = $posts['categorie_id'] ?? null;
        require self::$viewPageDir . "Home.php";
    }
    public function upster()
    {
        Session::notLoggedRedirect();

        $postModel = new Posts();

        $errors = [];

        (int) $id = $_GET["id"] ?? null;
        $categories = $postModel->fetchAllCatogories();
        
        if (isset($id)) {
            $data = $postModel->fetchOne($id);
            if (!$data) {
                http_response_code(404);
                exit("Post not found");
            }
            if (!Session::isByCurrentUser($data['user_id'])) {
                header("Location: /post?id=$id");
                exit();
            }
            $title = $content = $image = "";

            (string) ($title = $data["title"]);
            (string) ($content = $data["content"]);
            (string) ($image_path = $data["image"]);
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            ValidateCsrfAction::validate();

            $titleStrlenFilter = new MaxStrlenFilter();
            $uploadImageAction = new UploadImageAction();

            $new_title = $_POST["title"];

            (string) ($created_at = Session::getUsername());
            (int) ($user_id = Session::getUserId());

            if (empty($new_title)) {
                $errors["title"] = "Title is reuquired";
            } elseif (!$titleStrlenFilter($new_title, 20)) {
                $errors["title"] = "Title should be less that 20 characters";
            }

            $new_content = $_POST["content"];
            $selectedCategorie = $_POST['selected-categories'];
            if (empty($new_content)) {
                $errors["content"] = "Title is reuquired";
            }

            $new_image = $_FILES["image"];

            try {
                if (
                    isset($new_image["error"]) &&
                    $new_image["error"] === UPLOAD_ERR_OK
                ) {
                    $destination = $uploadImageAction($new_image);
                    if (!empty($image) && file_exists($image)) {
                        unlink($image);
                    }
                } else {
                    $destination = $image_path ?? "";
                }
            } catch (Exception $e) {
                $errors["image"] = $e->getMessage();
            }

            if (empty($errors)) {
                $insertedId = $postModel->upsert(
                    id: $id,
                    title: $new_title,
                    imagePath: $destination,
                    content: $new_content,
                    created_at: $created_at,
                    user_id: $user_id,
                );
                if (isset($selectedCategorie) && $selectedCategorie !== 'null') {
                    $postModel->upsertCategories_post(
                        post_id: $x = $insertedId <= 0 ? $id : $insertedId,
                        categories_id: $selectedCategorie
                    );
                }
                if (isset($id)) {
                    Session::addAction(FLASH_MESSAGE_EDITED);
                    header("Location: /post?id=$id");
                } else {
                    Session::addAction(FLASH_MESSAGE_CREATED);
                    header("Location: /post?id=$insertedId");
                }
                exit();
            }
        }
        if (isset($id)) {
            require self::$viewPageDir . "EditPostPage.php";
        } else {
            require self::$viewPageDir . "PostForm.php";
        }
    }
    public function open()
    {
        (int) ($id = $_GET["id"]);
        $data = new Posts()->fetchOne(id: $id);
        if (!isset($data)) {
            http_response_code(404);
            die("Post is not exit");
        }
        (bool) $isCreatedByCurrentUser = Session::isByCurrentUser($data['user_id']);
        $title = $data["title"];
        $content = $data["content"];
        $image_path = $data["image"];
        if (!file_exists($image_path)) {
            $image_path = "/asset/notImage.png";
        }

        require self::$viewPageDir . "PostPage.php";
    }
    public function remove()
    {
        $id = $_GET['id'];
        $data = new Posts()->fetchOne(id: $id);
        Session::addAction(FLASH_MESSAGE_REMOVED);
        (bool) $isCreatedByCurrentUser = Session::isByCurrentUser($data['user_id']);
        if ($isCreatedByCurrentUser) {
            new Posts()->delete($id);
        }
        header("Location: /");
    }
}
