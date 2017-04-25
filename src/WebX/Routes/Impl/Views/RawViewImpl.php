<?php
namespace WebX\Routes\Impl\Views;

use WebX\Routes\Api\ResponseBody;
use WebX\Routes\Api\ResponseHeader;
use WebX\Routes\Api\Views\RawView;
use WebX\Routes\Impl\ArrayUtil;

class RawViewImpl implements RawView {

    private $contentType;

    private $data;

    public function __construct() {}

    public function setData($data) {
        $this->data = $data;
        return $this;
    }

    public function renderHead(ResponseHeader $responseHeader, $data) {
        $responseHeader->addHeader("Content-Type",$this->contentType ?: "text/plain; charset=utf-8");
    }

    public function renderBody(ResponseBody $responseBody, $data) {
        if($data = ArrayUtil::mergeRecursive($data,$this->data)) {
            if(is_scalar($data)) {
                $responseBody->writeContent($data);
            } else {
                error_log("Non scalar data in RawResponse");
            }
        }
    }

    public function contentType($contentType) {
        $this->contentType = $contentType;
        return $this;
    }
}