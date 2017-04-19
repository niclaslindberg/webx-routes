<?php


use WebX\Routes\Api\Configurator;
use WebX\Routes\Impl\ResponseTypes\TwigViewImpl;

return function(Configurator $configurator) {
    $configurator->register(TwigViewImpl::class);
};