<?php


namespace Test\WebX\Classes\Controllers;

use WebX\Routes\Api\Responses\ContentResponse;

class ControllerA {

    public function test1(ContentResponse $response) {
        $response->setContent(1);
    }

    public function test2(ContentResponse $response) {
        $response->setContent(2);
    }

    public function test3(ContentResponse $response) {
        $response->setContent(3);
    }
}

