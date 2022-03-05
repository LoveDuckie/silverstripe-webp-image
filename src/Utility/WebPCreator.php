<?php

namespace LoveDuckie\SilverStripe\WebPImage\Utility;

use LoveDuckie\SilverStripe\WebPImage\Config\WebPImageConfig;

use Exception;
use League\Flysystem\FileExistsException;

class WebPCreator
{
    public static function createWebPImage($filepath)
    {
        if (!file_exists($filepath)) {
            throw new FileExistsException("Unable to find the file \"$filepath\"");
        }

        $expandedPaths = explode(DIRECTORY_SEPARATOR, $filepath);
        if (!$expandedPaths) {
            throw new Exception("Failed to expand the paths by the directory separator");
        }
        if (count($expandedPaths) < 0) {
            throw new Exception("The path is invalid. Unable to retrieve the filename.");
        }


        $fileName = $expandedPaths[count($expandedPaths) - 1];
        $fileExtension = substr($fileName, strpos($fileName, "."));
        if (!$fileExtension) {
            throw new Exception("Failed to retrieve the file extension");
        }
    }

    private static function createFromImage($filePath, $fileExtension, $webpImageFilePath, $force = false)
    {
        $imageResource = null;
        if (file_exists($webpImageFilePath)) {
            throw new Exception("The webp image already exists");
        }
        list($width, $height, $type, $attr) = getimagesize($filePath);

        switch ($fileExtension) {
            case "gif":
                $imageResource = imagecreatefromgif($filePath);
                break;

            case "jpg":
            case "jpeg":
                $imageResource = imagecreatefromjpeg($filePath);
                break;

            case "png":
                $imageResource = imagecreatefrompng($filePath);
                break;

            default:
                break;
        }
        if ($imageResource) {
            imagewebp($imageResource, $filePath, WebPImageConfig::inst()->getWebPImageQuality());
            imagedestroy($imageResource);
        }
        return $imageResource;
    }
}
