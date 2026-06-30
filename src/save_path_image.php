<?php
function save_path_image(array $image): string
{
    $uploadDir = "./images/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $fileExtension = pathinfo($image["name"], PATHINFO_EXTENSION);

    $newFilename = bin2hex(random_bytes(8)) . "." . $fileExtension;
    $destination = $uploadDir . $newFilename;

    move_uploaded_file($image["tmp_name"], $destination);
    return $destination;
}