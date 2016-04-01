<?php


namespace Test\WebX\Classes\Controllers;

use WebX\Routes\Api\Responses\ContentResponse;

class ControllerA {

    public function test1(ContentResponse $response) {
        $response->setContent(1);
    }

}

