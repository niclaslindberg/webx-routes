<?php


namespace WebX\Routes\Api\ResponseTypes;
use WebX\Routes\Api\ResponseType;

interface TemplateResponseType extends ResponseType
{

    /**
     * The id of the template to be loaded.
     * @param $template
     * @return TemplateResponseType
     */
    public function id($template);

    /**
     * @param $contentType
     * @return TemplateResponseType
     */
    public function contentType($contentType);

    /**
     * Sets a prefix to be prepended to the id before loading the template.
     * @param $prefix
     * @return TemplateResponseType
     */
    public function prefix($prefix);

}