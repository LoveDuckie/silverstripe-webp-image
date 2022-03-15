<?php

namespace LoveDuckie\SilverStripe\WebPImage\Extensions;

use SilverStripe\ORM\DataExtension;

class DBFileExtension extends DataExtension
{
    public function onBeforeWrite()
    {
        if ($this->owner->getMimeType()) {

        }
    }

    private static $supportedImageMimeTypes = [
        'image/jpg',
        'image/jpeg',
        'image/pjpeg',
        'image/gif',
        'image/png',
        'image/x-png',
    ];

    public function isSupportedMimeType($mimeType) {
        
    }
}
