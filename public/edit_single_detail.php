<?php


require_once "../src/components/head.php";

require_once "../src/components/input.php";
require_once "../src/components/textarea.php";

require_once "../src/filteres/max_character.php";
require_once "../src/filteres/valid_image_file.php";
require_once "../src/filteres/filter_CSRF_token.php";

require_once "../src/save_path_image.php";

require_once "../src/db.php";

generate_CSRF_token();

(int) ($id = $_GET["id"]);
try {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id=:id");
    $stmt->execute(["id" => $id]);
    $data = $stmt->fetch();

    (string) ($title = $data["title"]);
    (string) ($content = $data["content"]);
    (string) ($image = $data["image"]);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        validateCSRFToken();
        
        $title_post = $_POST["title"];
        $content_post = $_POST["content"];
        $image_post = $_FILES["image"];

        filter_max_characters($title_post, 20);
        if ($image_post['size'] > 0) {
            $destination = save_path_image($image_post);
            unlink($image);
        }

        $stmt = $pdo->prepare(
            "UPDATE posts SET title = :title, content = :content, image = :image WHERE id = :id",
        );
        $stmt->execute([
            "id" => $id,
            "title" => $title_post,
            "content" => $content_post,
            "image" => $destination ?? $image,
        ]);
    }
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!doctype html>
<html lang="en">
    <?php head("Edit Post"); ?>
    <body>
        <?php require "../src/components/nav.php"; ?>
        <img src="<?= $image ?>" class="h-60 w-lvw object-cover">
        <main class="flex justify-between px-10 py-5 gap-5">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="flex-1">
                    <?php input_with_border(
                        type: "text",
                        name: "title",
                        value: $title,
                    ); ?>
                </div>
                <?php textarea(name: "content", value: $content); ?>
                <input type="hidden" name="csrf_token" value="<?= $_SESSION["csrf_token"] ?>">
                <div>
                    <?php input_with_border(type: "file", name: "image"); ?>
                    <button type="submit">Save Changes</button>
                </div>
            </form>
        </main>
    </body>
</html>
