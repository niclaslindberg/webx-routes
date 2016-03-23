<?php

namespace WebX\Routes\Api;

interface Request {


    /**
     * Returns $_SERVER['REQUEST_URIs']
     * @return string
     */
    public function path();

    /**
     * Returns $_SERVER['REQUEST_METHOD']
     * @return string
     */
    public function method();

    /**
     * Returns the raw body
     * @return string
     */
    public function body();

    /**
     * Returns the value of the query parameter with the same id.
     * @param $id
     * @return string
     */
    public function parameter($id);

    public function cookie($id);

    public function header($id);

    public function nextSegment();

}

?>