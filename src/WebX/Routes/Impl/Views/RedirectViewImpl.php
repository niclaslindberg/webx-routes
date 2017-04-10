<?php
namespace WebX\Routes\Impl\ResponseTypes;

use WebX\Routes\Api\Configuration;
use WebX\Routes\Api\Request;
use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseBody;
use WebX\Routes\Api\ResponseHeader;
use WebX\Routes\Api\ResponseTypes\RedirectResponseType;
use WebX\Routes\Api\ResponseTypes\RedirectView;
use WebX\Routes\Api\ResponseWriter;

class RedirectViewImpl implements RedirectView
{
    /**
     * @var int
     */
    private $type = RedirectView::TYPE_TEMPORARY;

    /**
     * @var string
     */
    private $url;

    public function __construct() {}


    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }

    public function renderHead(ResponseHeader $responseHeader, $data) {
        $httpCode = $this->type === RedirectView::TYPE_TEMPORARY ? 302 : 301;
        $responseHeader->setStatus($httpCode);
        $responseHeader->addHeader("Location", $this->url);

    }

    public function renderBody(ResponseBody $responseBody, $data) {}

}