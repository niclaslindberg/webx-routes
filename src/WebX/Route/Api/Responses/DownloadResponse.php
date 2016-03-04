<?php

namespace WebX\Route\Api\Responses;

interface DownloadResponse extends ContentResponse {

    const DISPOSITION_INLINE = "inline";
    const DISPOSITION_ATTACHMENT = "attachment";

    public function setFilename($fileName);

    /**
     * Sets the Content-Disposition to either inline or "attachment" (default).
     * @param string $disposition The disposition DownloadResponse::DISPOSITION_ATTACHMENT(default) or DownloadResponse::DISPOSITION_INLINE
     * @return void
     */
    public function setDisposition($disposition);

}

?>