<?php

use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseTypeFactory;
use WebX\Routes\Api\ResponseTypes\RawResponseType;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesBootstrap;

require_once dirname(dirname(dirname(dirname(__DIR__)))) . "/vendor/autoload.php";

RoutesBootstrap::run(function(Routes $routes) {

        $routes->onSegment("redirectSource",function(Response $response){
                $response->typeRedirect()->url("/redirectTarget");
        })->onSegment("redirectTarget",function(Response $response){
                $response->typeRaw();
                $response->data("The target");
        })->onSegment("download",function(Response $response) {
                $response->typeDownload()->fileName("readme.js");
                $response->data("Read this.");
        });
;
});

