<?php

use WebX\Routes\Api\Request;
use WebX\Routes\Api\Response;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesBootstrap;

require_once  dirname(dirname(dirname(dirname(__DIR__)))) . "/vendor/autoload.php";

RoutesBootstrap::run([function(Routes $routes){
    $routes->onSegment("url",function(Response $response, Request $request) {
        $response->typeRaw();
        $response->data($request->path());
    });

    $routes->onSegment("queryParameter",function(Response $response, Request $request) {
        $response->typeRaw();
        $response->data($request->reader(Request::INPUT_AS_QUERY)->asString("param"));
    });


},"test"]);
