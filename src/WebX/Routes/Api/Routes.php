<?php

namespace WebX\Routes\Api;

interface Routes extends ExceptionRouter {

    /**
     * @param $pattern
     * @param \Closure|string $action
     * @param array $parameters
     * @return Router
     */

    /**
     * @param $pattern
     * @param $action
     * @param string|null $subject (Default: Request->path)
     * @param array $parameters
     * @return mixed
     */
    public function onMatch($pattern, $action, $subject=null, array $parameters = []);

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

    /**
     * @param string $segment
     * @param \Closure|string $action
     * @param array $parameters
     * @return Router
     */
    public function onSegment($segment, $action, array $parameters = []);


    /**
     * @param $fileName
     * @return void
     */
    public function load($fileName);

}

?>