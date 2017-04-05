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
    const INPUT_AS_FORM = "form";

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
     * The $_SERVER wrapped as reader
     * @return Reader
     */
    public function server();

    /**
     * The body of the request
     * @return string|null
     */
    public function rawBody();

    /**
     * A Reader for one ore more request input.
     * @param string|string[] $bodyFormat (If array the reader will return the first non-null value in the order of declaration).
     * @return Reader
     */
    public function body($bodyFormat);

    /**
     * Returns the value of the query parameter with the same id.
     * @param $id
     * @return string
     */
    public function parameter($id);

    /**
     * Returns all get query parameters
     * @return Reader
     */
    public function parameters();

    /**
     * @return Reader
     */
    public function cookies();

    /**
     * @param $id
     * @return string|null
     */
    public function cookie($id);

    /**
     * @return Reader
     */
    public function headers();

    /**
     * @param $id
     * @return string|null
     */
    public function header($id);

    /**
     * The next segment (path split by /)
     * @return string|null
     */
    public function nextSegment();

    /**
     * @return string[]
     */
    public function remainingSegments();

    /**
     * @return string|null
     */
    public function currentSegment();

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