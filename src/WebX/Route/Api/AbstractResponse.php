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
        $this->responseHost->addHeader($this,$header);
    }

    public function addCookie($cookie)
    {
        $this->responseHost->addCookie($this,$cookie);
    }

    public function setStatus($httpStatus)
    {
        $this->responseHost->setStatus($this,$httpStatus);
    }

    protected function setContentAvailable() {
        $this->responseHost->setContentAvailable($this);
    }

    public abstract function generateContent(Configuration $configuration, ResponseWriter $responseWriter);
}

?>