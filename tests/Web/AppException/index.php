<?php

use WebX\Routes\Api\Response;
use Test\WebX\Web\AppException\SpecificException;
use WebX\Routes\Api\ResponseTypes\RawResponseType;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesBootstrap;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";



RoutesBootstrap::run(function(Routes $routes, Response $response, RawResponseType $responseType){
    $response->type($responseType);
    try {
        $routes->onSegment("s", function (Routes $routes) {
            $routes->onSegment("s", function (Routes $routes) {
                throw new SpecificException("s.s");

            })->onSegment("e", function (Routes $routes) {
                throw new SpecificException("s.e");
            });

        });
    } catch(SpecificException $e) {
        $response->data("handlerS:{$e->getMessage()}");
    } catch(Exception $e) {
        $response->setContent("handlerE:{$e->getMessage()}");
    }

},["home"=>"/"]);

