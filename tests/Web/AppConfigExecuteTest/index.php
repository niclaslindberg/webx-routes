<?php

use Test\WebX\Classes\IService;
use WebX\Routes\Api\Response;
use WebX\Routes\Api\Responses\ContentResponse;
use WebX\Routes\Api\ResponseTypes\RawResponseType;
use WebX\Routes\Api\RoutesBootstrap;
use WebX\Routes\Api\Session;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";



RoutesBootstrap::run([function(Response $response, IService $service, RawResponseType $rawResponseType, Session $session){
        $response->type($rawResponseType);
        $response->data($service->getValue());
        


},"default"],["home"=>"/"]);

