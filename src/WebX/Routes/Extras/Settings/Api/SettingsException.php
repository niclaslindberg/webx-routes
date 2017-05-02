<?php

namespace WebX\Routes\Extras\Settings\Api;

use Exception;

class SettingsException extends Exception {

    /**
     * SettingsReader constructor.
     */
    public function __construct($message=null, Exception $cause=null) {
        parent::__construct($message,0,$cause);
    }
}