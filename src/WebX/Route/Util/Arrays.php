<?php

namespace WebX\Route\Util;

/**
 * Class Arrays
 */
class Arrays{
    /**
     * Arrays constructor.
     */
    private function __construct() {
    }


    /**
     * Safely reads a value from an array
     * @param array $array
     * @param $key
     * @return mixed|null
     */
    public static function get($path,array $array){
        if(is_string($path)) {
            $path = explode(".",$path);
        }
        if($path){
            while(($key = array_shift($path))){
                $value = isset($array[$key])?$array[$key]:null;
                if(count($path) === 0){
                    return $value;
                } else {
                    $array = $value;
                }
            }
        }
        return $array;
    }
}