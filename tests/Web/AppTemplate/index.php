<?php

use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseTypes\TemplateResponseType;
use WebX\Routes\Api\RoutesBootstrap;
use WebX\Routes\Api\Routes;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

RoutesBootstrap::run([function(Routes $routes){

        $routes->onSegment("viaResponseType",function(Response $response, TemplateResponseType $responseType) {
            $response->data(["value1"=>"a"]);
            $response->type($responseType->id("main"));
        })->onSegment("viaResponse",function(Response $response) {
            $response->data(["value1"=>"b"]);
            $response->typeTemplate()->id("main");
        });



},"default"],["home"=>"/"]);

