<?php
namespace WebX\Routes\Impl\Views;

use WebX\Routes\Api\ResponseBody;
use WebX\Routes\Api\ResponseHeader;
use WebX\Routes\Api\Views\JsonView;
use WebX\Routes\Impl\ArrayUtil;

class JsonViewImpl implements JsonView {
    /**
     * @var int
     */
    private $parameters;

    /**
     * @var mixed
     */
    private $data;

    public function setJsonParameters($parameters) {
        $this->parameters = $parameters;
        return $this;
    }

    public function renderHead(ResponseHeader $responseHeader, $data) {
        $responseHeader->addHeader("Content-Type","application/json; charset=utf-8");
    }

    public function renderBody(ResponseBody $responseBody, $data) {
        $responseBody->writeContent(json_encode(ArrayUtil::mergeRecursive($data,$this->data),$this->parameters));
    }

    public function setData($data, $path = null) {
        if (null !== $path) {
            if (is_string($path)) {
                $path = explode(".", $path);
            }
            if (!is_array($this->data)) {
                $this->data = [];
            }
            $root = &$this->data;
            while ($part = array_shift($path)) {
                $root[$part] = empty($path) ? $data : ((isset($root[$part]) && is_array($root[$part])) ? $root[$part] : []);
                $root = &$root[$part];
            }
        } else {
            $this->data = $data;
        }
        return $this;
    }



}