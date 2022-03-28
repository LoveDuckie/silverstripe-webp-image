<?php

namespace LoveDuckie\SilverStripe\WebPImage\Tasks;
use LoveDuckie\SilverStripe\WebPImage\Tasks\WebPCreator;

use SilverStripe\Assets\Image;
use SilverStripe\Dev\BuildTask;
use SilverStripe\Dev\Debug;

class CreateWebPImagesTask extends BuildTask
{
    protected $title = "Create WebP Images";

    protected $description = "Automatically generate WebP images for all stored image assets.";

    private static $segment = "create-webp-images";

    public function __construct()
    {
        parent::__construct();
    }

    public function run($request)
    {
        Debug::show("Assets Path: " . ASSETS_PATH);
        $images = Image::get();
        foreach ($images as $image) {
            if (!$image->exists()) {
                continue;
            }
            $imageRelativeUrl = $image->File->URL;
            $imageFilename = $image->File->Filename;
            Debug::show("Updating: \"$imageFilename\"");
        
            Debug::show("Checking: \"$image->Title\"");
        
            // DIRECTORY_SEPARATOR
        }
    }
}
