<?php


use WebX\Routes\Api\Configurator;
use WebX\Routes\Api\Routes;
use WebX\Routes\Extras\Settings\Impl\SettingsReaderFactoryImpl;

return function(Configurator $configurator,Routes $routes) {
    $factory = new SettingsReaderFactoryImpl();
    $configurator->register($factory->create($routes->resourcePath("config.json")));
};