<?php

namespace WebX\Routes\Extras\Template\Api;

use WebX\Routes\Api\View;

/**
 * Interface TemplateView
 * Super interface of all template view integrations. 
 * @package WebX\Routes\Extras\Template\Api
 */
interface TemplateView extends View {

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

    /**
     * * Sets data in context
     * @param mixed $data
     * @param string|null $path
     * @return TemplateView
     */
    public function data($data, $path = null);
}