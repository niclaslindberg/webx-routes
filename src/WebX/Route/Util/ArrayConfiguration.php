<?php

namespace WebX\Route\Util;

use WebX\Route\Api\Application;
use WebX\Route\Api\Configuration;

class ArrayConfiguration implements Configuration {

    private $settings;

    public function __construct(array $settings) {
        if(!is_array($settings)) {
            throw new \Exception("Configuration is not an array");
        }
        $this->settings = $settings;
    }

    public function get($path)
    {
        return Arrays::get($path,$this->settings);
    }
}


?>