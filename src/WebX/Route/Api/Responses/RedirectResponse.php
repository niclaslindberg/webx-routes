<?php

namespace WebX\Route\Api\Responses;

use WebX\Route\Api\Response;

interface RedirectResponse extends Response {

    const REDIRECT_TEMPORARY = 0;
    const REDIRECT_PERMANENT = 1;

    public function setUrl($url, $type = RedirectResponse::REDIRECT_TEMPORARY);

}

?>