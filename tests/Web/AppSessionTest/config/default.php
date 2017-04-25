<?php

use WebX\Routes\Api\Configurator;

return function(Configurator $configurator) {
    $configurator->configureSession(null,10*60,"encryptionKeyDummy");
};
