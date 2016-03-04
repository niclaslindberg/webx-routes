<?php

namespace WebX\Route\Api;

use WebX\Route\Api\AbstractResponse;

interface ResponseHost {

    public function setContentAvailable(AbstractResponse $response);

    public function addHeader(AbstractResponse $response, $header);

    public function addCookie(AbstractResponse $response, $cookie);

    public function setStatus(AbstractResponse $response, $httpStatus);

}

?>