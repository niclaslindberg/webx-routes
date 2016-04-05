<?php


namespace Test\WebX\Classes\Controllers;

use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseTypes\RawResponseType;

class ControllerA {

    public function test1(Response $response, RawResponseType $responseType) {
        $response->data(1);
        $response->type($responseType);
    }

    public function test2(Response $response, RawResponseType $responseType) {
        $response->data(2);
        $response->type($responseType);
    }

    public function test3(Response $response, RawResponseType $responseType) {
        $response->data(3);
        $response->type($responseType);
    }
}

