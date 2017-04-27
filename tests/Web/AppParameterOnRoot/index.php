<?php

use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseTypeFactory;
use WebX\Routes\Api\ResponseTypes\RawResponseType;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesBootstrap;
use WebX\Routes\Api\Views\JsonView;
use WebX\Routes\Api\Views\RawView;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

RoutesBootstrap::run(function($param1=null,$param2=null,$param3=null, JsonView $jsonView) {
    return $jsonView->setData([
       "p1" => $param1,
       "p2" => $param2,
       "p3" => $param3
    ]);

},null,["home"=>"/"]);

