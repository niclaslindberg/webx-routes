<?php

namespace WebX\Route\Api\Responses;

interface ContentResponse extends Response {


    public function setContent($data);

    /**
     * @param $contentType The content type. Default text/html; charset-utf-8
     * @return void
     */
    public function setContentType($contentType);
}

?>