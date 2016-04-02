<?php

use WebX\Routes\Api\Responses\ContentResponse;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesBootstrap;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

RoutesBootstrap::run(function(Routes $routes) {

        $routes->onSegment("default",[function(ContentResponse $response){
                $response->setContent("1");

        },"default"]);
},["home"=>"/"]);

