<?php

namespace WebX\Routes\Util;

use WebX\Routes\Api\Routes;
use WebX\Routes\Impl\AppImpl;
use WebX\Routes\Impl\RoutesImpl;

class RoutesBootstrap {

    private function __construct(){}

    /**
     * @param array $config
     * <code>
     * Required values:
     * 'home' (string) relative path from $_SERVER['DOCUMENT_ROOT'] defaults to ".."
     * </code>
     * @return Routes
     */
    public final static function create(array $config = null) {
        return new RoutesImpl($config);
    }
}

?>