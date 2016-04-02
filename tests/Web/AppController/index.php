<?php

use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesBootstrap;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

RoutesBootstrap::run([function(Routes $routes){

        $routes->onSegment("fullClassName","Test\WebX\Classes\Controllers\ControllerA#test1");
        $routes->onSegment("controller","ControllerA#test2");
        $routes->onSegment("lastNamespaceAndcontroller","Controllers\\ControllerA#test3");
},"default"],["home"=>"/"]);


