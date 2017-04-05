<?php

namespace WebX\Routes\Api;

use Closure;

interface Routes extends ResponseHeader, ResponseBody{


    /**
     * @param Closure $closure
     * @param array $parameters
     * @return mixed
     */
    public function invoke(Closure $closure, $configuration = null, array $parameters = []);

    /**
     * @param $name
     * @return mixed
     */
    public function load($name);

    /**
     * @param mixed $data
     * @param string|null $path
     * @return Routes
     */
    public function setData($data, $path=null);

    /**
     * @param ResponseRenderer $responseType
     * @return Routes
     */
    public function setRenderer(ResponseRenderer $responseRenderer);

}

?>