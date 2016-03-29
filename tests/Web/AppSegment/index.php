<?php

use WebX\Routes\Api\Responses\ContentResponse;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesBootstrap;

require_once dirname(dirname(dirname(__DIR__))). "/vendor/autoload.php";

RoutesBootstrap::run(function(Routes $routes)  {

    $routes->onSegment("1",function(Routes $routes){

        $routes->onSegment("1",function(ContentResponse $response){
            $response->setContent("1.1");

        })->onSegment("2",function(ContentResponse $response){
            $response->setContent("1.2");
        });

    })->onSegment("2",function(Routes $routes){

        $routes->onSegment("1",function(ContentResponse $response){
            $response->setContent("2.1");

        })->onSegment("2",function(ContentResponse $response){
            $response->setContent("2.2");
        });

    })->onSegment("3",function(ContentResponse $response){
        $response->setContent("3");

    })->onAlways(function(ContentResponse $response){
        $response->setContent("void");
    });


},["home"=>"/"]);

