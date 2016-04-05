<?php

namespace WebX\Routes\Api\ResponseTypes;
use WebX\Routes\Api\ResponseType;

interface StreamResponseType extends ResponseType
{

    /**
     * @param $contentType
     * @return StreamResponseType
     */
    public function contentType($contentType);
}