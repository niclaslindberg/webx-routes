<?php


namespace WebX\Routes\Api\ResponseTypes;
use WebX\Routes\Api\ResponseType;


interface RawResponseType extends ResponseType
{

    /**
     * @param $contentType default is text/html; charset=utf8
     * @return RawResponseType
     */
    public function contentType($contentType);
}