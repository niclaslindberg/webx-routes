<?php

namespace WebX\Routes\Api\Responses;

use WebX\Routes\Api\Response;

interface DownloadResponse extends Response {

    const DISPOSITION_INLINE = "inline";
    const DISPOSITION_ATTACHMENT = "attachment";

    /**
     * The content of the file to be downloaded
     * @param string $content
     * @param string fileNames
     * @param string $disposition The disposition DownloadResponse::DISPOSITION_ATTACHMENT(default) or DownloadResponse::DISPOSITION_INLINE
     * @return void
     */
    public function setContent($content,$fileName, $disposition = DownloadResponse::DISPOSITION_ATTACHMENT);

}

?>