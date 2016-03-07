<?php

namespace WebX\Route\Api;

use WebX\Route\Api\AbstractResponse;

interface ResponseHost {

    public function setContentAvailable(AbstractResponse $response);

    public function registerHeader(AbstractResponse $response, $header);

    public function registerCookie(AbstractResponse $response, $cookie);

    public function registerStatus(AbstractResponse $response, $httpStatus);

}

?>