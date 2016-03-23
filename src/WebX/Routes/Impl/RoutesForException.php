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
     * @var AppImpl
     */
    private $app;

    public function __construct(Exception $e, RoutesImpl $app) {
        $this->e  = $e;
        $this->app = $app;
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
        $closure = $this->app->createClosure($action);
        foreach((new ReflectionFunction($closure))->getParameters() as $refParam) {
            if($paramClass = $refParam->getClass()) {
                if(is_a($this->e,$paramClass)) {
                    try {
                        $this->app->invoke($action,$parameters);
                        return new RouterNop();
                    } catch(Exception $e) {
                        $this->e = $e;
                        return $this;
                    }
                }
            }
        }
        return $this;

    }

    public function load($fileName)
    {
       return $this;
    }
}
