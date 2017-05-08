<?php

namespace WebX\Routes\Api;

interface Configurator  {

    /**
     * Adds a controller namespace to prepend to className when loading controllers.
     * @param $namespace
     * @return void
     */
    public function addCtrlNamespace($namespace);

    /**
     * Configures behavior of a session
     * @param null $id
     * @param int $ttl seconds before discarded
     * @param bool $httpOnly if only accessible via http
     * @param null $encryptionKey enctryption key to encrypt the session cookie.
     * @return void
     */
    public function configureSession($id=null, $ttl=3600, $httpOnly = false,  $encryptionKey = null);

    /**
     * Adds an absolute path to the beginning of the path list.
     * @param $path
     * @param bool $append if true appended else prepended.
     * @return void
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

