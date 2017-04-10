<?php
namespace WebX\Routes\Impl\ResponseTypes;

use WebX\Routes\Api\Configuration;
use WebX\Routes\Api\Request;
use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseBody;
use WebX\Routes\Api\ResponseException;
use WebX\Routes\Api\ResponseHeader;
use WebX\Routes\Api\ResponseTypes\RawResponseType;
use WebX\Routes\Api\ResponseTypes\RawView;
use WebX\Routes\Api\ResponseWriter;

class RawViewImpl implements RawView {

    private $contentType;

    public function __construct() {}

    public function renderHead(ResponseHeader $responseHeader, $data) {
        $responseHeader->addHeader("Content-Type",$this->contentType ?: "text/plain; charset=utf-8");
    }

    public function renderBody(ResponseBody $responseBody, $data) {
        if(is_scalar($data)) {
            $responseBody->writeContent($data);
        }
    }

    public function contentType($contentType) {
        $this->contentType = $contentType;
        return $this;
    }
}