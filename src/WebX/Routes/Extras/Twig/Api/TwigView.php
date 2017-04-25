<?php


namespace WebX\Routes\Extras\Twig\Api;

use WebX\Routes\Api\View;


interface TwigView extends View {

    /**
     * The id of the template to be loaded.
     * @param $template
     * @return TwigView
     */
    public function id($template);

    /**
     * @param $contentType
     * @return TwigView
     */
    public function contentType($contentType);

    /**
     * * Sets data in context
     * @param mixed $data
     * @param string|null $path
     * @return TwigView
     */
    public function data($data, $path = null);
}