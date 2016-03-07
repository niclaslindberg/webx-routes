<?php

namespace WebX\Route\Util;

use WebX\Ioc\Util\Bootstrap;
use WebX\Route\Api\Application;
use WebX\Route\Api\Configuration;
use WebX\Route\Api\Response;
use WebX\Route\Impl\AppImpl;

class RoutesBootstrap {

    private function __construct(){}
    /**
     * @return Application
     */
    public static function createApp($appConfigFile) {
        $configuration = new ArrayConfiguration(require($_SERVER["DOCUMENT_ROOT"] . "/" .$appConfigFile));
        $ioc = Bootstrap::ioc();
        Bootstrap::init(function(\ReflectionParameter $param,array $config=null) use ($configuration,$ioc) {
            if($paramClass = $param->getClass()) {
                if(in_array(Response::class,$paramClass->getInterfaceNames())) {
                    $responseConfiguration = new ArrayConfiguration($configuration->get("responses.{$paramClass}"));
                    $response = $ioc->instantiate($responseConfiguration->get("class"));
                    $response->{AppImpl::$CONFIG_KEY} = new ArrayConfiguration($responseConfiguration);
                    return $response;
                }
            }
            return $configuration->get("settings." . $param->getName());
        });
        return new AppImpl($configuration,$ioc);
    }
}

?>