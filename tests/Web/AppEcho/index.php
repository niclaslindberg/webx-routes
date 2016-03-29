<?php

use WebX\Routes\Api\Responses\ContentResponse;
use WebX\Routes\Util\RoutesBootstrap;

require_once  dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";



RoutesBootstrap::run(function(Routes $routes) {
    $routes->onAlways(function(ContentResponse $response){
        $response->setContent("hello");
    });
},["home"=>"/"]);
