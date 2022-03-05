<?php

namespace LoveDuckie\SilverStripe\WebPImage\Config;

use SilverStripe\Core\Config\Config;

class WebPImageConfig
{
    private static $instance = null;

    private $configInstance;

    public function __construct()
    {
    }

    public static function inst()
    {
        return self::$instance ? self::$instance : self::$instance = new static();
    }

    public function getConfig()
    {
        if (!$this->configInstance) {
            $this->configInstance = Config::inst()->get(self::class);
        }
    }
}
