<?php

namespace WebX\Routes\Api;


interface ResponseType
{
    public function prepare(Request $request, Response $response);

    public function render(Configuration $configuration, ResponseWriter $responseWriter, $data);

}