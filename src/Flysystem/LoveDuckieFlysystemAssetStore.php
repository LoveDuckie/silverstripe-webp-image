<?php

namespace LoveDuckie\SilverStripe\WebPImage\Flysystem;

use SilverStripe\Assets\Flysystem\FlysystemAssetStore as SS_FlysystemAssetStore;

class LoveDuckieFlysystemAssetStore extends SS_FlysystemAssetStore
{
    private static $webp_default_quality = 80;

    private int $webp_quality;

    public function __construct()
    {
        $this->webp_quality = $this->config()->webp_default_quality;
    }

    public function setFromString($data, $filename, $hash = null, $variant = null, $config = array())
    {
        $fileID = $this->getFileID($filename, $hash);
        if ($this->getPublicFilesystem()->has($fileID)) {
            if ($filename) {
                $extension = substr(strrchr($filename, '.'), 1);
                $tmp_file  = TEMP_PATH . DIRECTORY_SEPARATOR . 'raw_' . uniqid() . '.' . $extension;
                file_put_contents($tmp_file, $data);
                $this->createWebPImage($tmp_file, $fileID, $hash, $variant, $config);
            }
        }
        return parent::setFromString($data, $filename, $hash, $variant, $config);
    }

    public function setFromLocalFile($path, $filename = null, $hash = null, $variant = null, $config = array())
    {
        if ($filename) {
            if (isset($config['visibility']) && $config['visibility'] === self::VISIBILITY_PROTECTED) {
                //todo: generate protected webp image
            } else {
                $this->createWebPImage($path, $filename, $hash, $variant, $config);
            }
        }

        return parent::setFromLocalFile($path, $filename, $hash, $variant, $config);
    }

    public function createWebPImage($path, $filename, $hash, $variant = false)
    {

        if (!function_exists('imagewebp') || !function_exists('imagecreatefromjpeg') || !function_exists('imagecreatefrompng')) {
            return;
        }

        $orgpath = './' . $this->getAsURL($filename, $hash, $variant);
        $webpImageRelativeFilePath = $this->createWebPName($orgpath);
        list($width, $height, $type, $attr) = getimagesize($path);

        switch ($type) {
            case IMAGETYPE_GIF:
                $img = imagecreatefromgif($path);
                imagepalettetotruecolor($img);
                imagesavealpha($img, true); // save alphablending setting (important)
                // imagewebp($img, $webpImageRelativeFilePath, $this->webp_quality);
                break;
            case IMAGETYPE_JPEG:
                $img = imagecreatefromjpeg($path);
                // imagewebp($img, $webpImageRelativeFilePath, $this->webp_quality);
                break;
            case IMAGETYPE_PNG:
                $img = imagecreatefrompng($path);
                imagesavealpha($img, true); // save alphablending setting (important)
                break;
        }

        if ($img) {
            imagewebp($img, $webpImageRelativeFilePath, $this->webp_quality);
            imagedestroy($img);
        }
    }

    public function createWebPName($filename)
    {
        $picname = pathinfo($filename, PATHINFO_FILENAME);
        $directory = pathinfo($filename, PATHINFO_DIRNAME);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        return $directory . '/' . $picname . '.' . $extension . '.webp';
    }
}
