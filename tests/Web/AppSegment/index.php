<?php

use WebX\Routes\Api\Response;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesBootstrap;

require_once dirname(dirname(dirname(__DIR__))). "/vendor/autoload.php";

RoutesBootstrap::run(function(Routes $routes, Response $response)  {
    $routes->onSegment("1",function(Routes $routes){

        $routes->onSegment("1",function(Response $response){
            $response->typeRaw();
            $response->data("1.1");

        })->onSegment("2",function(Response $response){
            $response->typeRaw();
            $response->data("1.2");
        });

    })->onSegment("2",function(Routes $routes){

        $routes->onSegment("1",function(Response $response){
            $response->typeRaw();
            $response->data("2.1");

        })->onSegment("2",function(Response $response){
            $response->typeRaw();
            $response->data("2.2");
        });

    })->onSegment("3",function(Response $response){
        $response->typeRaw();
        $response->data("3");
    })->onSegment("4",function($myVal=null,Response $response){
        $response->typeRaw();
        $response->data($myVal);
    })->onAlways(function(Response $response){
        $response->typeRaw();
        $response->data("void");
    });


},["home"=>"/"]);

