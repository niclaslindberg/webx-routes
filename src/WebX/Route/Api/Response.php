<?php
/**
 * User: niclas
 * Date: 2/4/16
 * Time: 5:47 PM
 */

namespace WebX\Route\Api;


interface Response
{
    public function addHeader($header);
    public function addCookie($cookie);
    public function setStatus($httpStatus);
}