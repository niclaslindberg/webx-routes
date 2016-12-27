<?php
/**
 * User: niclas
 * Date: 12/27/16
 * Time: 2:56 PM
 */

namespace WebX\Routes\Api;


interface SessionConfig
{

    /**
     * @param int $ttl number of seconds this session will survive without interaction
     * @param string $encryptionKey user for enrypting the cookie.
     * @param bool $httpOnly if this cookie is to reached only via HTTP (not javascript).
     * @param null $id optional sessionStore to configure (default is the default session store)
     * @return void
     */
    public function configure($ttl, $encryptionKey, $httpOnly = true, $id=null);

}