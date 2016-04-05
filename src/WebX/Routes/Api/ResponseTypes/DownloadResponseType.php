<?php


namespace WebX\Routes\Api\ResponseTypes;
use WebX\Routes\Api\ResponseType;

interface DownloadResponseType extends ResponseType
{

    const DISPOSITION_INLINE = "inline";
    const DISPOSITION_ATTACHMENT = "attachment";

    /**
     * @param $fileName
     * @return DownloadResponseType
     */
    public function fileName($fileName);

    /**
     * @param $disposition
     * @return DownloadResponseType
     */
    public function disposition($disposition);

     /**
     * @param $contentType
     * @return DownloadResponseType
     */
    public function contentType($contentType);
}