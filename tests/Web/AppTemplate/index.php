<?php

use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseTypes\TemplateResponseType;
use WebX\Routes\Api\RoutesBootstrap;
use WebX\Routes\Api\Routes;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

RoutesBootstrap::run([function(Routes $routes){

        $routes->onSegment("1",function(Response $response, TemplateResponseType $responseType) {
            $response->data(["value1"=>"a"]);
            $response->type($responseType->template("main"));
        });

},"default"],["home"=>"/"]);

