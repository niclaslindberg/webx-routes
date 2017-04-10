<?php

namespace WebX\Routes\Api;

interface Configuration  {

    /**
     * @param string $directory resolved by Routes"
     * @return mixed
     */
    public function addClassLoader($directory);

    public function addCtrlNamespace($namespace);

    public function configureSession($id=null, $ttl=3600, $encryptionKey = null);

    /**
     * Adds an absolute path to the beginning of the path list.
     * @param $path
     * @param bool $append if true appended else prepended.
     */
    public function addResourcePath($path,$append = true);

}

