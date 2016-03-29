<?php

use Test\WebX\Classes\IService;
use WebX\Routes\Api\Responses\ContentResponse;
use WebX\Routes\Api\RoutesBootstrap;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";



RoutesBootstrap::run([function(ContentResponse $response, IService $service){
        $response->setContent($service->returnSame(1));

},"default"],["home"=>"/"]);

