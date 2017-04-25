<?php

use Test\WebX\Classes\Service;
use WebX\Routes\Api\Configurator;

return function(Configurator $configurator) {
    $configurator->register(Service::class);
};