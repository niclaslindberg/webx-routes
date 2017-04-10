<?php


namespace WebX\Routes\Api\ResponseTypes;
use WebX\Routes\Api\View;

interface TemplateView extends View
{

    /**
     * The id of the template to be loaded.
     * @param $template
     * @return TemplateView
     */
    public function id($template);

    /**
     * @param $contentType
     * @return TemplateView
     */
    public function contentType($contentType);

}