<?php

namespace WebX\Routes\Impl;
use DateTime;
use WebX\Routes\Api\Map;
use WebX\Routes\Api\Reader;
use WebX\Routes\Api\RoutesException;
use WebX\Routes\Api\WritableMap;

class WritableMapImpl extends MapImpl implements WritableMap {

    public function __construct(array $array = null) {
        parent::__construct($array);
    }

    public function set($data,$path=null) {
        if($path) {
            if (is_string($path)) {
                $path = explode(".", $path);
            }
            $root = &$this->array;
            while ($part = array_shift($path)) {
                $root[$part] = empty($path) ? $data : ((isset($root[$part]) && is_array($root[$part])) ? $root[$part] : []);
                $root = &$root[$part];
            }
        } else {
            if(is_array($data)) {
                $this->array = $data;
            } else {
                throw new RoutesException("Can not set top-level data with a non-array value.");
            }
        }
    }

    public function delete($path) {
        if($path) {
            if (is_string($path)) {
                $path = explode(".", $path);
            }
            $root = &$this->array;
            while ($part = array_shift($path)) {
                if(count($path)===0) {
                    $val = isset($root[$part]) ? $root[$part] : null;
                    unset($root[$part]);
                    return $val;
                } else {
                    if (is_array($root[$part])) {
                        $root = $root[$part];
                    } else {
                        break;
                    }
                }
            }
        } else {
            $this->data = [];
        }
    }

    public function clear() {
        $this->array = [];
    }


}