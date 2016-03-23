<?php

namespace WebX\Routes\Api;

use Exception;

interface ExceptionRouter {

    /**
     * @param Exception $e
     * @param $action
     * @param $exceptionType
     * @param array $parameters
     * @return ExceptionRouter
     */
    public function onException($action,  array $parameters = []);

}

?>