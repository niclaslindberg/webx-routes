<?php


use WebX\Routes\Api\Configurator;
use WebX\Routes\Extras\Twig\Impl\TwigViewImpl;


return function(Configurator $configurator) {
    $configurator->register(TwigViewImpl::class);
};