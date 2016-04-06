<?php
namespace WebX\Routes\Impl\ResponseTypes;

use WebX\Routes\Api\Configuration;
use WebX\Routes\Api\Request;
use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseTypes\JsonResponseType;
use WebX\Routes\Api\ResponseWriter;

class JsonResponseTypeImpl implements JsonResponseType
{
    private $contentType;

    public function prepare(Request $request, Response $response)
    {
        $response->header("Content-Type",$this->contentType ?: "application/json; charset=utf-8");
    }

    public function render(Configuration $configuration, ResponseWriter $responseWriter, $data)
    {
        $responseWriter->addContent(json_encode($data,$configuration->asBool("prettyPrint") ? JSON_PRETTY_PRINT : 0));
    }

    public function contentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }


}