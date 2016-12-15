<?php

namespace WebX\Routes\Api;

interface Request {

    /**
     * Request::reader based on posted Json
     */
    const INPUT_AS_JSON = "json";

    /**
     * Request::reader based on posted form-encoded parameters
     */
    const INPUT_AS_FORMENCODED = "form";

    /**
     * Request::reader based on url query parameters
     */
    const INPUT_AS_QUERY = "query";

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
     * The body of the request
     * @return string
     */
    public function body();

    /**
     * A Reader for one ore more request input.
     * @param string|string[] $bodyFormat (If array the reader will return the first non-null value in the order of declaration).
     * @return Reader
     */
    public function reader($inputFormat);

    /**
     * Returns the value of the query parameter with the same id.
     * @param $id
     * @return string
     */
    public function parameter($id);

    /**
     * Returns all get query parameters key=>value
     * @return array
     */
    public function parameters();

    public function cookie($id);

    public function header($id);

    public function nextSegment();


    /**
     * @return string returns the protocol of the request (http|https).
     */
    public function protocol();
    /**
     * @return string the external host name.
     */
    public function host();


    /**
     * The full path, including protocol and host
     * @param string $path
     * @return string
     */
    public function fullPath($path = "");


}

?>