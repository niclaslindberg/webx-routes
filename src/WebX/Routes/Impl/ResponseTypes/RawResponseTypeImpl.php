<?php
namespace WebX\Routes\Impl\ResponseTypes;

use WebX\Routes\Api\Configuration;
use WebX\Routes\Api\Request;
use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseException;
use WebX\Routes\Api\ResponseTypes\RawResponseType;
use WebX\Routes\Api\ResponseWriter;

class RawResponseTypeImpl implements RawResponseType
{

    private $contentType;

    public function __construct() {}

    public function prepare(Request $request, Response $response)
    {
        $response->header("Content-Type",$this->contentType ?: "text/plain; charset=utf-8");
    }

    public function render(Configuration $configuration, ResponseWriter $responseWriter, $data)
    {
        if(is_scalar($data) || $data===null) {
            $responseWriter->addContent($data);
        } else {
            throw new ResponseException("Bad data to render.");
        }
    }

    public function contentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }

}