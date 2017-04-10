<?php


namespace WebX\Routes\Api\ResponseTypes;
use WebX\Routes\Api\View;

interface ContentView extends View {


    const DISPOSITION_INLINE = "inline";
    const DISPOSITION_ATTACHMENT = "attachment";

    public function setDisposition($fileName, $disposition = self::DISPOSITION_ATTACHMENT);

    public function setFile($file,$contentType = null);

    public function setContent($content,$contentType = null);

}