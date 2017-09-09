<?php

namespace WebX\Routes\Impl;
use DateTime;
use JsonSerializable;
use WebX\Routes\Api\Map;

class MapImpl implements Map, JsonSerializable {

    protected $array;

    public function __construct(array $array = null) {
        $this->array = $array!==null ? $array : [];
    }

    public function pushArray(array $array) {
        if($array) {
            $this->array = ArrayUtil::mergeRecursive($this->array, $array);
        }
    }

    public function raw() {
        return $this->array;
    }

    public function keys() {
        return array_keys($this->array);
    }

    public function hasKey($path) {
        if(is_string($path)) {
            $path = explode(".", $path);
        } else if ($path===null) {
            return true;
        } else if (!is_array($path) && !is_scalar($path)) {
            throw new \Exception("\$path in Map must be either a '.'-notated string, a scalar value or an array of path segments.");
        }
        if(count($path)===1) {
            if(array_key_exists($path[0],$this->array)) {
                return true;
            }
        } else {
            $value = $this->array;
            while (null!==($key = array_shift($path))) {
                if(array_key_exists($key,$value)) {
                    if(count($path)===0) {
                        return true;
                    } else {
                        $value = $value[$key];
                    }
                }
            }
        }
        return false;
    }


    public function asAny($key, $default = null) {
        if(null !== ($value = $this->get($key))) {
            return $value;
        }
        return $default;
    }


    public function asInt($key, $default = null) {
        if(null !== ($value = $this->get($key))) {
            return is_int($value) ? $value : (is_scalar($value) ? intval($value) : $default);
        }
        return $default;
    }

    public function asFloat($key, $default = null) {
        if(null !== ($value = $this->get($key))) {
            return is_float($value) ? $value : (is_scalar($value) ? floatval($value) : $default);
        }
        return $default;
    }

    public function asBool($key, $default = null) {
        if(null !== ($value = $this->get($key))) {
            return $value ? true : false;
        }
        return $default;
    }

    public function asDate($key, $default = null) {
        if(null !== ($value = $this->get($key))) {
            if($value instanceof DateTime) {
                return $value;
            } else if(is_string($value)) {
                $date = new DateTime();
                $date->setTimestamp(strtotime($value));
                return $date;
            } else if (is_int($value) || is_long($value)) {
                $date = new DateTime();
                $date->setTimestamp($value);
                return $date;
            }
        }
        return $default;
    }

    public function asArray($path=null, $default = null) {
        if (null !== ($value = $this->get($path))) {
            return is_array($value) ? $value : [$value];
        }
        return $default;
    }

    public function asString($key, $default = null) {
        if(null !== ($value = $this->get($key))) {
            return is_string($value) ? trim($value) : (is_scalar($value) ? strval($value) : $default);
        }
        return $default;
    }

    public function asMap($key) {
        if(null!==($value = $this->get($key))) {
            return is_array($value) ? new MapImpl($value) : null;
        }
        return null;
    }

    protected function get($path=null){
        if(is_string($path)) {
            $path = explode(".", $path);
        } else if ($path===null) {
            return $this->array;
        } else if (!is_array($path) && !is_scalar($path)) {
            throw new \Exception("\$path in ArrayReader must be either a '.'-notated string, a scalar value or an array of path segments.");
        }
        if(count($path)===1) {
            if(array_key_exists($path[0],$this->array)) {
                return $this->array[$path[0]];
            }
            return null;
        } else {
            $value = $this->array;
            while (null!==($key = array_shift($path))) {
                if(is_array($value) && array_key_exists($key,$value)){
                    $value = $value[$key];
                    if ((count($path) === 0) || ($value === null)) {
                        return $value;
                    }
                } else {
                    return null;
                }
            }
            return $value;
        }
    }

    function jsonSerialize() {
        return $this->array;
    }


}