<?php

namespace WebX\Routes\Api;

use Closure;
use WebX\Routes\Impl\Path;
use WebX\Routes\Impl\Segments;

interface Routes extends ResponseHeader {

    /**
     * Reader based on posted Json
     */
    const INPUT_AS_JSON = "json";

    /**
     * Reader based on posted form-encoded parameters
     */
    const INPUT_AS_REQUEST = "request";

    /**
     * @param Closure $closure
     * @param array|string|null $configuration
     * @param array $parameters
     * @return mixed
     */
    public function map(Closure $closure, $configuration = null, array $parameters = []);

    /**
     * @param $class
     * @param array|string|null $configuration
     * @param array $parameters
     * @return bool if the request was successfully mapped to a method on the controller.
     */
    public function mapMethod($class,$configuration = null, array $parameters = []);

    /**
     * @param array|string|null $configuration
     * @param array $parameters
     * @return bool  if the request was successfully mapped to a method on a controller.
     */
    public function mapCtrl($configuration = null, array $parameters = []);

    /**
     * Forwards execution to another routes segment
     * @param $routesName
     * @return bool if the processing was successfully forwarded.
     */
    public function forward($routesName);

    /**
     * Sets data in context
     * @param mixed $data
     * @param string|null $path
     * @return Routes
     */
    public function setData($data, $path=null);

    /**
     * Gets data from context
     * @param null $path
     * @return mixed
     */
    public function data($path=null);

    /**
     * @param View $responseType
     * @return Routes
     */
    public function setView(View $view);

    /**
     * @return View|null
     */
    public function view();

    /**
     * The $_SERVER wrapped as reader
     * @return Reader
     */
    public function server();

    /**
     * The body of the request
     * @return string|null
     */
    public function body();

    /**
     * Returns all get query parameters
     * @param string $type Routes#INPUT_AS_REQUEST or Routes#INPUT_AS_JSON
     * @return Reader
     */
    public function input($type = "request");

    /**
     * @return Reader
     */
    public function cookies();

    /**
     * @return Reader
     */
    public function headers();

    /**
     * @return Path
     */
    public function path();

    /**
     * @param $id|null
     * @return Session
     */
    public function session($id=null);

    /**
     * Returns the absolute path of the relative path based on the resource loader settings.
     * @param null $relativePath
     * @return string|null
     */
    public function resourcePath($relativePath = null);
}

?>