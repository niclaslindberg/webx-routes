<?php

namespace WebX\Routes\Api;

use WebX\Routes\Api\AbstractResponse;

interface ResponseHost {

    public function setContentAvailable(AbstractResponse $response);

    public function registerHeader(AbstractResponse $response, $header);

    public function registerCookie(AbstractResponse $response, $name, $value, $ttl=0, $path = "/");

    public function registerStatus(AbstractResponse $response, $httpStatus);

}

?>