<?php

namespace WebX\Routes\Api\ResponseTypes;
use WebX\Routes\Api\ResponseType;


interface JsonResponseType extends ResponseType
{

    /**
     * @param $contentType
     * @return JsonResponseType
     */
    public function contentType($contentType);
}