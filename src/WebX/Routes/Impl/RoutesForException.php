<?php

namespace WebX\Routes\Impl;

use Exception;
use ReflectionFunction;
use WebX\Routes\Api\Routes;

class RoutesForException implements Routes  {

    /**
     * @var Exception
     */
    private $e;

    /**
     * @var RoutesImpl
     */
    private $routes;

    public function __construct(Exception $e, RoutesImpl $routes) {
        $this->e  = $e;
        $this->routes = $routes;
    }

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
        $closure = $this->routes->createClosure($action);
        $parameters = [];
        foreach((new ReflectionFunction($closure))->getParameters() as $refParam) {
            if($paramClass = $refParam->getClass()) {
                if(is_a($this->e,$paramClass->getName())) {
                    $parameters[$refParam->getName()] = $this->e;
                }
            }
        }
        if($parameters) {
            try {
                $this->routes->invoke($closure, $parameters);
                return new RoutesForNop();
            } catch (Exception $e) {
                $this->e = $e;
            }
        } else {
            return $this;
        }

    }

    public function load($fileName)
    {
       return $this;
    }
}
