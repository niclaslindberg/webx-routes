<?php
namespace WebX\Routes\Impl\ResponseTypes;

use WebX\Routes\Api\ResponseBody;
use WebX\Routes\Api\ResponseHeader;
use WebX\Routes\Api\ResponseTypes\JsonView;

class JsonViewImpl implements JsonView
{
    private $parameters;

    public function setJsonParameters($parameters) {
        $this->parameters = $parameters;
        return $this;
    }

    public function renderHead(ResponseHeader $responseHeader, $data) {
        $responseHeader->addHeader("Content-Type","application/json; charset=utf-8");
    }

    public function renderBody(ResponseBody $responseBody, $data) {
        $responseBody->writeContent(json_encode($data,$this->parameters));
    }

}