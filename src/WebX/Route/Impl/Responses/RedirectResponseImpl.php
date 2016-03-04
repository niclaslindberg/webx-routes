<?php
namespace WebX\Route\Impl\Responses;

use WebX\Route\Api\Responses\JsonResponse;
use WebX\Route\Api\AbstractResponse;
use WebX\Route\Api\Configuration;
use WebX\Route\Api\Responses\RedirectResponse;
use WebX\Route\Api\ResponseWriter;

class RedirectResponseImpl extends AbstractResponse implements RedirectResponse
{
    public function __construct(ResponseHost $responseHost) {
        parent::__construct($responseHost);
    }


    public function setUrl($url, $type = RedirectResponse::REDIRECT_TEMPORARY)
    {
        if($type===RedirectResponse::REDIRECT_PERMANENT) {
            $this->addHeader("HTTP/1.1 301 Moved Permanently"); header("HTTP/1.1 301 Moved Permanently");
        }
        $this->addHeader("Location",$url);
    }

    public function generateContent(Configuration $configuration, ResponseWriter $responseWriter){}

}