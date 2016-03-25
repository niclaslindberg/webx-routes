<?php

namespace Test\WebX\Web\AppException;

use Exception;

class SpecificException extends Exception {


    public function __construct($message) {
        parent::__construct($message);
    }
}