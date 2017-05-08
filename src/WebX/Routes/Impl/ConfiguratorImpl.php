<?php

namespace WebX\Routes\Impl;

use WebX\Routes\Api\Configurator;

class ConfiguratorImpl implements Configurator {

    private $ctrlNamespaces = [];

    private $absolutePaths = [];

    /**
     * @var RoutesImpl
     */
    private $routes;

    public function __construct(RoutesImpl $routes) {
        $this->routes = $routes;
    }

    public function addCtrlNamespace($namespace) {
        array_unshift($this->ctrlNamespaces,$namespace);
    }

    public function configureSession($id=null, $ttl=3600, $httpOnly = false,  $encryptionKey = null) {
        $this->routes->getSessionManager()->configure($ttl,$encryptionKey,$httpOnly,$id);
    }

    public function absolutePaths() {
        return $this->absolutePaths;
    }

    public function addResourcePath($absolutePath, $append = true) {
        if($append) {
            array_push($this->absolutePaths,$this->processPath($absolutePath));
        } else {
            array_unshift($this->absolutePaths,$this->processPath($absolutePath));
        }
    }

    public function ctrlNamespaces() {
        return $this->ctrlNamespaces;
    }

    public function absolutePath($relPath) {
        if($relPath) {
            $relPath = ltrim($relPath,"/");
            foreach($this->absolutePaths as $absolutePath) {
                $path = $absolutePath . $relPath;
                if(file_exists($path)) {
                    return $path;
                }
            }
        }
        return null;
    }

    private function processPath($path) {
        if($path) {
            return rtrim($path,"/") . "/";
        }
    }

    public function register($classOrInstance, array $config = null) {
        $this->routes->register($classOrInstance,$config);
    }

}