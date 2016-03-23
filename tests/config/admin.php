<?php

use WebX\Routes\Api\Routes;
use WebX\Routes\Api\Responses\JsonResponse;

/** @var Routes $routes */


$routes->onSegment("courses",function(JsonResponse $response){
    $response->setData(["date"=>time()]);
})->onAlways(function(JsonResponse $jsonResponse){
    $jsonResponse->setData("HEJ");
});