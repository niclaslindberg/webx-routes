<?php
namespace WebX\Routes\Impl\ResponseTypes;

use WebX\Routes\Api\Configuration;
use WebX\Routes\Api\Request;
use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseException;
use WebX\Routes\Api\ResponseTypes\FileContentResponseType;
use WebX\Routes\Api\ResponseWriter;

class FileContentResponseTypeImpl implements FileContentResponseType
{
    private $file;
    private $contentType;

    public function __construct() {}

    public function prepare(Request $request, Response $response)
    {
        $response->header("Content-Type", $this->contentType ?: (FilenameMimetypeFactory::findMimeType($this->file) ?: "application/octet-stream"));
    }

    public function render(Configuration $configuration, ResponseWriter $responseWriter, $data)
    {
        if(file_exists($this->file)) {
            $responseWriter->addContent(file_get_contents($this->file));
        } else {
            throw new ResponseException("File {$this->file} does not exist");
        }
    }

    public function contentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }

    public function file($file)
    {
        $this->file = $file;
        return $this;
    }


}
