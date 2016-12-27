<?php
/**
 * User: niclas
 * Date: 12/27/16
 * Time: 2:56 PM
 */

namespace WebX\Routes\Api;


interface SessionStore
{

    /**
     * @param $key
     * @return mixed
     */
    public function value($key);

    /**
     * @param $key
     * @param $value
     * @return mixed Old value
     */
    public function setValue($key, $value);

    /**
     * @param $key
     * @param $value
     * @return mixed Old value
     */
    public function setFlashValue($key, $value);


    /**
     * @param $key
     * @return mixed
     */
    public function flashValue($key);


    /**
     * @return void
     */
    public function kill();

    
}