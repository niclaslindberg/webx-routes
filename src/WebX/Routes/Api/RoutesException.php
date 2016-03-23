<?php
/**
 * User: niclas
 * Date: 3/23/16
 * Time: 12:52 PM
 */

namespace WebX\Routes\Api;


use Exception;

class RoutesException extends Exception {

    public function __construct($message=null,Exception $cause = null) {
        parent::__construct($message,0,$cause);
    }
}
