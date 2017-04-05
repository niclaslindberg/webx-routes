<?php


namespace WebX\Routes\Api\ResponseTypes;
use WebX\Routes\Api\ResponseRenderer;


interface RedirectRenderer extends ResponseRenderer
{

    const TYPE_PERMANENT = 302;
    const TYPE_TEMPORARY = 301;

    /**
     * @param string $template
     * @return RedirectRenderer
     */
    public function setType($type);

    /**
     * @param string $url
     * @return RedirectRenderer
     */
    public function setUrl($url);
}