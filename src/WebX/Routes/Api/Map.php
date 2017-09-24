<?php

namespace WebX\Routes\Api;


use DateTime;

/**
 * Interface Map
 * Reads type safe data from a data structure. All $key support '.' notation for reading data from deeper levels.
 * @package WebX\Routes\Api
 */
interface Map {

    /**
     * Checks if a key for the given path exits.
     * @param $path
     * @return bool
     */
    public function hasKey($path);

    /**
     * @return array
     */
    public function raw();

    /**
     * @return array
     */
    public function keys();

    /**
     * @param $path
     * @param mixed $default
     * @return mixed
     */
    public function asAny($path, $default=null);

    /**
     * @param string $path
     * @param int|null $default
     * @return int|null
     */
    public function asInt($path,$default = null);

    /**
     * @param string $path
     * @param float|null $default
     * @return float|null
     */
    public function asFloat($path,$default = null);

    /**
     * @param string $path
     * @param bool|null $default
     * @return bool|null
     */
    public function asBool($path,$default = null);

    /**
     * @param string $path
     * @param DateTime|null $default
     * @return DateTime|null
     */
    public function asDate($path,$default = null);

    /**
     * @param string $path
     * @param array|null $default
     * @return array|null
     */
    public function asArray($path=null, array $default = null);

    /**
     * @param string $path
     * @param string $default
     * @return string|null
     */
    public function asString($path,$default = null);

    /**
     * @param string $path
     * @param array|null $default
     * @return null|Map A Reader if the path contains an array otherwise null
     */
    public function asMap($path,array $default = null);


}