<?php

namespace WebX\Routes\Api;

interface Routes extends ExceptionRouter {

    /**
     * @param $pattern
     * @param $action
     * @param string|null $subject (Default: Request->path)
     * @param array $parameters
     * @return Routes
     */
    public function onMatch($pattern, $action, $subject=null, array $parameters = []);

    /**
     * @param $expression
     * @param \Closure|string $action
     * @param array $parameters
     * @return Routes
     */
    public function onTrue($expression, $action, array $parameters = []);

    /**
     * @param \Closure|string $action
     * @param array $parameters
     * @return Routes
     */
    public function onAlways($action, array $parameters = []);

    /**
     * @param string $segment
     * @param \Closure|string $action
     * @param array $parameters
     * @return Routes
     */
    public function onSegment($segment, $action, array $parameters = []);


    /**
     * @param $fileName
     * @return void
     */
    public function load($fileName);

}

?>