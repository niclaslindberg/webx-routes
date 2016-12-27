<?php

namespace WebX\Routes\Impl;


use WebX\Ioc\Ioc;
use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseType;
use WebX\Routes\Api\ResponseTypes\DownloadResponseType;
use WebX\Routes\Api\ResponseTypes\FileContentResponseType;
use WebX\Routes\Api\ResponseTypes\JsonResponseType;
use WebX\Routes\Api\ResponseTypes\RawResponseType;
use WebX\Routes\Api\ResponseTypes\RedirectResponseType;
use WebX\Routes\Api\ResponseTypes\TemplateResponseType;
use WebX\Routes\Api\RoutesException;

class ResponseImpl implements Response {

    /**
     * @var Ioc
     */
    private $ioc;

    public $data;

    public $headers = [];

    public $cookies = [];

    public $status;

    /**
     * @var ResponseType
     */
    public $responseType;

    public $hasResponse = false;

    public function __construct(Ioc $ioc) {
        $this->ioc = $ioc;
    }

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

    public function cookie($name, $value, $ttl = 0, $path = "/", $httpOnly = true)
    {
        $this->cookies[$name] = [
            "value" => $value,
            "ttl" => $ttl,
            "path" => $path,
            "httpOnly" => $httpOnly
        ];
    }

    public function status($httpStatus, $message=null)
    {
        $this->status = $message ? [$httpStatus,$message] : [$httpStatus];
        $this->hasResponse = true;
    }

    public function type(ResponseType $responseType)
    {
        $this->hasResponse = true;
        $this->responseType = $responseType;
    }

    public function typeTemplate($id=null)
    {
        $this->hasResponse = true;
        $templateResponseType = $this->ioc->get(TemplateResponseType::class);
        if($id) {
            $templateResponseType->id($id);
        }
        return $this->responseType = $templateResponseType;
    }

    public function typeRaw($data = null)
    {
        if($data) {
            $this->data($data);
        }
        $this->hasResponse = true;
        return $this->responseType = $this->ioc->get(RawResponseType::class);
    }

    public function typeJson($data=null)
    {
        if($data) {
            $this->data($data);
        }
        $this->hasResponse = true;
        return $this->responseType = $this->ioc->get(JsonResponseType::class);
    }

    public function typeDownload()
    {
        $this->hasResponse = true;
        return $this->responseType = $this->ioc->get(DownloadResponseType::class);
    }

    public function typeRedirect($url=null)
    {
        $this->hasResponse = true;
        $redirectResponse = $this->ioc->get(RedirectResponseType::class);
        if($url) {
            $redirectResponse->url($url);
        }
        return $this->responseType = $redirectResponse;
    }

    public function typeFileContent($file=null)
    {
        $this->hasResponse = true;
        $fileContentResponseType = $this->ioc->get(FileContentResponseType::class);
        if($file) {
            $fileContentResponseType->file($file);
        }
        return $this->responseType = $fileContentResponseType;
   }

    public function currentResponseType()
    {
        return $this->responseType;
    }

    public function currentStatus()
    {
        return $this->status ?: 200;
    }


}
