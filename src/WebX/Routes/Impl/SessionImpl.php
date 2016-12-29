<?php
/**
 * User: niclas
 * Date: 12/27/16
 * Time: 3:25 PM
 */

namespace WebX\Routes\Impl;


use WebX\Routes\Api\Request;
use WebX\Routes\Api\Response;
use WebX\Routes\Api\RoutesException;
use WebX\Routes\Api\Session;
use WebX\Routes\Api\SessionConfig;

class SessionImpl implements Session, SessionConfig {

    /**
     * @var array
     */
    private $configs = [];

    /**
     * @var SessionStoreImpl[]
     */
    private $sessionStores = [];

    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
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
     * @return SessionStoreImpl
     */
    private function createSessionStore($id) {
        if(isset($this->sessionStores[$id])) {
            return $this->sessionStores[$id];
        }
        $config = isset($this->configs[$id]) ? $this->configs[$id] : null;
        if($config) {
            $readId = $id ?: "default";
            if($raw = $this->request->cookie("_{$readId}")) {
                if($encryption = isset($config["encryption"]) ? $config["encryption"] : null) {
                    $raw = $this->decrypt($raw,$encryption);
                } else {
                    $raw = base64_decode($raw);
                }
            }
            $data = json_decode($raw,true) ?: [];
            $sessionStore = new SessionStoreImpl($data);
            $this->sessionStores[$id] = $sessionStore;
            return $sessionStore;
        } else {
            throw new RoutesException("The session store '{$id}' is not configured.");
        }
    }

    public function getStore($id)
    {
        return $this->createSessionStore($id);
    }

    public function value($key)
    {
        return $this->createSessionStore(null)->value($key);
    }

    public function setValue($key, $value)
    {
        return $this->createSessionStore(null)->setValue($key,$value);
    }

    public function unsetValue($key)
    {
        return $this->createSessionStore(null)->unsetValue($key);
    }

    /**
     * @return void
     */
    public function kill()
    {
        $this->createSessionStore(null)->kill();
    }

    public function writeCookies(Response $response) {
        foreach($this->configs as $id => $config) {
            $raw = null;
            if(isset($this->sessionStores[$id])) {
                $sessionStore = $this->sessionStores[$id];
                if($data = $sessionStore->data()) {
                    $json = json_encode($data);
                    if ($encryption = isset($config["encryption"]) ? $config["encryption"] : null) {
                        $raw = $this->encrypt($json, $encryption);
                    } else {
                        $raw = base64_encode($json);
                    }
                }
            } else {
                $raw = $this->request->cookie($id);
            }
            if($raw) {
                $ttl = isset($config["ttl"]) ? $config["ttl"] : 60*10;
                $httpOnly = isset($config["httpOnly"]) ? $config["httpOnly"] : true;
                $id = $id ?: "default";
                $response->cookie("_{$id}",$raw,$raw ? $ttl : -3600,"/",$httpOnly);
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
