<?php

use WebX\Routes\Api\Request;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesBootstrap;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

RoutesBootstrap::run([function(Routes $routes){





        $routes->addCtrlNamespace("Flowpro\\Controllers");
        $segment = $routes->currentSegment();

        if($segment==='admin') {
            $routes->mapCtrl();
        } else {

        }

        $routes->mapCtrl();

        if(!$routes->view()) {
            //404;
        }



},"default"],["home"=>"/"]);


