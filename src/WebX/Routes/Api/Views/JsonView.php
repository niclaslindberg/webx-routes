<?php

namespace WebX\Routes\Api\Views;
use WebX\Routes\Api\View;


interface JsonView extends View
{
    /**
     * @param int $parameters OR:ed Json Parameters @see json_encode
     * @return JsonView
     */
    public function setJsonParameters($parameters);

    /**
     * * Sets data in context
     * @param mixed $data
     * @param string|null $path
     * @return JsonView
     */
    public function setData($data, $path = null);

}