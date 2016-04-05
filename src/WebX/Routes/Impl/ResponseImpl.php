<?php

namespace WebX\Routes\Impl;


use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseType;

class ResponseImpl implements Response {

    public $data;

    public $headers = [];

    public $cookies = [];

    public $status;

    /**
     * @var ResponseType
     */
    public $responseType;

    public $hasResponse = false;

    public function data($data, $path = null)
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
                $root[$part] = empty($path) ? $data : ((isset($root[$part]) && is_array($root[$part])) ? $root[$part] : []);
                $root = &$root[$part];
            }
        } else if(is_array($this->data) && is_array($data)) {
            $this->data = ArrayUtil::mergeRecursive($this->data,$data);
        } else {
            $this->data = $data;
        }
        $this->hasResponse = true;
    }

    public function header($name,$value)
    {
        $this->headers[$name] = $value;
    }

    public function cookie($name, $value, $ttl = 0, $path = "/")
    {
        $this->cookies[$name] = [
            "value" => $value,
            "ttl" => $ttl,
            "$path" => $path
        ];
    }

    public function status($httpStatus, $message=null)
    {
        $this->status = $message ? [$httpStatus,$message] : [$httpStatus];
        $this->hasResponse = true;
    }

    public function type(ResponseType $responseType)
    {
        $this->responseType = $responseType;
    }
}
