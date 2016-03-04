<?php

namespace WebX\Route\Impl;

use WebX\Route\Api\Router;

class RouterNop implements Router  {

    public function onMatch($pattern, $subject, $action, array $parameters = [])
    {
        return $this;
    }

    public function onTrue($expression, $action, array $parameters = []) {
        return $this;
    }

    public function onAlways($action, array $parameters = []) {
        return $this;
    }
}
