<?php

namespace WebX\Route\Impl;


use WebX\Route\Api\Request;

class RequestImpl implements Request {

    private $headers;
    private $path;

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
        return file_get_contents("php://input");
    }

    public function cookie($id)
    {
        return isset($_COOKIE[$id]) ? $_COOKIE[$id] : null;
    }

}
