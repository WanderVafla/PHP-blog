<?php
namespace Wandervafla\PhpBlog\Actions;

use Exception;
use finfo;

class UploadImageAction
{
    private static $uploadDir = "./images/";
    private static $allowedTypes = ["image/jpeg", "image/png", "image/gif", "image/webp"];
    
    public function __invoke(array $image): string
    {
    
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($image["tmp_name"]);
    
        if (!in_array($mimeType, self::$allowedTypes)) {
            http_response_code(406);
            throw new Exception("Image format is not correct!");
        }
        
        if (!is_dir(self::$uploadDir)) {
            mkdir(self::$uploadDir, 0755, true);
        }
        $fileExtension = pathinfo($image["name"], PATHINFO_EXTENSION);
    
        $newFilename = bin2hex(random_bytes(8)) . "." . $fileExtension;
        $destination = self::$uploadDir . $newFilename;
    
        move_uploaded_file($image["tmp_name"], $destination);
    
        if (file_exists($destination))
        {
            return $destination;
        }
        http_response_code(500);
        throw new Exception("Image is not saved!");
    }
}