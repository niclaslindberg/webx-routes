<?php

namespace WebX\Routes\Api;

interface ResponseBody {

    /**
     * Writes raw output to the caller.
     * @param string $content
     * @return void
     */
    public function writeContent($content);

}

?>