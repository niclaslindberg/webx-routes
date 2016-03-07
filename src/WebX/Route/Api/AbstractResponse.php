<?php

namespace WebX\Route\Api;

use WebX\Route\Api\Configuration;
use WebX\Route\Api\RendererHost;

abstract class AbstractResponse implements Response {

    /**
     * @var ResponseHost
     */
    private $responseHost;

    public function __construct(ResponseHost $responseHost) {
        $this->responseHost = $responseHost;
    }

    public function addHeader($header)
    {
        $this->responseHost->registerHeader($this,$header);
    }

    public function addCookie($cookie)
    {
        $this->responseHost->registerCookie($this,$cookie);
    }

    public function setStatus($httpStatus)
    {
        $this->responseHost->registerStatus($this,$httpStatus);
    }

    protected function setContentAvailable() {
        $this->responseHost->setContentAvailable($this);
    }

    public abstract function generateContent(Configuration $configuration, ResponseWriter $responseWriter);
}

?>