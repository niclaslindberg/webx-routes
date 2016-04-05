<?php

namespace WebX\Routes\Api;

interface Request {

    const BODY_FORMAT_JSON = "json";
    const BODY_FORMAT_FORM = "form";


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
     * @param string $bodyFormat
     * @return Reader
     */
    public function bodyReader($bodyFormat);

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