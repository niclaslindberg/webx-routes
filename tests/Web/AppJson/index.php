<?php

use WebX\Routes\Api\Responses\ContentResponse;
use WebX\Routes\Api\Responses\JsonResponse;
use WebX\Routes\Api\RoutesBootstrap;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

RoutesBootstrap::run(function(JsonResponse $response){
        $response->setData("a","a.a");
        $response->setData("b","a.b");
        $response->setData("c","a.b.c");

},["home"=>"/"]);

