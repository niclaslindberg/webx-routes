<?php

namespace WebX\Routes\Api;

interface ResponseHeader {

    /**
     * @param $httpStatusCode
     * @return ResponseHeader
     */
    public function setStatus($httpStatusCode);

    /**
     * @param string $key
     * @param mixed $value
     * @return ResponseHeader
     */
    public function addHeader($name,$value);

    /**
     * @param $name
     * @return ResponseHeader
     */
    public function clearHeader($name);

    /**
     * Writes a cookie for the response
     * @param $name
     * @param $value
     * @param int $ttl
     * @param string $path
     * @param bool $httpOnly
     * @return ResponseHeader
     */
    public function addCookie($name, $value, $ttl=0, $path = "/", $httpOnly = true);

    /**
     * Clears the cookie
     * @param $name
     * @return ResponseHeader
     */
    public function clearCookie($name);

}

?>