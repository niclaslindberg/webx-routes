<?php

use Test\WebX\Classes\IService;
use Test\WebX\Classes\Service;
use WebX\Routes\Api\SessionConfig;

return [

    "execute" => [
        function(SessionConfig $sessionConfig) {
            $sessionConfig->configure(10*60,"AA",true);
        }
    ]
];