<?php

use Test\WebX\Classes\IService;
use WebX\Routes\Api\Response;
use WebX\Routes\Api\Responses\ContentResponse;
use WebX\Routes\Api\ResponseTypes\RawResponseType;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesBootstrap;
use WebX\Routes\Api\Session;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";



RoutesBootstrap::run([function(Routes $routes){
        $routes->onSegment("default",function(Routes $routes){
                $routes->onSegment("increment",function(Session $session,Response $response){
                        $val = $session->value("val") ?: 0;
                        $val++;
                        $session->setValue("val",$val);
                        $response->typeRaw($val);
                })->onSegment("kill",function(Session $session,Response $response){
                        $session->kill();
                        $response->typeRaw("Killed");
                });
        });
},"default"],["home"=>"/"]);

