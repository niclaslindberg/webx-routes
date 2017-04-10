<?php


namespace WebX\Routes\Api\ResponseTypes;
use WebX\Routes\Api\View;


interface RedirectView extends View
{

    const TYPE_PERMANENT = 302;
    const TYPE_TEMPORARY = 301;

    /**
     * @param string $template
     * @return RedirectView
     */
    public function setType($type);

    /**
     * @param string $url
     * @return RedirectView
     */
    public function setUrl($url);
}