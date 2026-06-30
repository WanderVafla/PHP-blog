<?php
session_start();

require_once "../src/filteres/filter_CSRF_token.php";

require_once "../src/filteres/max_character.php";
require_once "../src/filteres/valid_image_file.php";
require_once "../src/filteres/filter_CSRF_token.php";

require_once "../src/save_path_image.php";

require_once "../src/components/input.php";
require_once "../src/components/textarea.php";
require_once "../src/components/head.php";
require_once "../src/db.php";

generate_CSRF_token();

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        validateCSRFToken();

        $title = $_POST["title"];
        $image = $_FILES["image"];

        filter_max_characters($title, 20);

        $content = $_POST["content"];
        $created_at = "test";
        $user_id = 1;

        $destination = save_path_image($image);

        $query = 'INSERT INTO posts
        (title, image, content, created_at, user_id) VALUES
        (:title, :image, :content, :created_at, :user_id)';

        $stmt = $pdo->prepare($query);

        $stmt->execute([
            ":title" => $title,
            ":image" => $destination,
            ":content" => $content,
            ":created_at" => $created_at,
            ":user_id" => $user_id,
        ]);
    }
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!doctype html>
<html lang="en">
    <?php head("Create new post"); ?>
    <body>
        <?php require_once "../src/components/nav.php"; ?>
        <header>
        </header>
        <main class="main-default flex justify-between">
            <h1 class="whitespace-pre-line leading-18 items-start justify-start">
                Create
                <mark>New Post</mark>
            </h1>
            <form action="" method="post" enctype="multipart/form-data" class="flex flex-col gap-5 items-end">

                <?php input_with_border(
                    type: "text",
                    name: "title",
                    placeholder: "Title",
                ); ?>
                <?php textarea(name: "content", placeholder: "Description"); ?>
                <?php input_with_border(
                    type: "file",
                    name: "image",
                    placeholder: "Image",
                ); ?>
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION[
                    "csrf_token"
                ]; ?>">

                <button class="justify-end" type="submit">Publish</button>
            </form>
        </main>
    </body>
</html>
