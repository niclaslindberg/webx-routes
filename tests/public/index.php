<?php

use WebX\Routes\Api\Response;
use WebX\Routes\Api\Responses\ContentResponse;
use WebX\Routes\Api\Responses\TemplateResponse;
use WebX\Routes\Api\Router;
use WebX\Routes\Api\Routes;
use WebX\Routes\Util\RoutesBootstrap;

    require_once("../../vendor/autoload.php");

    $routes = RoutesBootstrap::create();

    $routes->onSegment("admin", function (Routes $routes) {
        $routes->load("admin");

    })->onSegment("template", function (TemplateResponse $response) {
        $response->setTemplate("test");
        $response->setData(["user"=>"Niclas"]);

    })->onMatch("api/(?P<method>\w+)$", function(ContentResponse $response) {
        $response->setContent("Hello there");

    })->onAlways(function(Response $response) {
        throw new \Exception("This is the error");
        $response->setStatus(404);

    })->onException(function (SoapFault $e) {

    })->onException(function (\Exception $other, TemplateResponse $response){
        dd($other);

        $response->setTemplate("error");
        $response->setData($other->getMessage(),"message");
        $response->setStatus(500);
    });

