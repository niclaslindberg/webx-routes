<?php

use WebX\Routes\Api\Responses\ContentResponse;
use WebX\Routes\Util\RoutesBootstrap;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";



RoutesBootstrap::run([function(ContentResponse $response){
        $response->setContent("1");

},"default"],["home"=>"/"]);

