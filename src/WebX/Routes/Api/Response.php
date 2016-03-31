<?php
/**
 * User: niclas
 * Date: 2/4/16
 * Time: 5:47 PM
 */

namespace WebX\Routes\Api;


interface Response
{
    public function addHeader($header);
    public function addCookie($name, $value, $ttl=0, $path = "/");
    public function setStatus($httpStatus);


}