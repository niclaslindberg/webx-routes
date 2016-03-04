<?php

namespace WebX\Route\Api;

interface Router {

    /**
     * @param $pattern
     * @param \Closure|string $action
     * @param array $parameters
     * @return Router
     */
    public function onMatch($pattern, $subject, $action, array $parameters = []);

    /**
     * @param $expression
     * @param \Closure|string $action
     * @param array $parameters
     * @return Router
     */
    public function onTrue($expression, $action, array $parameters = []);

    /**
     * @param \Closure|string $action
     * @param array $parameters
     * @return Router
     */
    public function onAlways($action, array $parameters = []);


}

?>