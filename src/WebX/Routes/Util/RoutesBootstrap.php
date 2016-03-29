<?php

namespace WebX\Routes\Util;

use Closure;
use WebX\Routes\Api\Routes;
use WebX\Routes\Impl\AppImpl;
use WebX\Routes\Impl\RoutesImpl;

class RoutesBootstrap {

    private function __construct(){}

    /**
     * @param Closure|array $action
     * @param array $config
     * <code>
     * Required values:
     * 'home' (string) relative path from $_SERVER['DOCUMENT_ROOT'] defaults to ".."
     * </code>
     * @return void
     */
    public final static function run($action, array $config = null) {
        $routes = new RoutesImpl($config);
        $routes->onAlways($action);
        $routes->render();
    }
}

?>