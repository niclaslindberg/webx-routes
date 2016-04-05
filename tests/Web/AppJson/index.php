<?php

use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseTypes\JsonResponseType;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesBootstrap;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

RoutesBootstrap::run(function(Routes $routes){

        $routes->onSegment("normal",function(Response $response){
          $response->data("a","a.a");
          $response->data("b","a.b");
          $response->data("c","a.b.c");
        })->onSegment("rootArray",function(Response $response, JsonResponseType $responseType){
          $response->type($responseType); //Setting specificially - default is JsonResponseType
          $response->data(["a"=>"1"]);
          $response->data(["b"=>"2"]);
        })->onSegment("rootScalar",function(Response $response){
          $response->data(1);
        });

},["home"=>"/"]);

