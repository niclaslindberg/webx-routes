<?php

namespace WebX\Route\Api\Responses;

interface TemplateResponse extends Response {


    public function setData($value, $path = null);

    public function setTemplate($template);

}

?>