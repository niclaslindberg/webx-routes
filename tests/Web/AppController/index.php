<?php

use WebX\Routes\Api\Request;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesBootstrap;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

RoutesBootstrap::run(function(Routes $routes){
    $routes->runCtrl();

},"default",null,["home"=>"/"]);


