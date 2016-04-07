<?php

namespace WebX\Routes\Api;


interface ResponseType
{
    /**
     * @param Request $request
     * @param Response $response
     * @return bool if contains valid response.
     */
    public function prepare(Request $request, Response $response);

    public function render(Configuration $configuration, ResponseWriter $responseWriter, $data);

}