<?php
namespace WebX\Route\Impl\Responses;

use WebX\Route\Api\Responses\JsonResponse;
use WebX\Route\Api\AbstractResponse;
use WebX\Route\Api\Configuration;
use WebX\Route\Api\ResponseWriter;

class JsonResponseImpl extends AbstractResponse implements JsonResponse
{
    private $data;

    public function __construct(ResponseHost $responseHost) {
        parent::__construct($responseHost);
    }

    public function setData($data, $path = null)
    {
        if($path) {
            if(is_string($path)) {
                $path = explode(".",$path);
            }
            if($this->data === null) {
                $this->data = [];
            }
            $root = &$this->data;
            while($part = array_shift($path)) {
                $root[$part] = empty($path) ? $data : [];
                $root = &$root[$part];
            }
        } else {
            $this->data = $data;
        }
        $this->setContentAvailable();
    }

    public function generateContent(Configuration $configuration, ResponseWriter $responseWriter)
    {
        $responseWriter->addContent(json_encode($this->data,JSON_PRETTY_PRINT));
    }
}