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

class AppImpl implements App, ResponseHost, Response
{

    public static $CONFIG_KEY = "__webx__configuration";

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

    public function __construct(ArrayConfiguration $configuration, Ioc $ioc)
    {
        $this->ioc = $ioc;
        $this->configuration = $configuration;
        $this->request = new RequestImpl();
        $ioc->register($this->request);
        $ioc->register($configuration);
        $ioc->register($this);
        $this->nop = new RouterNop();

        if ($iocConfig = $configuration->get("ioc")) {
            foreach ($iocConfig as $config) {
                call_user_func_array([$ioc, array_shift($config)], $config);
            }
        }
    }

    public function onMatch($pattern, $subject, $action, array $parameters = [])
    {
        if (preg_match("/" . $pattern . "/", $subject, $matches)) {
            $this->invoke($action, array_merge($parameters, $matches));
            return $this->nop;
        }
        return $this;
    }

    public function onTrue($expression, $action, array $parameters = [])
    {
        if ($expression) {
            $this->invoke($action, array_merge($parameters, $parameters));
            return $this->nop;
        }
        return $this;
    }

    public function onAlways($action, array $parameters = [])
    {
        $this->invoke($action, array_merge($parameters, $parameters));
        return $this->nop;
    }

    public function load($fileName)
    {
        $app = $this;
        return require $fileName;
    }

    private function createClosure($action, array $parameters = [])
    {
        if (is_a($action, Closure::class)) {
            return $action;
        } else if (is_string($action)) {
            if ($parameters && (strpos($action, "{") !== false)) {
                $replacer = function ($matches) use ($parameters) {
                    return isset($parameters[$matches[1]]) ? $parameters[$matches[1]] : "";
                };
                $action = preg_replace_callback("/\{(\w+)\}/i", $replacer, $action);
            }
            $segments = explode("#", $action, 2);
            if (count($segments) === 1) {
                $segments[] = "index";
            }
            list($controllerClass, $method) = $segments;
            $refMethod = new \ReflectionMethod($controllerClass, $method);
            $controller = $this->ioc->instantiate($controllerClass);
            return $refMethod->getClosure($controller);
        } else {
            throw new \Exception("Non invokable $action. Must be closure or a controller method path");
        };
    }

    public function invoke($action, array $parameters = [])
    {
        $closure = $this->createClosure($action, $parameters);
        $arguments = [];
        foreach ((new ReflectionFunction($closure))->getParameters() as $refParam) {
            if ($refClass = $refParam->getClass()) {
                $arguments[] = $this->ioc->get($refClass->getName());
            } else {
                $paramId = $refParam->getName() ?: $refParam->getPosition();
                if (NULL !== ($p = isset($parameters[$paramId]) ? $parameters[$paramId] : null)) {
                    $arguments[] = $p;
                } else {
                    $arguments[] = $refParam->getDefaultValue();
                }
            }
        }
        return call_user_func_array($closure, $arguments);
    }

    public function render()
    {
        $keys = [null];
        if ($this->currentResponse && ($this->currentResponse!==$this)) {
            $keys[] = get_class($this->currentResponse);
        }
        foreach ($keys as $key) {
            if (isset($this->headersByClass[$key])) {
                foreach ($this->headersByClass[$key] as $header) {
                    header($header);
                }
            }
            if (isset($this->cookiesByClass[$key])) {
                foreach ($this->cookiesByClasss[$key] as $cookieName => $value) {
                    setcookie($cookieName, $value["value"]);
                }
            }
            if (isset($this->statusByClass[$key])) {
                http_response_code($this->statusByClass[$key]);
            }
            if ($this->currentResponse) {
                echo($this->currentResponse->generateContent($this->currentResponse->{AppImpl::$CONFIG_KEY}, $this->currentResponse));
            } else {
                http_response_code(404);
            }
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

    public function registerHeader(AbstractResponse $response, $header)
    {
        $this->currentResponse = $response;
        $this->headersByClass[get_class($response)][] = $header;
    }

    public function registerCookie(AbstractResponse $response, $cookie)
    {
        $this->currentResponse = $response;
        $this->cookiesByClass[get_class($response)][] = $cookie;
    }

    public function registerStatus(AbstractResponse $response, $httpStatus)
    {
        $this->currentResponse = $response;
        $this->statusByClass[get_class($response)] = $httpStatus;
    }

    public function addHeader($header)
    {
        $this->registerHeader(null, $header);
    }

    public function addCookie($cookie)
    {
        $this->registerCookie(null,$cookie);
    }

    public function setStatus($httpStatus)
    {
        $this->registerStatus(null,$httpStatus);
    }
}
?>