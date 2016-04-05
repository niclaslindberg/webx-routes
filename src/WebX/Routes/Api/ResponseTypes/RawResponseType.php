<?php


namespace WebX\Routes\Api\ResponseTypes;
use WebX\Routes\Api\ResponseType;


interface RawResponseType extends ResponseType
{

    /**
     * @param $contentType
     * @return RawResponseType
     */
    public function contentType($contentType);
}