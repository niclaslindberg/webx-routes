<?php

namespace WebX\Routes\Impl;

use Closure;
use Exception;
use ReflectionFunction;
use WebX\Ioc\Impl\IocImpl;
use WebX\Ioc\Ioc;
use WebX\Ioc\IocNonResolvable;
use WebX\Ioc\IocNonResolvableException;
use WebX\Routes\Api\AbstractResponse;
use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseHost;
use WebX\Routes\Api\ResponseType;
use WebX\Routes\Api\ResponseTypes;
use WebX\Routes\Api\ResponseTypes\JsonResponseType;
use WebX\Routes\Api\ResponseWriter;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesException;
use WebX\Routes\Util\ReaderImpl;

class RoutesImpl implements Routes, ResponseWriter {

    public static $CONFIG_KEY = "__webx";

    /**
     * @var Ioc
     */
    private $ioc;

    /**
     * @var RequestImpl
     */
    private $request;


    /**
     * @var ResponseImpl
     */
    private $response;

    /**
     * @var ConfigurationImpl
     */
    private $configuration;

    /**
     * @var ResourceLoaderImpl
     */
    private $resourceLoader;

    /**
     * @var ControllerFactory
     */
    private $controllerFactory;

    private $homeDir;
    private $publicDir;
    private $configDir;

    /**
     * @var Routes;
     */
    private $nop;

    public function __construct(array $config = null)
    {
        $this->configuration = new ConfigurationImpl(require(__DIR__ . "/bootstrap_config.php"));
        $this->controllerFactory = new ControllerFactory();

        $resourceLoader = new ResourceLoaderImpl();
        $resourceLoader->appendPath($_SERVER['DOCUMENT_ROOT'] . (ArrayUtil::get("home",$config) ?: "/.."));
        $this->resourceLoader = $resourceLoader;

        $ioc = new IocImpl(function(IocNonResolvable $nonResolvable, Ioc $ioc) {
            if($refClass = $nonResolvable->unresolvedClass()) {
                $class = $refClass->getName();
                if(is_subclass_of($class, ResponseType::class, true) || ($class === ResponseType::class)) {
                    $responseTypeConfiguration = $this->configuration->asReader("responseTypes.{$class}");
                    if ($responseTypeClass = $responseTypeConfiguration->asString("class")) {
                        $responseImpl = $this->ioc->instantiate($responseTypeClass);
                        $responseImpl->{self::$CONFIG_KEY} = $responseTypeConfiguration->asReader("config");
                        $ioc->register($responseImpl);
                        return $responseImpl;
                    } else {
                        throw new RoutesException("Could not find responseType implementation for {$class}");
                    }
                }
            }
            if($param = $nonResolvable->unresolvedParameter()) {
                return $this->configuration->asAny("settings.{$param->getName()}");
            }
        });
        $this->ioc = $ioc;
        $this->request = new RequestImpl();
        $this->response = new ResponseImpl($ioc);

        $ioc->register($this);
        $ioc->register($this->request);
        $ioc->register($this->response);
        $ioc->register($this->configuration);
        $ioc->register($this->resourceLoader);
    }

    private function pushConfiguration($config) {
        $reader = $this->configuration->pushArray($config);
        foreach ($reader->asArray("ioc",[]) as $method => $parameterList) {
            foreach($parameterList as $parameters) {
                call_user_func_array([$this->ioc, $method], $parameters);
            }
        }
        $this->controllerFactory->pushClassNamespaces($reader->asArray("namespaces"));
    }

    private function popConfiguration($n=1) {
       $this->configuration->popArray($n);
       $this->controllerFactory->popClassNamespaces($n);
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
        $configFile = "routes/{$configName}.php";
        if($completePath = $this->resourceLoader->absolutePath($configFile)) {
            $routes = $this;
            require $completePath;
            return $this->hasResponse() ? $this->nop : $this;
        } else {
            throw new RoutesException(sprintf("Could not load %s in %s",$configFile, json_encode($this->resourceLoader->rootPaths())));
        }
    }

    public function createClosure($action, array $parameters = [])
    {
        if (is_a($action, Closure::class)) {
            return $action;
        } else if (is_string($action)) {
            $segments = explode("#", $action);
            if (count($segments) !== 2) {
                throw new RoutesException("Controller action must be defined controller#method");
            }
            list($controllerClass, $method) = $segments;
            $controllerClass = $this->controllerFactory->createClassName($controllerClass);
            $controller = $this->ioc->instantiate($controllerClass);
            $refMethod = new \ReflectionMethod($controllerClass, $method);
            return $refMethod->getClosure($controller);
        } else {
            throw new RoutesException("Non invokable $action. Must be closure or a controller method path");
        };
    }

    public function invoke($action, array $parameters = [])
    {
        $configCount = 0;
        try {
            if (is_array($action)) {
                $configs = $action;
                $action = array_shift($configs);
                foreach ($configs as $configId) {
                    $configFile = "config/{$configId}.php";
                    if (false !== ($completePath = $this->resourceLoader->absolutePath($configFile))) {
                        $this->pushConfiguration(require $completePath);
                        $configCount++;
                    } else {
                        throw new RoutesException(sprintf("Could not load %s in %s", $configFile, json_encode($this->resourceLoader->rootPaths())));
                    }
                }
            }
            $closure = $this->createClosure($action, $parameters);
            $arguments = [];
            foreach ((new ReflectionFunction($closure))->getParameters() as $refParam) {
                if ($parameters && ($paramId = $refParam->getName() ?: $refParam->getPosition()) && (NULL !== ($p = isset($parameters[$paramId]) ? $parameters[$paramId] : null))) {
                    $arguments[] = $p;
                } else if ($refClass = $refParam->getClass()) {
                    $arguments[] = $this->ioc->get($refClass->getName());
                } else {
                    $arguments[] = $refParam->getDefaultValue();
                }
            }
            call_user_func_array($closure, $arguments);
        } finally {
            if ($configCount) {
                $this->popConfiguration(count($configCount));
            }
        }
        return $this->hasResponse() ? ($this->nop ?: ($this->nop = new RoutesForNop())) : $this;
    }

    public function render()
    {
        $response = $this->response;
        if($responseType = $this->response->responseType) {
            $responseType->prepare($this->request, $response);
        } else {
            if($response->data!==null) {
                $responseType = $this->ioc->get(JsonResponseType::class);
                $responseType->prepare($this->request,$response);
            }
        }

        foreach ($response->headers as $name => $value) {
            header("{$name}:{$value}");
        }
        foreach ($response->cookies as $name => $data) {
            setcookie($name, $data["value"]);
        }
        if($response->hasResponse) {
            header("HTTP/1.1 " . implode(" ", $response->status ?: [200]));
            if($responseType) {
                $responseType->render($responseType->{self::$CONFIG_KEY}, $this, $response->data);
            }
        } else {
            header("HTTP/1.1 404");
        }
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