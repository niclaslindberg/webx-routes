<?php

use WebX\Routes\Api\Request;
use WebX\Routes\Api\Response;
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
    })->onMatch("api/(?P<method>\w+)$",
        "apiCtrl#{method}"
    )->onAlways(function(Response $response) {
        $response->setStatus(404);

    })->onException(function (SoapFault $e) {

    })->onException(function (\Exception $other, Request $request){
        dd($other);
    });

