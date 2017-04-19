<?php

namespace WebX\Routes\Api;

use WebX\Routes\Api\ResponseBody;
use WebX\Routes\Api\ResponseHeader;

interface View
{

    /**
     * @param ResponseHeader $responseHeader
     * @return void
     */
    public function renderHead(ResponseHeader $responseHeader, $data);

    /**
     * @param ResponseBody $responseBody
     * @return void
     */
    public function renderBody(ResponseBody $responseBody, $data);

}