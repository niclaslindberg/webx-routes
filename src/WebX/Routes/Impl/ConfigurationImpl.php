<?php

namespace WebX\Routes\Impl;

use DateTime;
use WebX\Routes\Api\Application;
use WebX\Routes\Api\Configuration;
use WebX\Routes\Api\RoutesException;

class ConfigurationImpl implements Configuration {

    /**
     * @var array
     */
    private $settings;

    public function __construct($rootConfig) {
        $this->settings = [$rootConfig];
    }

    public function asAny($key, $default = null)
    {
        if(NULL !== ($value = $this->getTraverse($key))) {
            return $value;
        }
        return $default;
    }


    public function asInt($key, $default = null)
    {
        if(NULL !== ($value = $this->getTraverse($key))) {
            return intval($value);
        }
        return $default;
    }

    public function asFloat($key, $default = null)
    {
        if(NULL !== ($value = $this->getTraverse($key))) {
            return floatval($value);
        }
        return $default;
    }

    public function asBool($key, $default = null)
    {
        return $this->getTraverse($key) ? true : false;
    }

    public function asDate($key, $default = null)
    {
        if(NULL !== ($value = $this->getTraverse($key))) {
            if(is_string($value)) {
                $value = new DateTime();
                $value->setTimestamp(strtotime($value));
                return $value;
            } else if (is_int($value) || is_long($value)) {
                $value = new DateTime();
                $value->setTimestamp($value);
                return $value;
            }
        }
        return $default;
    }

    public function asArray($key, $default = null)
    {
        $result = [];
        foreach (array_reverse($this->settings) as $settings) {
            if ($settings !== null) {
                if (NULL !== ($value = self::get($key, $settings))) {
                    if(is_array($value)) {
                        $result = array_merge($result,$value);
                    }
                }
            }
        }
        return $result;
    }

    public function asString($key, $default = null)
    {
        if(NULL !== ($value = $this->getTraverse($key))) {
            return strval($value);
        }
        return $default;
    }

    public function asReader($key, $default = null)
    {
        if(NULL!==($value = $this->asArray($key))) {
            return new ConfigurationImpl($value);
        }
        return $default;
    }


    /**
     * @param array|null $settings
     * @throws RoutesException
     */
    public function pushArray($settings) {
        if(is_array($settings) || $settings===null) {
            array_unshift($this->settings, $settings);
            return new ConfigurationImpl($settings);
        } else {
            throw new RoutesException("Can not push a non-array setting");
        }
    }

    public function popArray() {
        array_shift($this->settings);
    }

    private function getTraverse($path)
    {
        foreach ($this->settings as $settings) {
            if ($settings !== null) {
                if (NULL !== ($value = self::get($path, $settings))) {
                    return $value;
                }
            }
        }
        return null;
    }

    private static function get($path,array $array){
        if(is_string($path)) {
            $path = explode(".",$path);
        } else if (!is_array($path)) {
            throw new RoutesException("\$path in reader must be either a '.'-notated string or an array of path segments.");
        }
        if(count($path)===1) {
            return isset($array[$path[0]]) ? $array[$path[0]] : null;
        }
        if($path) {
            while (($key = array_shift($path))) {
                $value = isset($array[$key]) ? $array[$key] : null;
                if (count($path) === 0) {
                    return $value;
                } else {
                    $array = $value;
                }
            }
            return $array;
        }
        return null;
    }
}


?>