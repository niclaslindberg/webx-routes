<?php

use Test\WebX\Classes\IService;
use WebX\Routes\Api\Response;
use WebX\Routes\Api\Responses\ContentResponse;
use WebX\Routes\Api\ResponseTypes\RawResponseType;
use WebX\Routes\Api\RoutesBootstrap;
use WebX\Routes\Api\Views\RawView;

require_once dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";


RoutesBootstrap::run(function(RawView $rawView, IService $service){
        return $rawView->setData($service->returnSame(1));
},"default",["home"=>"/"]);

