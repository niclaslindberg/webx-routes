<?php
namespace WebX\Routes\Impl\ResponseTypes;

use WebX\Routes\Api\Configuration;
use WebX\Routes\Api\Request;
use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseTypes\RedirectResponseType;
use WebX\Routes\Api\ResponseWriter;

class RedirectResponseTypeImpl implements RedirectResponseType
{
    /**
     * @var int
     */
    private $type = RedirectResponseType::REDIRECT_TYPE_TEMPORARY;

    /**
     * @var string
     */
    private $url;

    public function __construct() {
    }

    public function prepare(Request $request, Response $response)
    {
        $httpCode = $this->type === RedirectResponseType::REDIRECT_TYPE_TEMPORARY ? 302 : 301;
        $response->status($httpCode, $httpCode===301 ? "Moved Permanently" : "Moved Temporarily");
        $response->header("Location", $this->url);
    }

    public function render(Configuration $configuration, ResponseWriter $responseWriter, $data)
    {
    }

    public function type($type)
    {
        $this->type = $type;
        return $this;
    }

    public function url($url)
    {
        $this->url = $url;
        return $this;
    }
}