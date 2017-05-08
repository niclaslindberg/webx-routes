<?php

use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesBootstrap;
use WebX\Routes\Api\Views\JsonView;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

RoutesBootstrap::run(function(Routes $routes, JsonView $jsonView){

    $next = $routes->path()->next();
        if($next==='normal') {
            $routes->data()->set("a","a.a");
            $routes->data()->set("b","a.b");
            $jsonView->setData("c","a.b.c");

        } else if ($next==='rootArray') {
            $routes->data()->set(["a"=>"1"]);
            $jsonView->setData(["b"=>"2"]);

        } else if ($next==='scalar') {
            $jsonView->setData(1);

        }
        return $jsonView;
},null,null,["home"=>"/"]);

