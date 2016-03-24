<?php

namespace WebX\Routes\Impl;

use Closure;
use Exception;
use ReflectionFunction;
use WebX\Ioc\Ioc;
use WebX\Ioc\IocNonResolvableException;
use WebX\Ioc\Util\Bootstrap;
use WebX\Routes\Api\AbstractResponse;
use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseHost;
use WebX\Routes\Api\ResponseWriter;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesException;
use WebX\Routes\Impl\Responses\JsonResponseImpl;
use WebX\Routes\Util\ReaderImpl;

class RoutesImpl implements Routes, ResponseHost, ResponseWriter {

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
     * @var ConfigurationImpl
     */
    private $configuration;

    /**
     * @var ResourceLoaderImpl
     */
    private $resourceLoader;


    private $headersByClass = [];
    private $cookiesByClass = [];
    private $statusByClass = [];

    private $invocationDepth = 0;

    private $homeDir;
    private $publicDir;
    private $configDir;

    /**
     * @var Routes;
     */
    private $nop;

    public function __construct(array $config = null)
    {
        $configuration = new ConfigurationImpl();
        if($config) {
            $configuration->pushSettings($config);
        }
        $configuration->pushArray(require(__DIR__ . "/bootstrap_config.php"));

        $home = $configuration->asString("home",'..');
        $resourceLoader = new ResourceLoaderImpl();
        $resourceLoader->appendPath($_SERVER['DOCUMENT_ROOT'] . "/" . $home);

        $ioc = Bootstrap::init(function(\ReflectionParameter $param,array $config=null, Ioc $ioc) use ($configuration) {
            return $configuration->asAny("settings.{$param->getName()}");
        });

        $this->ioc = $ioc;
        $this->configuration = $configuration;
        $this->request = new RequestImpl();
        $this->resourceLoader = $resourceLoader;

        $ioc->register($this->request);
        $ioc->register($configuration);
        $ioc->register($this);
        $ioc->register($this->resourceLoader);
        $this->nop = new RoutesForNop();



        foreach ($configuration->asArray("ioc",[]) as $config) {
            call_user_func_array([$ioc, array_shift($config)], $config);
        }

    }

    public function onMatch($pattern, $action, $subject=null, array $parameters = [])
    {
        $subject = is_string($subject) ? $subject : $this->request->path();
        $escapedPattern = str_replace("/","\/",$pattern);
        $result = preg_match("/" . $escapedPattern . "/", $subject, $matches);
        if ($result===1) {
            try {
                $this->invoke($action, array_merge($parameters, $matches));
                return $this->hasResponse() ? $this->nop : $this;
            } catch(Exception $e) {
                dd($pattern,$subject,$e);
                return new RoutesForException($e,$this);
            }
        } else if ($result===false) {
            throw new RoutesException("Invalid RegExp:{$pattern}");
        }
        return $this;
    }

    public function onTrue($expression, $action, array $parameters = [])
    {
        if ($expression) {
            try {
                $this->invoke($action, array_merge($parameters, $parameters));
                return $this->hasResponse() ? $this->nop : $this;
            } catch(Exception $e) {
                return new RoutesForException($e,$this);
            }
        }
        return $this;
    }

    public function onAlways($action, array $parameters = [])
    {
        try {
            $this->invoke($action, array_merge($parameters, $parameters));
            return $this->hasResponse() ? $this->nop : $this;
        } catch(Exception $e) {
            return new RoutesForException($e,$this);
        }
    }

    public function onSegment($segment, $action, array $parameters = [])
    {
        if($this->request->nextSegment()===$segment) {
            try {
                $this->request->moveCurrentSegment(1);
                $this->invoke($action, $parameters);
                return $this->hasResponse() ? $this->nop : $this;
            } catch(Exception $e) {
                return new RoutesForException($e,$this);
            }
            finally {
                $this->request->moveCurrentSegment(-1);
            }
        } else {
            return $this;
        }
    }

    public function onException($action, array $parameters = [])
    {
        return $this;
    }


    public function load($configName)
    {
        $configFile = "config/{$configName}.php";
        if($completePath = $this->resourceLoader->absolutePath($configFile)) {
            $routes = $this;
            require $completePath;
            return $this->hasResponse() ? $this->nop : $this;
        } else {
            throw new RoutesException("Could not find {$configFile}");
        }
    }

    public function createClosure($action, array $parameters = [])
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
        try {
            $this->invocationDepth++;
            $closure = $this->createClosure($action, $parameters);
            $arguments = [];
            foreach ((new ReflectionFunction($closure))->getParameters() as $refParam) {
                try {
                    if ($parameters && ($paramId = $refParam->getName() ?: $refParam->getPosition()) && (NULL !== ($p = isset($parameters[$paramId]) ? $parameters[$paramId] : null))) {
                        $arguments[] = $p;
                    } else if ($refClass = $refParam->getClass()) {
                        $arguments[] = $this->ioc->get($refClass->getName());
                    } else {
                        $arguments[] = $refParam->getDefaultValue();
                    }
                } catch(IocNonResolvableException $e) {
                    if(is_subclass_of($e->interfaceName(),Response::class,true) || ($e->interfaceName()===Response::class)) {
                        $responseConfiguration = $this->configuration->asReader("responseImplementations.{$e->interfaceName()}");
                        if ($responseClass = $responseConfiguration->asString("class")) {
                            $responseImpl = $this->ioc->instantiate($responseClass);
                            $responseImpl->{self::$CONFIG_KEY} = $responseConfiguration->asString("configId");
                            $this->ioc->register($responseImpl);
                            $arguments[] = $responseImpl;
                        } else {
                            throw new RoutesException("Could not find response implementation for {$e->interfaceName()}");
                        }
                    } else {
                        throw $e;
                    }
               }
            }
            return call_user_func_array($closure, $arguments);
        } finally {
            $this->invocationDepth--;
            if($this->invocationDepth === 0) {
                $this->render();
            }
        }
    }

    private function render()
    {
        $keys = [ResponseImpl::class];
        if ($this->currentResponse) {
            $keys[] = get_class($this->currentResponse);
            header("Content-Type: " . $this->currentResponse->getContentType() ?: "text/plain");
        } else {
            dd($this->currentResponse);
            header("Content-Type: text/plain");
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
            if ($key!==ResponseImpl::class) {
                $this->currentResponse->generateContent($this->configuration->asReader("responseConfigurations.{$this->currentResponse->{self::$CONFIG_KEY}}"), $this);
            }
        }
    }

    public function addContent($content)
    {
        echo($content);
    }


    public function hasResponse()
    {
        return $this->currentResponse!==null;
    }

    public function setContentAvailable(AbstractResponse $response)
    {
        $this->currentResponse = $response;
        $this->statusByClass[get_class($response)] = 200;
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

}
?>