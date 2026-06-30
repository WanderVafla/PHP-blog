<?php
function save_path_image(array $image): string
{
    $uploadDir = "./images/";

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($image["tmp_name"]);

    $allowedTypes = ["image/jpeg", "image/png", "image/gif", "image/webp"];

    if (!in_array($mimeType, $allowedTypes)) {
        http_response_code(406);
        throw new Exception("Image format is not correct!");
    }
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $fileExtension = pathinfo($image["name"], PATHINFO_EXTENSION);

    $newFilename = bin2hex(random_bytes(8)) . "." . $fileExtension;
    $destination = $uploadDir . $newFilename;

    move_uploaded_file($image["tmp_name"], $destination);

    if (file_exists($destination))
    {
        return $destination;
    }
    http_response_code(500);
    throw new Exception("Image is not saved!");
    
}