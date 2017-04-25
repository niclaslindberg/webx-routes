<?php

use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesBootstrap;
use WebX\Routes\Api\Views\RawView;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

RoutesBootstrap::run(function(Routes $routes, RawView $rawView){
        $next = $routes->path()->nextSegment();
        if($next === "default") {
                $op = $routes->path()->nextSegment();
                if($op==="increment") {
                        $session = $routes->session();
                        $val = $session->value("val") ?: 0;
                        $val++;
                        $session->setValue("val",$val);
                        return $rawView->setData($val);
                }
        }
},"default",["home"=>"/"]);

