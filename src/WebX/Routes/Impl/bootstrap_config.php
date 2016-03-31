<?php

    return [
        "responses" => [
            "WebX\\Routes\\Api\\Response" => [
                "class" => "WebX\\Routes\\Impl\\ResponseImpl"
            ],
            "WebX\\Routes\\Api\\Responses\\ContentResponse" => [
                "class" => "WebX\\Routes\\Impl\\Responses\\ContentResponseImpl"
            ],
            "WebX\\Routes\\Api\\Responses\\JsonResponse" => [
                "class" => "WebX\\Routes\\Impl\\Responses\\JsonResponseImpl",
                "config" => [
                    "prettyPrint" => true
                ]
            ],
            "WebX\\Routes\\Api\\Responses\\RedirectResponse" => [
                "class" => "WebX\\Routes\\Impl\\Responses\\RedirectResponseImpl"
            ],
            "WebX\\Routes\\Api\\Responses\\TemplateResponse" => [
                "class" => "WebX\\Routes\\Impl\\Responses\\TemplateResponseImpl",
                "config" => [
                    "templatesDir" => "templates",
                    "suffix" =>  "twig"
                ]
            ]
        ],
        "controllers" => [],
        "ini" => [
            "dev" => [
                ["display_errors",1],
                ["error_reporting",-1]
            ],
            "prod" => [
                ["display_errors",0],
                ["error_reporting",0]
            ]
        ],
        "deploy" => [
            "directories" => [
                "system" => "../",
                "public" => "public/",
                "config" => "config/"
            ]
        ]
    ];
?>