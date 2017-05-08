<?php

namespace WebX\Routes\Api;

use Closure;
use WebX\Routes\Api\Path;

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
     * @param string $condition executes only if equals current segment or if equals null
     * @param Closure $closure
     * @param array|string|null $configuration
     * @param array $parameters
     * @return bool if the method defined a view.
     */
    public function runAction($condition, Closure $closure, $configuration=null, array $parameters=[]);

    /**
     * @param string $condition executes only if equals current segment or if equals null
     * @param string $class
     * @param array|string|null $configuration
     * @param array $parameters
     * @return bool if the method defined a view.
     */
    public function runMethod($condition, $class, $configuration=null, array $parameters = []);

    /**
     * @param string $condition see notes
     * @param array|string|null $configuration
     * @param array $parameters
     * @return bool if the controller method defined a view.
     */
    public function runCtrl($condition=true, $configuration=null, array $parameters=[]);

    /**
     * Forwards execution to another routes segment
     * @param string $condition executes only if equals current segment or if equals null
     * @param $routesName
     * @return bool if the forward successfully rendered a view.
     */
    public function runRoute($condition, $routesName);

    /**
     * The shared data in the application
     * @return WritableMap
     */
    public function data();

    /**
     * @return View|null
     */
    public function view();

    /**
     * The $_SERVER wrapped as reader
     * @return Map
     */
    public function server();

    /**
     * @return string
     */
    public function verb();
    /**
     * @return Map
     */
    public function options();

    /**
     * The body of the request
     * @return string|null
     */
    public function body();

    /**
     * Returns all get query parameters
     * @param string $type Routes#INPUT_AS_REQUEST or Routes#INPUT_AS_JSON
     * @return Map
     */
    public function input($type = "request");

    /**
     * @return Map
     */
    public function cookies();

    /**
     * @return Map
     */
    public function headers();

    /**
     * @return Path
     */
    public function path();

    /**
     * @param $id|null
     * @return WritableMap
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