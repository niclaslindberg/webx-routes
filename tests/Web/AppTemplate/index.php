<?php

use WebX\Routes\Api\RoutesBootstrap;
use WebX\Routes\Api\Routes;
use WebX\Routes\Extras\Template\Api\TemplateView;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

RoutesBootstrap::run(function(Routes $routes, TemplateView $templateView){

    $next = $routes->path()->next();

    if($next) {
        return $templateView->id("value")->data(["value"=>$next]);
    } else {
        return $templateView->id("empty");
    }
},["WebXTwig"],["home"=>"/"]);

