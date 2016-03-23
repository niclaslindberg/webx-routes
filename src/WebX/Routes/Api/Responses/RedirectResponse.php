<?php

namespace WebX\Routes\Api\Responses;

use WebX\Routes\Api\Response;

interface RedirectResponse extends Response {

    const REDIRECT_TEMPORARY = 0;
    const REDIRECT_PERMANENT = 1;

    public function setUrl($url, $type = RedirectResponse::REDIRECT_TEMPORARY);

}

?>