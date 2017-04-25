<?php

use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseTypes\TemplateResponseType;
use WebX\Routes\Api\RoutesBootstrap;
use WebX\Routes\Api\Routes;
use WebX\Routes\Extras\Twig\Api\TwigView;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";

RoutesBootstrap::run(function(Routes $routes, TwigView $twigView){

    if($next = $routes->path()->nextSegment()) {
        return $twigView->id("value")->data(["value"=>$next]);
    } else {
        return $twigView->id("empty");
    }
},"Twig",["home"=>"/"]);

