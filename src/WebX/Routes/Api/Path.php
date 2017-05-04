<?php

namespace WebX\Routes\Api;

interface Path {

    /**
     * The current path segment
     * @return string|null
     */
    public function current();

    /**
     * The upcoming path segment
     * @return string|null
     */
    public function next();

    /**
     * Remaning path segments
     * @return string[]
     */
    public function remaining();

    /**
     * The full path
     * @return string
     */
    public function full();
}
