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
     * @param array $data
     * @return TwigView
     */
    public function data(array $data);
}