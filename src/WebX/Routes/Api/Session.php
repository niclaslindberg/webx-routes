<?php
/**
 * User: niclas
 * Date: 12/27/16
 * Time: 2:56 PM
 */

namespace WebX\Routes\Api;


interface Session extends SessionStore
{

    /**
     * @param $id
     * @return SessionStore
     */
    public function getStore($id);
    
}