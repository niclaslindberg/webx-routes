<?php

namespace WebX\Routes\Api;


interface ResponseRenderer
{

    /**
     * @param ResponseHeader $responseHeader
     * @return void
     */
    public function onHead(ResponseHeader $responseHeader);

    /**
     * @param ResponseBody $responseBody
     * @return void
     */
    public function onBody(ResponseBody $responseBody);

}