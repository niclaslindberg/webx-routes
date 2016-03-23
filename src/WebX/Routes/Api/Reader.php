<?php

namespace WebX\Routes\Api;


use DateTime;

/**
 * Interface Reader
 * Reads type safe data from a data structure. All $key support '.' notation for reading data from deeper levels.
 * @package WebX\Routes\Api
 */
interface Reader
{

    /**
     * @param $key
     * @param mixed $default
     * @return mixed
     */
    public function asAny($key, $default=null);

    /**
     * @param string $key
     * @param int|null $default
     * @return int|null
     */
    public function asInt($key,$default = null);

    /**
     * @param string $key
     * @param float|null $default
     * @return float|null
     */
    public function asFloat($key,$default = null);

    /**
     * @param string $key
     * @param bool|null $default
     * @return bool|null
     */
    public function asBool($key,$default = null);

    /**
     * @param string $key
     * @param DateTime|null $default
     * @return DateTime|null
     */
    public function asDate($key,$default = null);

    /**
     * @param string $key
     * @param array|null $default
     * @return array|null
     */
    public function asArray($key,$default = null);


    /**
     * @param string $key
     * @param string|null $default
     * @return string|null
     */
    public function asString($key,$default = null);


    /**
     * @param string $key
     * @param Reader|null $default
     * @return Reader|null
     */
    public function asReader($key,$default = null);

}