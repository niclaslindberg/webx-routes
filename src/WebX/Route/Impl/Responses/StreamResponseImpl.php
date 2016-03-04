<?php
namespace WebX\Route\Impl\Responses;

use WebX\Route\Api\AbstractResponse;
use WebX\Route\Api\Configuration;
use WebX\Route\Api\Responses\StreamResponse;
use WebX\Route\Api\ResponseWriter;

class StreamResponseImpl extends AbstractResponse implements StreamResponse
{
    /**
     * @var \Closure
     */
    private $callback;

    public function __construct(ResponseHost $responseHost) {
        parent::__construct($responseHost);
    }


    public function generateContent(Configuration $configuration, ResponseWriter $responseWriter)
    {
        $callback = $this->callback();
        while(NULL!== ($content = $callback())) {
            $responseWriter->addContent($content);
        }
    }

    public function setContent(\Closure $callback)
    {
        $this->callback = $callback;
        $this->setContentAvailable();
    }


    public function setContentType($contentType)
    {
        $this->addHeader("Content-Type:{$contentType}");
    }


}