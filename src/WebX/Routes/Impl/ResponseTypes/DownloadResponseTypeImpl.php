<?php
namespace WebX\Routes\Impl\ResponseTypes;

use WebX\Routes\Api\Configuration;
use WebX\Routes\Api\Request;
use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseException;
use WebX\Routes\Api\ResponseTypes\DownloadResponseType;
use WebX\Routes\Api\ResponseWriter;

class DownloadResponseTypeImpl implements DownloadResponseType
{
    private $fileName;
    private $contentType;
    private $disposition = DownloadResponseType::DISPOSITION_ATTACHMENT;

    public function __construct() {}

    public function prepare(Request $request, Response $response)
    {
        $response->header("Content-Disposition", "{$this->disposition};filename={$this->fileName}");
        $response->header("Content-Type", $this->contentType ?: "application/octet-stream");
    }

    public function render(Configuration $configuration, ResponseWriter $responseWriter, $data)
    {
        if(is_scalar($data)) {
            $responseWriter->addContent($data);
        } else {
            throw new ResponseException("No valid data to render");
        }
    }

    public function fileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    public function disposition($disposition)
    {
        $this->disposition = $disposition;
        return $this;
    }

    public function contentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }


}
