<?php
namespace WebX\Route\Impl\Responses;

use WebX\Route\Api\Responses\BinaryResponse;
use WebX\Route\Api\Responses\ContentResponse;
use WebX\Route\Api\Responses\JsonResponse;
use WebX\Route\Api\AbstractResponse;
use WebX\Route\Api\Configuration;
use WebX\Route\Api\ResponseWriter;

class ContentResponseImpl extends AbstractResponse implements ContentResponse
{
    private $content;

    public function __construct(ResponseHost $responseHost) {
        parent::__construct($responseHost);
    }

    public function setContentType($contentType)
    {
        $this->addHeader("Content-Type:{$contentType}");
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