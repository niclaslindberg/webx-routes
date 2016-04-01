<?php

use WebX\Routes\Api\Responses\ContentResponse;
use WebX\Routes\Api\Responses\JsonResponse;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesBootstrap;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

RoutesBootstrap::run(function(Routes $routes){

        $routes->onSegment("normal",function(JsonResponse $response){
          $response->setData("a","a.a");
          $response->setData("b","a.b");
          $response->setData("c","a.b.c");
        })->onSegment("rootArray",function(JsonResponse $response){
          $response->setData(["a"=>"1"]);
          $response->setData(["b"=>"2"]);
        })->onSegment("rootScalar",function(JsonResponse $response){
          $response->setData(1);
        });

},["home"=>"/"]);

