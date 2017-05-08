<?php

use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseTypeFactory;
use WebX\Routes\Api\ResponseTypes\RawResponseType;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesBootstrap;
use WebX\Routes\Api\Views\RawView;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

RoutesBootstrap::run(function(Routes $routes) {

       $routes->runAction("test1",function(RawView $rawView,$parameter1=null){
                return $rawView->setData($parameter1);
       });

},null,null,["home"=>"/"]);

