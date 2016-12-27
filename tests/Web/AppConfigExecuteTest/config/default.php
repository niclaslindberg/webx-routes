<?php

use Test\WebX\Classes\IService;
use Test\WebX\Classes\Service;

return [

    "ioc" => [
         "register" => [
             [Service::class]
        ]
    ],
    "execute" => [
        function(IService $service) {
            $service->setValue("HelloThereFromExecute");
        }
    ]
];