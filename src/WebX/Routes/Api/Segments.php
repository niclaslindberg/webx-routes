<?php

namespace WebX\Routes\Impl;

interface Segments {

    /**
     * @return string|null
     */
    public function nextSegment();

    /**
     * @return string|null
     */
    public function currentSegment();

}
