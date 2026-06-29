<?php
session_start();

// generate new csrf_token if we not have
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

require_once "../src/components/input.php";
require_once "../src/components/textarea.php";
require_once "../src/components/head.php";
require_once "../src/db.php";

// check CSRF-token
function validateCSRFToken()
{
    if (
        !isset($_POST["csrf_token"]) ||
        !isset($_SESSION["csrf_token"]) ||
        !hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"])
    ) {
        die("CSRF token validation failed");
    }
    return true;
}

function validateData(string $title, array $image)
{
    if (
        !filter_var($title, FILTER_CALLBACK, [
            "options" => function ($value) {
                if (strlen($value) > 60) {
                    return false;
                } else {
                    return true;
                }
            },
        ])
    ) {
        die("Oversize title!");
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($image["tmp_name"]);

    $allowedTypes = ["image/jpeg", "image/png", "image/gif", "image/webp"];

    if (!in_array($mimeType, $allowedTypes)) {
        die('Image format is not correct!');
    }
}

function saveImage(array $image): string {
    $uploadDir = __DIR__ . "/images/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $fileExtension = pathinfo($image["name"], PATHINFO_EXTENSION);

    $newFilename = bin2hex(random_bytes(8)) . "." . $fileExtension;
    $destination = $uploadDir . $newFilename;

    move_uploaded_file($image["tmp_name"], $destination);
    return $destination;
}

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        validateCSRFToken();

        $title = $_POST["title"];
        $image = $_FILES["image"];
        
        validateData($title, $image);

        $content = $_POST["content"];
        $created_at = "test";
        $user_id = 1;

        $destination = saveImage($image);

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
    print_r($e);
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
