<?php

namespace WebX\Routes\Impl;


use WebX\Routes\Api\Reader;
use WebX\Routes\Api\Request;
use WebX\Routes\Api\RoutesException;

class RequestImpl implements Request {

    private $headers;
    private $path;

    private $currentSegmentPos = null;
    private $segments;
    private $bodyCache;

    public function nextSegment()
    {
        if($this->currentSegmentPos===null) {
            $this->currentSegmentPos = 0;
            $this->segments = explode("/",trim($this->path()," /"));
        }
        if(count($this->segments)>$this->currentSegmentPos) {
            return $this->segments[$this->currentSegmentPos];
        }
        return null;
    }

    public function moveCurrentSegment($dpos) {
        $this->currentSegmentPos += $dpos;
    }

    public function parameter($id)
    {
       return isset($_GET[$id]) ? $_GET[$id] : null;
    }

    public function header($id) {
        if(!$this->headers) {
            $this->headers = apache_request_headers();
        }
        return isset($this->headers[$id]) ? $this->headers[$id] : null;
    }

    public function path() {
        if(!$this->path) {
            $this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        }
        return $this->path;
    }

    public function method()
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    public function body()
    {
        if(!$this->bodyCache) {
            return $this->bodyCache = file_get_contents("php://input");
        }
        return $this->bodyCache;
    }

    public function requestReader($inputFormat)
    {
        if($inputFormat!==null) {
            $inputFormats = is_array($inputFormat) ? $inputFormat : [$inputFormat];
            $reader = new ConfigurationImpl();
            foreach ($inputFormats as $inputFormat) {
                if ($inputFormat === Request::READ_BODY_AS_JSON) {
                    $reader->pushArray(json_decode($this->body(), true) ?: []);
                } else if ($inputFormat === Request::READ_BODY_AS_FORM_ENCODED) {
                    $array = [];
                    parse_str($this->body(), $array);
                    $reader->push($array);
                } else if ($inputFormat === Request::READ_QUERY_PARAMETERS) {
                    $reader->pushArray($_GET);
                } else {
                    throw new RoutesException("Unknown input format for requestReader:{$inputFormat}");
                }
            }
            return $reader;
        }
        throw new RoutesException("Missing input format for requestReader");
    }


    public function cookie($id)
    {
        return isset($_COOKIE[$id]) ? $_COOKIE[$id] : null;
    }

}
