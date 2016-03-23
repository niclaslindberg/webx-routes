<?php
namespace WebX\Routes\Impl\Responses;

use WebX\Routes\Api\Responses\BinaryResponse;
use WebX\Routes\Api\Responses\ContentResponse;
use WebX\Routes\Api\Responses\DownloadResponse;
use WebX\Routes\Api\Responses\fileNames;
use WebX\Routes\Api\Responses\JsonResponse;
use WebX\Routes\Api\AbstractResponse;
use WebX\Routes\Api\Configuration;
use WebX\Routes\Api\ResponseWriter;

class ContentResponseImpl extends AbstractResponse implements DownloadResponse
{
    private $content;
    private $disposition = DownloadResponse::DISPOSITION_ATTACHMENT;

    public function __construct(ResponseHost $responseHost) {
        parent::__construct($responseHost);
    }

    public function setContentType($contentType)
    {
        $this->addHeader("Content-Type:{$contentType}");
    }

    public function setContent($content, $fileName, $disposition = DownloadResponse::DISPOSITION_ATTACHMENT)
    {
        $this->addHeader("Content-Disposition: {$disposition};filename={$fileName}");
        $this->content = $content;
    }


    public function generateContent(Configuration $configuration, ResponseWriter $responseWriter)
    {
        $responseWriter->addContent($this->content);
    }


}