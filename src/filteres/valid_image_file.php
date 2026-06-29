<?php
function valid_image_file(array $image)
{
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($image["tmp_name"]);

    $allowedTypes = ["image/jpeg", "image/png", "image/gif", "image/webp"];

    if (!in_array($mimeType, $allowedTypes)) {
        die("Image format is not correct!");
    }
}