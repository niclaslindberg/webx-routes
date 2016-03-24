<?php
namespace WebX\Routes\Impl\Responses;

use WebX\Routes\Api\AbstractResponse;
use WebX\Routes\Api\Configuration;
use WebX\Routes\Api\Responses\StreamResponse;
use WebX\Routes\Api\ResponseWriter;

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
        $callback = $this->callback;
        while(NULL!== ($content = $callback())) {
            $responseWriter->addContent($content);
        }
    }

    public function setContent(\Closure $callback)
    {
        $this->callback = $callback;
        $this->setContentAvailable();
    }


}