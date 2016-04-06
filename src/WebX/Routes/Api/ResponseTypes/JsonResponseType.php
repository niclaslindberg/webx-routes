<?php

namespace WebX\Routes\Api\ResponseTypes;
use WebX\Routes\Api\ResponseType;


interface JsonResponseType extends ResponseType
{

    /**
     * @param $contentType default is application/octect-stream
     * @return JsonResponseType
     */
    public function contentType($contentType);
}