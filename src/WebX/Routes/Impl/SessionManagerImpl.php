<?php
/**
 * User: niclas
 * Date: 12/27/16
 * Time: 3:25 PM
 */

namespace WebX\Routes\Impl;


use WebX\Routes\Api\Request;
use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseHeader;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesException;
use WebX\Routes\Api\Session;
use WebX\Routes\Api\SessionConfig;

class SessionManagerImpl   {

    /**
     * @var array
     */
    private $configs = [];

    /**
     * @var SessionImpl[]
     */
    private $sessions = [];

    /**
     * @var Routes
     */
    private $routes;

    public function __construct(Routes $routes) {
        $this->routes = $routes;
    }


    /**
     * @param int $ttl number of seconds this session will survive without interaction
     * @param string $encryptionKey user for enrypting the cookie.
     * @param bool $httpOnly if this cookie is to reached only via HTTP (not javascript).
     * @param null $id optional sessionStore to configure (default is the default session store)
     * @return void
     */
    public function configure($ttl, $encryptionKey, $httpOnly = true, $id = null)
    {
        $this->configs[$id] = [
            "ttl" => $ttl,
            "encryption" => $encryptionKey,
            "httpOnly" => $httpOnly
        ];
    }

    /**
     * @param $id
     * @return SessionImpl
     * @throws RoutesException
     */
    public function createSession($id) {
        if(isset($this->sessions[$id])) {
            return $this->sessions[$id];
        }
        $config = isset($this->configs[$id]) ? $this->configs[$id] : null;
        if($config) {
            $readId = $id ?: "default";
            $cookies = $this->routes->cookies();
            if($raw = $cookies->asString("_{$readId}")) {
                if($encryption = isset($config["encryption"]) ? $config["encryption"] : null) {
                    $raw = $this->decrypt($raw,$encryption);
                } else {
                    $raw = base64_decode($raw);
                }
            }
            $data = json_decode($raw,true) ?: [];
            $session = new SessionImpl($data);
            $this->sessions[$id] = $session;
            return $session;
        } else {
            throw new RoutesException("The session store '{$id}' is not configured.");
        }
    }

    public function writeCookies(ResponseHeader $responseHeader) {
        foreach($this->configs as $id => $config) {
            $raw = null;
            if(isset($this->sessions[$id])) {
                $session = $this->sessions[$id];
                if($data = $session->data()) {
                    $json = json_encode($data);
                    if ($encryption = isset($config["encryption"]) ? $config["encryption"] : null) {
                        $raw = $this->encrypt($json, $encryption);
                    } else {
                        $raw = base64_encode($json);
                    }
                }
            } else {
                $raw = $this->routes->cookies()->asString($id);
            }
            if($raw) {
                $ttl = isset($config["ttl"]) ? $config["ttl"] : 60*10;
                $httpOnly = isset($config["httpOnly"]) ? $config["httpOnly"] : true;
                $id = $id ?: "default";

                $this->routes->addCookie("_{$id}",$raw,$ttl ? $ttl : -3600,"/",$httpOnly);
            }
        }
    }

    function encrypt($text, $salt)
    {
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }

    function decrypt($text, $salt)
    {
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }
}
