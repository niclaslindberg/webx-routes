<?php

namespace WebX\Routes\Api;

use WebX\Routes\Api\Configuration;
use WebX\Routes\Api\RendererHost;

abstract class AbstractResponse implements Response {

    /**
     * @var ResponseHost
     */
    private $responseHost;
    private $contentType;

    public function __construct(ResponseHost $responseHost) {
        $this->responseHost = $responseHost;
    }

    public function addHeader($header)
    {
        $this->responseHost->registerHeader($this,$header);
    }

    public function addCookie($name, $value, $ttl=0, $path = "/")
    {
        $this->responseHost->registerCookie($this,$name, $value, $ttl=0, $path = "/");
    }

    public function setStatus($httpStatus)
    {
        $this->responseHost->registerStatus($this,$httpStatus);
    }

    protected function setContentAvailable() {
        $this->responseHost->setContentAvailable($this);
    }

    /**
     * The content type of the response.
     * @return string
     */
    public function getContentType() {
        return $this->contentType;
    }

    public function setContentType($contentType) {
        $this->contentType = $contentType;
    }

    public abstract function generateContent(Configuration $configuration, ResponseWriter $responseWriter);

}

?>