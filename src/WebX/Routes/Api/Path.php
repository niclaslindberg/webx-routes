<?php

namespace WebX\Routes\Api;

interface Path {

    /**
     * @return string|null
     */
    public function current();

    /**
     * @return string[]
     */
    public function remainingSegments();

    /**
     * @return string
     */
    public function full();
}
