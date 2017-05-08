<?php

namespace WebX\Routes\Utils;

use WebX\Routes\Api\Map;
use WebX\Routes\Api\WritableMap;
use WebX\Routes\Impl\MapImpl;
use WebX\Routes\Impl\WritableMapImpl;

class MapUtil {
    private function __construct() {}

    /**
     * @param array|null $array
     * @return Map
     */
    public static function readable(array $array=null) {
        return new MapImpl($array);
    }

    /**
     * @param array|null $array
     * @return WritableMap
     */
    public static function writable(array $array=null) {
        return new WritableMapImpl($array);
    }
}