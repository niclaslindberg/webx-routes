<?php

namespace WebX\Route\Impl;

use WebX\Ioc\Ioc;
use WebX\Route\Api\AbstractResponse;
use WebX\Route\Api\App;
use \Closure;
use \ReflectionFunction;
use WebX\Route\Api\Response;
use WebX\Route\Api\ResponseHost;
use WebX\Route\Api\Router;
use WebX\Route\Util\ArrayConfiguration;

class AppImpl implements App, ResponseHost {

    /**
     * @var Ioc
     */
    private $ioc;

    /**
     * @var RequestImpl
     */
    private $request;

    /**
     * @var AbstractResponse
     */
    private $currentResponse;

    private $loadedResponses = [];

    /**
     * @var Configuration
     */
    private $configuration;

    private $headersByClass = [];
    private $cookiesByClass = [];
    private $statusByClass = [];

    /**
     * @var Router;
     */
    private $nop;

    public function __construct(ArrayConfiguration $configuration, Ioc $ioc) {
        $this->ioc = $ioc;
        $this->configuration = $configuration;
        $this->request = new RequestImpl();
        $ioc->register($this->request);
        $ioc->register($configuration);
        $ioc->register($this);
        $this->nop = new RouterNop();

        if($iocConfig = $configuration->get("ioc")) {
            foreach($iocConfig as $config) {
                call_user_func_array([$ioc,array_shift($config)],$config);
            }
        }
    }

    public function onMatch($pattern, $subject, $action, array $parameters = []) {
        if(preg_match("/" . $pattern. "/",$subject,$matches)) {
            $this->invoke($action, array_merge($parameters, $matches));
            return $this->nop;
        }
        return $this;
    }

    public function onTrue($expression,  $action, array $parameters = []) {
        if($expression) {
            $this->invoke($action, array_merge($parameters, $parameters));
            return $this->nop;
        }
        return $this;
    }

    public function onAlways($action, array $parameters = []) {
        $this->invoke($action, array_merge($parameters, $parameters));
        return $this->nop;
    }

    public function load($fileName) {
        $app = $this;
        return require $fileName;
    }

    private function createClosure($action,array $parameters = []) {
        if(is_a($action,Closure::class)) {
            return $action;
        } else if( is_string($action)) {
            if($parameters && (strpos($action,"{")!==false)) {
                $replacer = function($matches) use ($parameters) {
                    return isset($parameters[$matches[1]]) ? $parameters[$matches[1]] : "";
                };
                $action = preg_replace_callback("/\{(\w+)\}/i",$replacer,$action);
            }
            $segments  = explode("#",$action,2);
            if(count($segments)===1) {
                $segments[] = "index";
            }
            list($controllerClass,$method) = $segments;
            $refMethod = new \ReflectionMethod($controllerClass, $method);
            $controller = $this->ioc->instantiate($controllerClass);
            return $refMethod->getClosure($controller);
        } else {
            throw new \Exception("Non invokable $action. Must be closure or a controller method path");
        };
    }

    public function invoke($action, array $parameters = []) {
        $closure = $this->createClosure($action, $parameters);
        $arguments = [];
        foreach((new ReflectionFunction($closure))->getParameters() as $refParam) {
            if($refClass = $refParam->getClass()) {
                $className = $refClass->getName();
                if(isset($this->loadedResponses[$className])) {
                    $arguments[] = $this->loadedResponses[$className];
                } else if (strpos($className,"Response")!==false) {
                    if($config = $this->configuration->get("responses.$className")) {
                        if($responseClass = isset($config["class"]) ? $config["class"] : null) {
                            $response = $this->ioc->instantiate($responseClass);
                            $response->_config = $config;
                            $this->loadedResponses[$className] = $response;
                            $arguments[] = $response;
                        } else {
                            throw new \Exception("Class configuration missing for Response $className");
                        }
                    } else {
                        throw new \Exception("Could not find Response of type $className");
                    }
                } else {
                    $arguments[] = $this->ioc->get($className);
                }
            } else {
                $paramId = $refParam->getName() ?: $refParam->getPosition();
                if(NULL!== ($p = isset($parameters[$paramId]) ? $parameters[$paramId] : null)) {
                    $arguments[] = $p;
                } else {
                    $arguments[] = $refParam->getDefaultValue();
                }
            }
        }
        return call_user_func_array($closure,$arguments);
    }

    public function render() {
        foreach($this->headers as $name => $value) {
            header("$name: $value");
        }
        http_response_code($this->status ?: 404);
        if($response = $this->currentResponse) {
            $configuration = new ArrayConfiguration($response->_config);
            $content = $response->content($configuration);
            echo($content);
        } else {
            http_response_code(404);
        }
    }

    public function hasResponse()
    {
        return $this->currentResponse!==null;
    }

    public function setContentAvailable(AbstractResponse $response)
    {
        $this->currentResponse = $response;
    }

    public function addHeader(AbstractResponse $response, $header)
    {
        $this->currentResponse = $response;
        $this->headersByClass[get_class($response)][] = $header;
    }

    public function addCookie(AbstractResponse $response, $cookie)
    {
        $this->currentResponse = $response;
        $this->cookiesByClass[get_class($response)][] = $cookie;
    }

    public function setStatus(AbstractResponse $response, $httpStatus)
    {
        $this->currentResponse = $response;
        $this->statusByClass[get_class($response)] = $httpStatus;
    }


}
?>