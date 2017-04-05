<?php


namespace WebX\Routes\Api\ResponseTypes;
use WebX\Routes\Api\ResponseType;

interface FileContentResponseType extends ResponseType
{

     /**
     * @param string $contentType
     * @return FileContentResponseType
     */
    public function contentType($contentType);

    /**
     * @param string $contentType
     * @return FileContentResponseType
     */
    public function file($file);
}