<?php

use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseTypeFactory;
use WebX\Routes\Api\ResponseTypes\RawResponseType;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesBootstrap;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

RoutesBootstrap::run(function(Routes $routes) {

        $routes->onSegment("default",[function(Response $response, RawResponseType $templateType){
                $response->data("1");
                $response->type($templateType);

        },"default"]);
},["home"=>"/"]);

