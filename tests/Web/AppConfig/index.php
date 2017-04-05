<?php

use WebX\Routes\Api\Request;
use WebX\Routes\Api\ResponseTypes\JsonResponseType;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesBootstrap;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

RoutesBootstrap::run([function(Routes $routes, Request $request, JsonResponseType $responseType) {

        $routes->setRenderer($responseType);
        $segment = $request->nextSegment();
        if ("admin" === $segment) {
                $nextSegment = $request->nextSegment();
                if($nextSegment==='api') {
                        $routes->setResponseType($responseType);
                }
                $routes->setData("Hej på dej");
        }

},"default"],["home"=>"/"]);

