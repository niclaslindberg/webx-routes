<?php

use WebX\Routes\Api\Request;
use WebX\Routes\Api\ResponseTypes\JsonResponseType;
use WebX\Routes\Api\ResponseTypes\JsonView;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesBootstrap;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

RoutesBootstrap::run(function(Routes $routes, JsonView $jsonView){
        $routes->setData("hello","message");
        $routes->setView($jsonView);
},"default",["home"=>"/"]);



