<?php

use WebX\Routes\Api\Request;
use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseTypes\RawResponseType;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesBootstrap;

require_once  dirname(dirname(dirname(dirname(__DIR__)))) . "/vendor/autoload.php";

RoutesBootstrap::run([function(RawResponseType $rawResponseType, Response $response, Request $request) {
    $response->type($rawResponseType);
    $response->data($request->path());
},"test"]);
