<?php

use Test\WebX\Classes\IService;
use WebX\Routes\Api\Response;
use WebX\Routes\Api\Responses\ContentResponse;
use WebX\Routes\Api\ResponseTypes\RawResponseType;
use WebX\Routes\Api\RoutesBootstrap;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";



RoutesBootstrap::run([function(Response $response, IService $service, RawResponseType $rawResponseType){
        $response->type($rawResponseType);
        $response->data($service->returnSame(1));

},"default"],["home"=>"/"]);

