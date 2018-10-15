<?php

namespace WebX\Routes\Impl;

use Defuse\Crypto\Crypto;
use WebX\Routes\Api\ResponseHeader;
use WebX\Routes\Api\Routes;
use WebX\Routes\Api\RoutesException;
use WebX\Routes\Api\WritableMap;

class SessionManagerImpl   {

    /**
     * @var array
     */
    private $configs = [];

    /**
     * @var WritableMap[string]
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
    public function configure($ttl, $encryptionKey, $httpOnly = true, $id = null) {
        $this->configs[$id] = [
            "ttl" => $ttl,
            "encryption" => $encryptionKey,
            "httpOnly" => $httpOnly
        ];
    }

    /**
     * @param $id
     * @return WritableMapImpl
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
                    $raw = self::decrypt($raw,$encryption);
                } else {
                    $raw = base64_decode($raw);
                }
            }
            $data = json_decode($raw,true) ?: [];
            $session = new WritableMapImpl($data);
            $this->sessions[$id] = $session;
            return $session;
        } else {
            throw new RoutesException("The session store '{$id}' is not configured.");
        }
    }

    public function writeCookies(ResponseHeader $responseHeader) {
        foreach($this->configs as $id => $config) {
            $raw = null;
            $ttl = isset($config["ttl"]) ? $config["ttl"] : 60*10;
            if(isset($this->sessions[$id])) {
                $session = $this->sessions[$id];
                if($data = $session->raw()) {
                    $json = json_encode($data);
                    if ($encryption = isset($config["encryption"]) ? $config["encryption"] : null) {
                        $raw = self::encrypt($json, $encryption);
                    } else {
                        $raw = base64_encode($json);
                    }
                } else {
                    $raw = "";
                    $ttl = 0;
                }
            } else {
                $raw = $this->routes->cookies()->asString($id);
            }
            $httpOnly = isset($config["httpOnly"]) ? $config["httpOnly"] : true;
            $id = $id ?: "default";
            $responseHeader->addCookie("_{$id}",$raw,$ttl ? $ttl : -3600,"/",$httpOnly);
        }
    }

    static function encrypt($text, $salt) {
        return Crypto::encryptWithPassword($text,$salt);
    }

    static function decrypt($text, $salt) {
        return Crypto::decryptWithPassword($text,$salt);
    }
}
