<?php
namespace WebX\Routes\Impl\Responses;

use WebX\Routes\Api\Responses\JsonResponse;
use WebX\Routes\Api\AbstractResponse;
use WebX\Routes\Api\Configuration;
use WebX\Routes\Api\Responses\RedirectResponse;
use WebX\Routes\Api\ResponseWriter;

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