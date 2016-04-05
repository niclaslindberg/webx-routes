<?php

    return [
        "responseTypes" => [
            "WebX\\Routes\\Api\\ResponseTypes\\RawResponseType" => [
                "class" => "WebX\\Routes\\Impl\\ResponseTypes\\RawResponseTypeImpl"
            ],
            "WebX\\Routes\\Api\\ResponseTypes\\JsonResponseType" => [
                "class" => "WebX\\Routes\\Impl\\ResponseTypes\\JsonResponseTypeImpl",
                "config" => [
                    "prettyPrint" => true
                ]
            ],
            "WebX\\Routes\\Api\\ResponseTypes\\RedirectResponseType" => [
                "class" => "WebX\\Routes\\Impl\\ResponseTypes\\RedirectResponseTypeImpl"
            ],
            "WebX\\Routes\\Api\\ResponseTypes\\DownloadResponseType" => [
                "class" => "WebX\\Routes\\Impl\\ResponseTypes\\DownloadResponseTypeImpl"
            ],
            "WebX\\Routes\\Api\\ResponseTypes\\StreamResponseType" => [
                "class" => "WebX\\Routes\\Impl\\ResponseTypes\\StreamResponseTypeImpl"
            ],
            "WebX\\Routes\\Api\\ResponseTypes\\TemplateResponseType" => [
                "class" => "WebX\\Routes\\Impl\\ResponseTypes\\TemplateResponseTypeImpl",
                "config" => [
                    "templatesDir" => "templates",
                    "suffix" =>  "twig"
                ]
            ]
        ],
        "ini" => [
            "dev" => [
                ["display_errors",1],
                ["error_reporting",-1]
            ],
            "prod" => [
                ["display_errors",0],
                ["error_reporting",0]
            ]
        ]
    ];
?>