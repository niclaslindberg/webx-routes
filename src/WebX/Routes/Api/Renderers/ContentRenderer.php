<?php


namespace WebX\Routes\Api\ResponseTypes;
use WebX\Routes\Api\ResponseRenderer;

interface ContentRenderer extends ResponseRenderer
{
    const DISPOSITION_INLINE = "inline";
    const DISPOSITION_ATTACHMENT = "attachment";

    public function setDispostion($name, $dispostion = self::DISPOSITION_ATTACHMENT);

    public function setFile($file,$contentType = null);

    public function setContent($content,$contentType = "text/plain");

}