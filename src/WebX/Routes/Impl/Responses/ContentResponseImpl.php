<?php
namespace WebX\Routes\Impl\Responses;

use WebX\Routes\Api\AbstractResponse;
use WebX\Routes\Api\Configuration;
use WebX\Routes\Api\ResponseHost;
use WebX\Routes\Api\Responses\BinaryResponse;
use WebX\Routes\Api\Responses\ContentResponse;
use WebX\Routes\Api\ResponseWriter;

class ContentResponseImpl extends AbstractResponse implements ContentResponse
{
    private $content;

    public function __construct(ResponseHost $responseHost) {
        parent::__construct($responseHost);
        $this->setContentType("text/plain");
    }

    public function generateContent(Configuration $configuration, ResponseWriter $responseWriter)
    {
        $responseWriter->addContent($this->content);
    }
    public function setContent($content)
    {
        $this->content = $content;
        $this->setContentAvailable();
    }
}