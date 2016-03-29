<?php

namespace WebX\Routes\Impl;

use Closure;
use Exception;
use ReflectionFunction;
use WebX\Ioc\Impl\IocImpl;
use WebX\Ioc\Ioc;
use WebX\Ioc\IocNonResolvableException;
use WebX\Routes\Api\AbstractResponse;
use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseHost;
use WebX\Routes\Api\ResponseWriter;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesException;
use WebX\Routes\Util\ReaderImpl;

class RoutesImpl implements Routes, ResponseHost, ResponseWriter {

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

    private $homeDir;
    private $publicDir;
    private $configDir;

    private $e;

    /**
     * @var Routes;
     */
    private $nop;

    public function __construct(array $config = null)
    {
        $this->configuration = new ConfigurationImpl(require(__DIR__ . "/bootstrap_config.php"));

        if($config) {
            $this->pushConfiguration($config);
        }

        $home = $this->configuration->asString("home",'/..');
        $resourceLoader = new ResourceLoaderImpl();
        $resourceLoader->appendPath($_SERVER['DOCUMENT_ROOT'] . $home);
        $this->resourceLoader = $resourceLoader;

        $ioc = new IocImpl(function(\ReflectionParameter $param,array $config=null, Ioc $ioc) {
            return $this->configuration->asAny("settings.{$param->getName()}");
        });
        $this->ioc = $ioc;
        $this->request = new RequestImpl();

        $ioc->register($this);
        $ioc->register($this->request);
        $ioc->register($this->configuration);
        $ioc->register($this->resourceLoader);
        $this->nop = new RoutesForNop();


    }

    private function pushConfiguration($config) {
        $reader = $this->configuration->pushArray($config);
        foreach ($reader->asArray("ioc",[]) as $config) {
            call_user_func_array([$this->ioc, array_shift($config)], $config);
        }
    }

    private function popConfiguration($n=1) {
        for($i=0;$i<$n;$i++) {
            $this->configuration->popArray();
        }
    }

    public function onMatch($pattern, $action, $subject=null, array $parameters = [])
    {
        if($pattern && !$this->e) {
            $subject = is_string($subject) ? $subject : $this->request->path();
            $result = preg_match("/" . str_replace("/", "\/", $pattern) . "/", $subject, $matches);
            if ($result === 1) {
                $this->invoke($action, array_merge($parameters, $matches));
                return $this->hasResponse() ? $this->nop : $this;
             } else if ($result === false) {
                throw new RoutesException("Invalid RegExp:{$pattern}");
            }
        }
        return $this;
    }

    public function onTrue($expression, $action, array $parameters = [])
    {
        if ($expression && !$this->e) {
            $this->invoke($action, array_merge($parameters, $parameters));
            return $this->hasResponse() ? $this->nop : $this;
        }
        return $this;
    }

    public function onAlways($action, array $parameters = [])
    {
        if(!$this->e) {
            $this->invoke($action, $parameters);
            return $this->hasResponse() ? $this->nop : $this;
        }
        return $this;
    }

    public function onSegment($segment, $action, array $parameters = [])
    {
        if($this->request->nextSegment()===$segment && !$this->e) {
            $this->request->moveCurrentSegment(1);
            $this->invoke($action, $parameters);
            $this->request->moveCurrentSegment(-1);
            return $this->hasResponse() ? $this->nop : $this;
       }
       return $this;
    }

    public function onException($action, array $parameters = [])
    {
        if($this->e) {
            $closure = $this->createClosure($action);
            $parameters = [];
            foreach ((new ReflectionFunction($closure))->getParameters() as $refParam) {
                if ($paramClass = $refParam->getClass()) {
                    if (is_a($this->e, $paramClass->getName())) {
                        $parameters[$refParam->getName()] = $this->e;
                    }
                }
            }
            if ($parameters) {
                $this->e = null;
                $this->invoke($closure, $parameters);
                return $this->hasResponse() ? $this->nop : $this;
            }
        }
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
            $segments = explode("#", $action, 2);
            if (count($segments) !== 2) {
                throw new RoutesException("Controller action must be defined controller#method");
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
        $configCount = 0;
        try {
            if (is_array($action)) {
                $configs = $action;
                $action = array_shift($configs);
                foreach ($configs as $configId) {
                    if (false !== ($configFile = $this->resourceLoader->absolutePath("config/{$configId}.php"))) {
                        $this->pushConfiguration(require $configFile);
                        $configCount++;
                    } else {
                        throw new RoutesException("Can not load config file $configId");
                    }
                }
            }
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
                } catch (IocNonResolvableException $e) {
                    if (is_subclass_of($e->interfaceName(), Response::class, true) || ($e->interfaceName() === Response::class)) {
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
            call_user_func_array($closure, $arguments);
        } catch(Exception $e) {
            $this->e = $e;
        }
        if($configCount) {
            $this->popConfiguration(count($configCount));
        }
    }

    public function render()
    {
        $keys = [ResponseImpl::class];
        if($this->e) {
            header("Content-Type: text/html; charset=utf-8");
            echo("<html><body>");
            echo(sprintf("<h1>Uncaught exception:%s [%s]</h1>" , $this->e->getMessage() , get_class($this->e)));
            if(function_exists("dd")) {
                dd($this->e);
            } else {
              var_dump($this->e);
            }
            return;
        }
        if ($this->currentResponse) {
            $keys[] = get_class($this->currentResponse);
            header("Content-Type: " . $this->currentResponse->getContentType() ?: "text/plain");
        } else {
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