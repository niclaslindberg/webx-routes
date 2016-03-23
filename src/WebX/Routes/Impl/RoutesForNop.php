<?php

namespace WebX\Routes\Impl;

use WebX\Routes\Api\Router;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesException;

class RoutesForNop implements Routes  {

    public function onMatch($pattern, $action, $subject=null, array $parameters = [])
    {
        return $this;
    }

    public function onTrue($expression, $action, array $parameters = []) {
        return $this;
    }

    public function onAlways($action, array $parameters = []) {
        return $this;
    }

    public function onSegment($segment, $action, array $parameters = [])
    {
        return $this;
    }


    public function onException($action, array $parameters = [])
    {
        return $this;
    }

    public function load($fileName)
    {
        throw new RoutesException("Exception router can not load resources");
    }


}
