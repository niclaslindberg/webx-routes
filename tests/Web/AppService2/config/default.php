<?php

use Test\WebX\Classes\Service;
use Test\WebX\Classes\Service2;
use WebX\Routes\Api\Configurator;

return function(Configurator $configurator) {
    $a = new Service();
    $b = new Service2();
    $configurator->register($a);
    $configurator->register($b);
};