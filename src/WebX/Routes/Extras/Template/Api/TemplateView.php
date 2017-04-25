<?php


namespace WebX\Routes\Extras\Template\Api;

use WebX\Routes\Api\View;


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