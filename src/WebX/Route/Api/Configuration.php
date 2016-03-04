<?php

namespace WebX\Route\Api;

interface Configuration {

    /**
     * Returns the configuration for a given path
     * @param $path string
     * @return mixed
     */
    public function get($path);

}

?>