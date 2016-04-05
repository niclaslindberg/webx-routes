<?php


namespace WebX\Routes\Api\ResponseTypes;
use WebX\Routes\Api\ResponseType;

interface TemplateResponseType extends ResponseType
{

    /**
     * @param $template
     * @return TemplateResponseType
     */
    public function template($template);

    /**
     * @param $contentType
     * @return TemplateResponseType
     */
    public function contentType($contentType);
}