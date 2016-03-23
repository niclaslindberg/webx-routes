<?php

namespace WebX\Routes\Api\Responses;

use WebX\Routes\Api\Response;

interface TemplateResponse extends Response {


    public function setData($value, $path = null);

    public function setTemplate($template);

}

?>