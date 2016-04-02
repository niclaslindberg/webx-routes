<?php
/**
 * User: niclas
 * Date: 4/2/16
 * Time: 10:34 PM
 */

namespace WebX\Routes\Api;


use Exception;

class ControllerException extends RoutesException
{
    public function __construct($message=null, Exception $cause = null) {
        parent::__construct($message,$cause);
    }

}