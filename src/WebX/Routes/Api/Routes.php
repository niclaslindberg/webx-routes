<?php

namespace WebX\Routes\Api;

use Closure;
use WebX\Routes\Impl\Path;

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
    public function run(Closure $closure, $configuration = null, array $parameters = []);

    /**
     * @param string $class
     * @param array|string|null $configuration
     * @param array $parameters
     * @return bool if the method defined a view.
     */
    public function runMethod($class,$configuration = null, array $parameters = []);

    /**
     * @param array|string|null $configuration
     * @param array $parameters
     * @return bool if the controller method defined a view.
     */
    public function runCtrl($configuration = null, array $parameters = []);

    /**
     * Forwards execution to another routes segment
     * @param $routesName
     * @param null $segmentCondition will only execute the forward if the condition is equal to the current segment.
     * @return bool if the forward successfully rendered a view.
     */
    public function forward($routesName,$segmentCondition=null);

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