<?php

namespace WebX\Routes\Impl;

class ArrayUtil {

    public static function mergeRecursive($array1, $array2) {
        if(is_array($array1) && is_array($array2)) {
            foreach($array2 as $key2 => $val2) {
                if($val1 = isset($array1[$key2]) ? $array1[$key2] : null) {
                    $array1[$key2] = self::mergeRecursive($val1,$val2);
                } else {
                    $array1[$key2] = $val2;
                }
            }
            return $array1;
        } else {
            return $array2!==null ? $array2 : $array1;
        }
    }

    public static function get($field, $array) {
        return isset($array[$field]) ? $array[$field] : null;
    }
}