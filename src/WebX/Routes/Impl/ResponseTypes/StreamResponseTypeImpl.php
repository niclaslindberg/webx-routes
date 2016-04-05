<?php
namespace WebX\Routes\Impl\ResponseTypes;

use Closure;
use WebX\Routes\Api\Configuration;
use WebX\Routes\Api\Request;
use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseException;
use WebX\Routes\Api\ResponseTypes\StreamResponseType;
use WebX\Routes\Api\ResponseWriter;

class StreamResponseTypeImpl implements StreamResponseType
{
    /**
     * @var string
     */
    private $contentType;


    public function __construct() {
    }

    public function prepare(Request $request, Response $response)
    {
        $response->header("Content-Type",$this->contentType ?: "application/stream-octet");
    }

    public function render(Configuration $configuration, ResponseWriter $responseWriter, $data)
    {
        if(is_a($data, Closure::class)) {
            while(NULL!== ($content = $data())) {
                $responseWriter->addContent($content);
            }
        } else {
            throw new ResponseException("Data for StreamResponseType is not a valid Closure");
        }
    }

    public function contentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }


}