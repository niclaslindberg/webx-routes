<?php

    return [
        "responseTypes" => [
            "WebX\\Routes\\Api\\Views\\RawResponseType" => [
                "class" => "WebX\\Routes\\Impl\\Views\\RawResponseTypeImpl"
            ],
            "WebX\\Routes\\Api\\Views\\JsonResponseType" => [
                "class" => "WebX\\Routes\\Impl\\Views\\JsonResponseTypeImpl",
                "config" => [
                    "prettyPrint" => true
                ]
            ],
            "WebX\\Routes\\Api\\Views\\RedirectResponseType" => [
                "class" => "WebX\\Routes\\Impl\\Views\\RedirectResponseTypeImpl"
            ],
            "WebX\\Routes\\Api\\Views\\FileContentResponseType" => [
                "class" => "WebX\\Routes\\Impl\\Views\\FileContentResponseTypeImpl"
            ],
            "WebX\\Routes\\Api\\Views\\DownloadResponseType" => [
                "class" => "WebX\\Routes\\Impl\\Views\\DownloadResponseTypeImpl"
            ],
            "WebX\\Routes\\Api\\Views\\TemplateResponseType" => [
                "class" => "WebX\\Routes\\Impl\\Views\\TemplateResponseTypeImpl",
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