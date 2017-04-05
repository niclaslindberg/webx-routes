<?php


namespace WebX\Routes\Api\ResponseTypes;
use WebX\Routes\Api\ResponseRenderer;

interface TemplateRenderer extends ResponseRenderer
{

    /**
     * The id of the template to be loaded.
     * @param $template
     * @return TemplateRenderer
     */
    public function id($template);

    /**
     * @param $contentType
     * @return TemplateRenderer
     */
    public function contentType($contentType);

}