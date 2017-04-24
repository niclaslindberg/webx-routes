<?php

use WebX\Routes\Api\Request;
use WebX\Routes\Api\ResponseTypes\JsonResponseType;
use WebX\Routes\Api\ResponseTypes\JsonView;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesBootstrap;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

RoutesBootstrap::run(function(JsonView $jsonView){
        return $jsonView->setData("Hello","message");
},"default",["home"=>"/"]);



