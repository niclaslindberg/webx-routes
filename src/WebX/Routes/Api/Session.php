<?php

namespace WebX\Routes\Api;


interface Session
{

    /**
     * Reads the session value.
     * @param string $key
     * @return mixed
     */
    public function value($key);

    /**
     * @param $key
     * @param string $value
     * @return mixed Old value
     */
    public function setValue($key, $value);

    /**
     * Deletes a value (it's key)
     * @param string $key
     * @return mixed Old value
     */
    public function unsetValue($key);


    /**
     * @return void
     */
    public function kill();
    
}