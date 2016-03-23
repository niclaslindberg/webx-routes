<?php

namespace WebX\Routes\Impl;


use WebX\Routes\Api\AbstractResponse;
use WebX\Routes\Api\Configuration;
use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseWriter;

class ResponseImpl extends AbstractResponse implements Response {


    public function generateContent(Configuration $configuration, ResponseWriter $responseWriter) {}

}
