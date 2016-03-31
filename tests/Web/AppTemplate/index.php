<?php

use WebX\Routes\Api\Responses\ContentResponse;
use WebX\Routes\Api\Responses\JsonResponse;
use WebX\Routes\Api\Responses\TemplateResponse;
use WebX\Routes\Api\RoutesBootstrap;
use WebX\Routes\Api\Routes;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

RoutesBootstrap::run([function(Routes $routes){

        $routes->onSegment("1",function(TemplateResponse $response) {
            $response->setData(["value1"=>"a"]);
            $response->setTemplate("main");
        });

},"default"],["home"=>"/"]);

