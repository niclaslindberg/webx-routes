<?php

namespace WebX\Routes\Impl;
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

    public function delete($path=null) {
        if($path) {
            if (is_string($path)) {
                $path = explode(".", $path);
            }
            $root = &$this->array;
            while ($part = array_shift($path)) {
                if(count($path)===0) {
                    if (array_key_exists($part, $root)) {
                        $val = $root[$part];
                        unset($root[$part]);
                        return $val;
                    } else {
                        return null;
                    }
                } else {
                    if (is_array($root[$part])) {
                        $root = &$root[$part];
                    } else {
                        return null;
                    }
                }
            }
        } else {
            $this->array = [];
        }
    }

    public function asWritableMap($path=null) {
        if(null!==($value = $this->get($path))) {
            return is_array($value) ? new WritableMapImpl($value) : null;
        }
        return null;
    }

    public function clear() {
        $this->array = [];
    }


}