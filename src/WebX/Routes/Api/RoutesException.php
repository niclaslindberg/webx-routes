<?php

namespace WebX\Routes\Api;

use Exception;

class RoutesException extends Exception {

    public function __construct($message=null,Exception $cause = null) {
        parent::__construct($message,0,$cause);
    }
}
