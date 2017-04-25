<?php

namespace WebX\Routes\Api;

interface Configurator  {

    public function addCtrlNamespace($namespace);

    public function configureSession($id=null, $ttl=3600, $httpOnly = false,  $encryptionKey = null);

    /**
     * Adds an absolute path to the beginning of the path list.
     * @param $path
     * @param bool $append if true appended else prepended.
     */
    public function addResourcePath($path,$append = true);

    /**
     * Ioc registration
     * @param string|object $classOrInstance
     * @param array|null $config
     * @return void
     */
    public function register($classOrInstance, array $config = null);
}

