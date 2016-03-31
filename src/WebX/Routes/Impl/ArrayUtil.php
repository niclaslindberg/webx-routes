<?php

namespace WebX\Routes\Impl;

class ArrayUtil {

    public static function mergeRecursive(array $array1, $array2) {
        if($array2 && is_array($array2)) {
            foreach($array2 as $key => $val2) {
                if (is_array($val2) && (null!==($val1 = isset($array1[$key]) ? $array1[$key] : null)) && is_array($val1)) {
                    $array1[$key] = self::mergeRecursive($val1,$val2);
                } else {
                    $array1[$key] = $val2;
                }
            }
        }
        return $array1;
    }
}