<?php

namespace WebX\Routes\Extras\Twig\Api;

use Twig_Environment;
use WebX\Routes\Extras\Template\Api\TemplateView;


interface TwigFactory {

    /**
     * Returns the configured twig engine
     * @return Twig_Environment
     */
    public function twig();
}