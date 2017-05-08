<?php

namespace WebX\Routes\Utils;

use WebX\Routes\Impl\MapImpl;
use WebX\Routes\Impl\WritableMapImpl;

class MapUtil {
    private function __construct() {}

    public static function readable(array $array=null) {
        return new MapImpl($array);
    }

    public static function writable(array $array=null) {
        return new WritableMapImpl($array);
    }
}