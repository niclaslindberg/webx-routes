<?php

use WebX\Routes\Api\Responses\ContentResponse;
use Test\WebX\Web\AppException\SpecificException;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesBootstrap;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";



RoutesBootstrap::run(function(Routes $routes){

    $routes->onSegment("s",function(Routes $routes){

        $routes->onSegment("s",function(Routes $routes){
            throw new SpecificException("s.s");

        })->onSegment("e",function(Routes $routes){
            throw new SpecificException("s.e");
        });

    })->onException(function(SpecificException $e, ContentResponse $response){
        $response->setContent("handlerS:{$e->getMessage()}");

    })->onException(function(Exception $e, ContentResponse $response){
        $response->setContent("handlerE:{$e->getMessage()}");
    });

},["home"=>"/"]);

