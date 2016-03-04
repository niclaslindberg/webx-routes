<?php

namespace WebX\Route\Util;

use WebX\Ioc\Util\Bootstrap;
use WebX\Route\Api\Application;
use WebX\Route\Api\Configuration;
use WebX\Route\Impl\AppImpl;

class RouteBootstrap {

    private function __construct(){}
    /**
     * @return Application
     */
    public static function createApp($appConfigFile) {
        $configuration = new ArrayConfiguration(require($_SERVER["DOCUMENT_ROOT"] . "/" .$appConfigFile));
        Bootstrap::init(function(\ReflectionParameter $param,array $config=null) use ($configuration) {
            return $configuration->get("settings." . $param->getName());
        });
        $ioc = Bootstrap::ioc();
        return new AppImpl($configuration,$ioc);
    }
}

?>