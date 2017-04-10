<?php

namespace WebX\Routes\Impl;

interface Path {

    /**
     * @return string|null
     */
    public function nextSegment();

    /**
     * @return string|null
     */
    public function currentSegment();

    /**
     * @return string
     */
    public function full();
}
