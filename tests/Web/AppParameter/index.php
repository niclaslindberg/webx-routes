<?php

use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseTypeFactory;
use WebX\Routes\Api\ResponseTypes\RawResponseType;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesBootstrap;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

RoutesBootstrap::run(function(Routes $routes) {

        $routes->onSegment("test1",function(Response $response, RawResponseType $templateType, $param1,$param2,$param3){
                $text = implode("-",[$param1,$param2,$param3]);
                $response->data($text);
                $response->type($templateType);
        })->onSegment("test2",function(Response $response, RawResponseType $templateType, $param1,$param2,$param3="default"){
                $text = implode("-",[$param1,$param2,$param3]);
                $response->data($text);
                $response->type($templateType);
        })->onSegment("test3",function(Response $response, RawResponseType $templateType, $param1,$param2,$param3=null){
                $text = implode("-",[$param1,$param2,$param3]);
                $response->data($text);
                $response->type($templateType);
        });

},["home"=>"/"]);

