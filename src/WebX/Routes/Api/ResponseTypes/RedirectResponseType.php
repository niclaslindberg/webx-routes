<?php


namespace WebX\Routes\Api\ResponseTypes;
use WebX\Routes\Api\ResponseType;


interface RedirectResponseType extends ResponseType
{

    const REDIRECT_TYPE_PERMANENT = "permanent";
    const REDIRECT_TYPE_TEMPORARY = "temporary";

    /**
     * @param string $template
     * @return RedirectResponseType
     */
    public function type($type);

    /**
     * @param string $url
     * @return RedirectResponseType
     */
    public function url($url);
}