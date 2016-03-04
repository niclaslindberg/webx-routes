<?php

namespace WebX\Route\Api\Responses;

use WebX\Route\Api\Response;

interface StreamResponse extends Response
{

    /**
     * Registers the callback to be used to generate chunked stream content.
     * The return value of the callback will be echoed to the output stream. The callback will be repeatedly called until it returns the value <code>NULL</code>.
     * @param \Closure $content Callback to be invoked for each chunk.
     * @return void
     */
    public function setContent(\Closure $content);

    public function setContentType($contentType);

}


?>