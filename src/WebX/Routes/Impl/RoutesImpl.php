<?php

namespace WebX\Routes\Impl;

use Closure;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use WebX\Impl\ReaderImpl;
use WebX\Ioc\Impl\IocImpl;
use WebX\Ioc\Ioc;
use WebX\Ioc\IocNonResolvable;
use WebX\Routes\Api\Reader;
use WebX\Routes\Api\ResponseBody;
use WebX\Routes\Api\ResponseHeader;
use WebX\Routes\Api\ResponseType;
use WebX\Routes\Api\ResponseTypes;
use WebX\Routes\Api\ResponseTypes\JsonResponseType;
use WebX\Routes\Api\ResponseWriter;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesException;
use WebX\Routes\Api\Session;
use WebX\Routes\Api\View;

class RoutesImpl implements Routes, ResponseHeader,ResponseBody {


    /**
     * @var Ioc
     */
    private $ioc;

    /**
     * @var ResourceLoaderImpl
     */
    private $resourceLoader;


    /**
     * @var SessionImpl
     */
    private $session;

    private $httpStatusCode;

    private $headers = [];

    private $cookies = [];

    private $data = null;

    /**
     * @var View
     */
    private $view;

    private $segments;

    private $body;

    private $serverReader = null;

    private $parameterReader = null;

    private $cookieReader = null;

    private $headerReader = null;

    /**
     * @var SessionManagerImpl
     */
    private $sessionManager = null;


    public function __construct(array $config = null)
    {

        $resourceLoader = new ResourceLoaderImpl();
        $resourceLoader->appendPath($_SERVER['DOCUMENT_ROOT'] . (ArrayUtil::get("home",$config) ?: "/.."));
        $this->resourceLoader = $resourceLoader;
        $this->ioc = new IocImpl();
        $this->ioc->register($this);
    }

    public function setStatus($httpStatusCode) {
        $this->httpStatusCode = $httpStatusCode;
    }

    public function addHeader($name, $value) {
        if($value!==null) {
            $this->headers[$name] = $value;
        } else {
            unset($this->headers[$name]);
        }
    }

    public function clearHeader($name) {
           unset($this->headers[$name]);
    }

    public function addCookie($name, $value, $ttl = 0, $path = "/", $httpOnly = true) {
        if($value!==null) {
            $this->cookies[$name] = [
                "value" => $value,
                "ttl" => $ttl,
                "path" => $path,
                "httpOnly" => $httpOnly
            ];
        } else {
            unset($this->cookies[$name]);
        }
    }

    public function clearCookie($name) {
        unset($this->cookies[$name]);
    }

    private function pushConfiguration($configuration) {
        if(is_string($configuration)) {
            $configuration = [$configuration];
        }
        foreach($configuration as $config) {
            if($configPath = $this->resourceLoader->absolutePath("config/{$config}.php")) {
                $content = require($configPath);
                $this->ioc->invoke($content);
            } else {
                throw new RoutesException("Config-path {$configPath} could not be found");
            }
        }
    }

    public function map(Closure $closure, $configuration = null, array $parameters = []) {
        if($configuration) {
            $this->pushConfiguration($configuration);
        }
        return $this->ioc->invoke($closure,["paramters"=>$parameters]);
    }

    public function mapMethod($class, $configuration = null, array $parameters = []) {
        if($configuration) {
            $this->pushConfiguration($configuration);
        }
        try {
            $refClass = new ReflectionClass($class);
            $methodName = $this->path()->nextSegment() ?: "index";
            try {
                $method = $refClass->getMethod($methodName);
                $controller = $this->ioc->instantiate($class);
                $closure = $method->getClosure($controller);
                return $this->ioc->invoke($closure,["parameters"=>$parameters]);
            } catch(ReflectionException $e) {
                return;
            }
        } catch(ReflectionException $e) {
            throw new RoutesException(null,$e);
        }
    }

    public function mapCtrl($configuration = null, array $parameters = []) {
        
    }

    public function forward($routesName) {
        $configFile = "routes/{$routesName}.php";
        if($completePath = $this->resourceLoader->absolutePath($configFile)) {
            $routes = $this;
            require $completePath;
            return $this->view ? true : false;
        } else {
            throw new RoutesException(sprintf("Could not forward to %s in %s",$configFile, json_encode($this->resourceLoader->rootPaths())));
        }
    }

    public function setData($data, $path = null) {
        if(null !== $path) {
            if(is_string($path)) {
                $path = explode(".",$path);
            }
            if(!is_array($this->data)) {
                $this->data = [];
            }
            $root = &$this->data;
            while($part = array_shift($path)) {
                $root[$part] = empty($path) ? $data : ((isset($root[$part]) && is_array($root[$part])) ? $root[$part] : []);
                $root = &$root[$part];
            }
        } else {
            $this->data = $data;
        }
    }

    public function data($path = null) {
        if(null!==$path) {
            if(is_array($this->data)) {
                $reader = new ReaderImpl($this->data);
                return $reader->asAny($path);
            } else {
                return null;
            }
        } else {
            return $this->data;
        }
    }

    public function setView(View $view) {
        $this->httpStatusCode = 200;
        $this->view = $view;
    }

    public function view() {
        return $this->view;
    }

    public function server() {
        if(!$this->serverReader) {
            $this->serverReader = new ReaderImpl($_SERVER);
        }
        return $this->serverReader;
    }

    public function body() {
        if(!$this->body) {
            return $this->body = file_get_contents("php://input");
        }
        return $this->body;
    }

    public function input($inputFormat = "request")
    {
        if($inputFormat===Routes::INPUT_AS_REQUEST) {
            if (!$this->parameterReader) {
                $this->parameterReader = new ReaderImpl($_REQUEST);
            }
            return $this->parameterReader;
        } else if ($inputFormat === Routes::INPUT_AS_JSON) {
            return new ReaderImpl(json_decode($this->body(),true));
        }
    }

    public function cookies() {
        if(!$this->cookieReader) {
            $this->cookieReader = new ReaderImpl($_COOKIE);
        }
        return $this->cookieReader;
    }

    public function headers() {
        if(!$this->headerReader) {
            $this->headerReader = new ReaderImpl(apache_request_headers());
        }
        return $this->headerReader;
    }

    public function path() {
        if(!$this->path) {
            $this->path = new PathImpl();
        }
        return $this->path;
    }

    public function session($id = null) {
        if(!$this->sessionManager) {
            $this->sessionManager = new SessionManagerImpl($this);
        }
        return $this->sessionManager->createSession($id);
    }

    public function resourcePath($relativePath = null) {
        return $this->resourceLoader->absolutePath($relativePath);
    }

    public function initialize($action) {
        $this->invoke($action,[],true);
    }

    public function render() {
        if($this->view) {
            $this->view->renderHead($this, $this->data);
            if($this->sessionManager) {
                $this->sessionManager->writeCookies($this);
            }
            header("HTTP/1.1 " . $this->httpStatusCode);
            foreach ($this->cookies as $name => $data) {
                setcookie($name, $data["value"], $data["ttl"] ? ($data["ttl"] + time()) : 0, $data["path"]);
            }
            foreach ($this->headers as $name => $value) {
                header("{$name}:{$value}");
            }
            $this->view->renderBody($this,$this->data);
        } else {
            header("HTTP/1.1 404");
        }
    }

    public function writeContent($content) {
        echo($content);
    }


    //*******************************




    private function popConfiguration($n=1) {
        $this->configuration->popArray($n);
    }









    public function onMatch($pattern, $action, $subject=null, array $parameters = [])
    {
        if($pattern) {
            $subject = is_string($subject) ? $subject : $this->request->path();
            if($result = preg_match("/" . str_replace("/", "\/", $pattern) . "/", $subject, $matches)) {
                return $this->invoke($action, array_merge($parameters, $matches));
            } else if ($result === false) {
                throw new RoutesException("Invalid RegExp:{$pattern}");
            }
        }
        return $this;
    }

    public function onTrue($expression, $action, array $parameters = [])
    {
        if ($expression) {
            return $this->invoke($action, array_merge($parameters, $parameters));
        }
        return $this;
    }

    public function onAlways($action, array $parameters = [])
    {
        return $this->invoke($action, $parameters);
    }

    public function onSegment($segment, $action, array $parameters = [])
    {
        if($this->request->nextSegment()===$segment) {
            $this->request->moveCurrentSegment(1);
            $routes = $this->invoke($action, $parameters);
            $this->request->moveCurrentSegment(-1);
            return $routes;
        }
        return $this;
    }

    public function load($configName)
    {

    }

    private function createClosure($action)
    {
        if (is_a($action, Closure::class)) {
            return [$action, new ReflectionFunction($action),false];
        } else if (is_string($action)) {
            $usedSegment = false;
            $segments = explode("#", $action);
            if(count($segments)===1) {
                $segments[] = $this->request->nextSegment() ?: "index";
                $usedSegment = true;
            } else if (count($segments) !== 2) {
                throw new RoutesException("Controller action must be defined as controller[#method]");
            }
            list($controllerClass, $method) = $segments;
            $controller = $this->ioc->instantiate($controllerClass);
            try {
                $refMethod = new \ReflectionMethod($controllerClass, $method);
                return [$refMethod->getClosure($controller),$refMethod,$usedSegment];
            } catch(ReflectionException $e) {
                return [null,null,false];
            }
        } else {
            throw new RoutesException("Non invokable $action. Must be closure or a controller method path");
        };
    }

    public function invoke($action, array $parameters = [], $initialize = false)
    {
        $configCount = 0;
        try {
            if (is_array($action)) {
                $configs = $action;
                $action = array_shift($configs);
                foreach ($configs as $configId) {
                    if(is_string($configId)) {
                        $configFile = "config/{$configId}.php";
                        if (false !== ($completePath = $this->resourceLoader->absolutePath($configFile))) {
                            $this->pushConfiguration(require $completePath);
                            $configCount++;
                        } else {
                            throw new RoutesException(sprintf("Could not load %s in %s", $configFile, json_encode($this->resourceLoader->rootPaths())));
                        }
                    } else if ($configId && is_array($configId)) {
                        $this->pushConfiguration($configId);
                        $configCount++;
                    }
                }
            }
            /** @var ReflectionFunctionAbstract $reflectionFunction */
            list($closure, $reflectionFunction,$usedSegment) = $this->createClosure($action);
            if($closure) {
                if($requestParameters = $this->request->remainingSegments($usedSegment ? 1 : 0)) {
                    $parameters = $parameters + $requestParameters;
                }
                $arguments = [];
                $paramCount = 0;
                foreach ($reflectionFunction->getParameters() as $refParam) {
                    if ($parameters && ($paramId = $refParam->getName()) && (NULL !== ($p = isset($parameters[$paramId]) ? $parameters[$paramId] : null))) {
                        $arguments[] = $p;
                    } else if ($refClass = $refParam->getClass()) {
                        $arguments[] = $this->ioc->get($refClass->getName(), $this->configuration->asString("mappings.{$refParam->getName()}"));
                    } else {
                        if(NULL !== ($param = isset($parameters[$paramCount]) ? $parameters[$paramCount] : null)) {
                            $arguments[] = $param;
                        } else if ($refParam->isDefaultValueAvailable()){
                            $arguments[] = $refParam->getDefaultValue();
                        } else if ($refParam->allowsNull()) {
                            $arguments[] = null;
                        } else {
                            throw new RoutesException("Can not apply non-existent default value to action. Parameter name: " . $refParam->getName());
                        }
                        $paramCount++;
                    }
                }
                call_user_func_array($closure, $arguments);
            }
        } finally {
            if ($configCount && !$initialize) { // Skip the first configuration
                $this->popConfiguration(count($configCount));
            }
        }
        return $this->hasResponse() ? ($this->nop ?: ($this->nop = new RoutesForNop())) : $this;
    }





    public function addContent($content)
    {

        echo($content);
    }


    public function hasResponse()
    {
        return $this->response->hasResponse;
    }
}
?>