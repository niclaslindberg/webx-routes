<?php

use WebX\Routes\Api\Responses\ContentResponse;
use WebX\Routes\Util\RoutesBootstrap;

require_once dirname(dirname(__DIR__)) . "/vendor/autoload.php";



$routes = RoutesBootstrap::create(["home"=>"/"]);
$routes->onAlways(function(ContentResponse $response){
    $response->setContent("hello");
});