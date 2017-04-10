<?php

namespace WebX\Routes\Impl;

use WebX\Routes\Api\RoutesException;
use WebX\Routes\Api\Session;

class SessionImpl implements Session {

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
        if(null!==$value) {
            $this->data[$key] = $value;
        } else {
            unset($this->data[$key]);
        }
        return $val;
    }

    public function unsetValue($key)
    {
        $this->assertNotKilled();
        $val = $this->value($key);
        unset($this->data[$key]);
        return $val;
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
