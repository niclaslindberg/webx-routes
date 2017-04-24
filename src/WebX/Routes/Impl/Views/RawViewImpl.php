<?php
namespace WebX\Routes\Impl\Views;

use WebX\Routes\Api\ResponseBody;
use WebX\Routes\Api\ResponseHeader;
use WebX\Routes\Api\Views\RawView;

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