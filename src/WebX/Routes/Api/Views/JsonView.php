<?php

namespace WebX\Routes\Api\ResponseTypes;
use WebX\Routes\Api\View;


interface JsonView extends View
{
    /**
     * @param int $parameters OR:ed Json Parameters @see json_encode
     * @return void
     */
    public function setJsonParameters($parameters);
}