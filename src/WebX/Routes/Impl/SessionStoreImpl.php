<?php
/**
 * User: niclas
 * Date: 12/27/16
 * Time: 3:25 PM
 */

namespace WebX\Routes\Impl;


use Exception;
use WebX\Routes\Api\Request;
use WebX\Routes\Api\Response;
use WebX\Routes\Api\RoutesException;
use WebX\Routes\Api\Session;
use WebX\Routes\Api\SessionConfig;
use WebX\Routes\Api\SessionStore;

class SessionStoreImpl implements SessionStore {

    /**
     * @var array
     */
    private $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function value($key)
    {
        $this->assertNotKilled();
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function setValue($key, $value)
    {
        $this->assertNotKilled();
        $val = $this->value($key);
        $this->data[$key] = $value;
        return $val;
    }

    public function setFlashValue($key, $value)
    {
        $this->assertNotKilled();
        throw new Exception("Not implemented");
    }

    public function flashValue($key)
    {
        $this->assertNotKilled();
        throw new Exception("Not implemented");
    }

    public function kill()
    {
        $this->data = null;
    }

    private function assertNotKilled() {
        if($this->data === null) {
            throw new RoutesException("Session store is killed. No writing/reading allowed.");
        }
    }

    public function data() {
        return $this->data;
    }

}
