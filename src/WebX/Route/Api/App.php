<?php

namespace WebX\Route\Api;

use \Closure;

interface App extends Router {

    public function load($fileName);

    public function render();

    public function hasResponse();
}

?>