<?php
/**
 * User: niclas
 * Date: 2/4/16
 * Time: 5:47 PM
 */

namespace WebX\Routes\Api;


interface Response
{
    public function header($name, $value);

    public function cookie($name, $value, $ttl=0, $path = "/");

    public function status($httpStatus, $message = null);

    /**
     * @param mixed $value
     * @param null $path '.' notated path of where in the data structure the value is stored.
     * @return void
     */
    public function data($value, $path = null);

    public function type(ResponseType $responseType);

}