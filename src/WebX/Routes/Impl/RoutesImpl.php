<?php

namespace WebX\Routes\Impl;

use Closure;
use ReflectionClass;
use ReflectionException;
use WebX\Impl\ReaderImpl;
use WebX\Ioc\Impl\IocImpl;
use WebX\Ioc\Ioc;
use WebX\Routes\Api\ResponseBody;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesException;
use WebX\Routes\Api\View;
use WebX\Routes\Impl\Views\JsonViewImpl;

class RoutesImpl implements Routes, ResponseBody {

    /**
     * @var Ioc
     */
    private $ioc;

    /**
     * @var ConfiguratorImpl
     */
    private $configurator;

    private $httpStatusCode;

    private $headers = [];

    private $cookies = [];

    private $data = null;

    /**
     * @var View
     */
    private $view;

    private $path;

    private $body;

    private $serverReader = null;

    private $parameterReader = null;

    private $cookieReader = null;

    private $headerReader = null;

    private $jsonReader = null;

    /**
     * @var SessionManagerImpl
     */
    private $sessionManager = null;


    public function __construct(array $options = null) {
        $this->ioc = new IocImpl();
        $this->configurator = new ConfiguratorImpl($this->ioc);
        $this->configurator->addResourcePath($_SERVER['DOCUMENT_ROOT'] . (ArrayUtil::get("home", $options) ?: "/.."));
        $this->ioc->register($this);
        $this->ioc->register($this->configurator);
        $this->ioc->register(JsonViewImpl::class);
    }

    public function setStatus($httpStatusCode) {
        $this->httpStatusCode = $httpStatusCode;
    }

    public function addHeader($name, $value) {
        if ($value !== null) {
            $this->headers[$name] = $value;
        } else {
            unset($this->headers[$name]);
        }
    }

    public function clearHeader($name) {
        unset($this->headers[$name]);
    }

    public function addCookie($name, $value, $ttl = 0, $path = "/", $httpOnly = true) {
        if ($value !== null) {
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
        if (is_string($configuration)) {
            $configuration = [$configuration];
        }
        foreach ($configuration as $config) {
            if ($configPath = $this->configurator->absolutePath("config/{$config}.php")) {
                $content = require($configPath);
                $this->ioc->invoke($content);
            } else {
                throw new RoutesException("Config-path {$configPath} could not be found");
            }
        }
    }

    public function run(Closure $closure, $configuration = null, array $parameters = []) {
        if($configuration) {
            $this->pushConfiguration($configuration);
        }
        $this->setView($this->ioc->invoke($closure, ["paramters" => $parameters]));
    }

    public function runMethod($class, $configuration = null, array $parameters = []) {
        if ($configuration) {
            $this->pushConfiguration($configuration);
        }
        try {
            $refClass = new ReflectionClass($class);
            $methodName = $this->path()->nextSegment() ?: "index";
            try {
                $method = $refClass->getMethod($methodName);
                $controller = $this->ioc->instantiate($class);
                $closure = $method->getClosure($controller);
                $this->setView($this->ioc->invoke($closure, ["parameters" => $parameters]));
            } catch (ReflectionException $e) {
                //Missing method
            }
            return null;
        } catch (ReflectionException $e) {
            throw new RoutesException(null, $e);
        }
    }

    public function runCtrl($configuration = null, array $parameters = []) {
        if ($ctrlNamespaces = $this->configurator->ctrlNamespaces()) {
            $ctrlName = $this->path()->nextSegment();
            foreach ($ctrlNamespaces as $ctrlNamespace) {
                $ctrlClassName = "$ctrlNamespace\\$ctrlName";
                if (class_exists($ctrlClassName)) {
                    $this->setView($this->runMethod($ctrlClassName, $configuration, $parameters));
                }
            }
            return null;
        } else {
            throw new RoutesException("Missing mapped controller namespaces for mapCtrl()");
        }
    }

    public function forward($routesName) {
        $configFile = "routes/{$routesName}.php";
        if ($completePath = $this->configurator->absolutePath($configFile)) {
            $routes = $this;
            require $completePath;
            return $this->view ? true : false;
        } else {
            throw new RoutesException(sprintf("Could not forward to %s", $configFile));
        }
    }

    public function setData($data, $path = null) {
        if (null !== $path) {
            if (is_string($path)) {
                $path = explode(".", $path);
            }
            if (!is_array($this->data)) {
                $this->data = [];
            }
            $root = &$this->data;
            while ($part = array_shift($path)) {
                $root[$part] = empty($path) ? $data : ((isset($root[$part]) && is_array($root[$part])) ? $root[$part] : []);
                $root = &$root[$part];
            }
        } else {
            $this->data = $data;
        }
    }

    public function data($path = null) {
        if (null !== $path) {
            if (is_array($this->data)) {
                $reader = new ReaderImpl($this->data);
                return $reader->asAny($path);
            } else {
                return null;
            }
        } else {
            return $this->data;
        }
    }

    public function setView($view) {
        if($view) {
            if($view instanceof View) {
                $this->view = $view;
            } else {
                throw new RoutesException("Result of map* must return an instance of View");
            }
        }
    }

    public function view() {
        return $this->view;
    }

    public function server() {
        if ($this->serverReader) {
            return $this->serverReader;
        }
        return $this->serverReader = new ReaderImpl($_SERVER);
    }

    public function body() {
        if ($this->body) {
            return $this->body;
        }
        return $this->body = file_get_contents("php://input");
    }

    public function input($inputFormat = "request")
    {
        if ($inputFormat === Routes::INPUT_AS_REQUEST) {
            if ($this->parameterReader) {
                return $this->parameterReader;
            }
            return $this->parameterReader = new ReaderImpl($_REQUEST);
        } else if ($inputFormat === Routes::INPUT_AS_JSON) {
            if ($this->jsonReader) {
                return $this->jsonReader;
            }
            return $this->jsonReader = new ReaderImpl(json_decode($this->body(), true));
        } else {
            throw new RoutesException("Bad input format {$inputFormat}");
        }
    }

    public function cookies() {
        if ($this->cookieReader) {
            return $this->cookieReader;
        }
        return $this->cookieReader = new ReaderImpl($_COOKIE);
    }

    public function headers() {
        if ($this->headerReader) {
            return $this->headerReader;
        }
        return $this->headerReader = new ReaderImpl(apache_request_headers());
    }

    public function path() {
        if ($this->path) {
            return $this->path;
        }
        return $this->path = new PathImpl();
    }

    public function session($id = null) {
        if (!$this->sessionManager) {
            $this->sessionManager = new SessionManagerImpl($this);
        }
        return $this->sessionManager->createSession($id);
    }

    public function resourcePath($relativePath = null) {
        return $this->configurator->absolutePath($relativePath);
    }

    public function render() {
        if ($this->view) {
            $this->view->renderHead($this, $this->data);
            if ($this->sessionManager) {
                $this->sessionManager->writeCookies($this);
            }
            header("HTTP/1.1 " . ($this->httpStatusCode ?: 200));
            foreach ($this->cookies as $name => $data) {
                setcookie($name, $data["value"], $data["ttl"] ? ($data["ttl"] + time()) : 0, $data["path"]);
            }
            foreach ($this->headers as $name => $value) {
                header("{$name}:{$value}");
            }
            $this->view->renderBody($this, $this->data);
        } else {
            header("HTTP/1.1 404");
        }
    }

    public function writeContent($content) {
        echo($content);
    }
}