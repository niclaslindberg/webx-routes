<?php


namespace WebX\Routes\Api\ResponseTypes;
use WebX\Routes\Api\ResponseRenderer;
use WebX\Routes\Api\ResponseType;


interface RawResponseType extends ResponseRenderer
{
    public function contentType($contentType);
}