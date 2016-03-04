<?php

    $config = [
        "responses" => [
            "\\WebX\\Route\\Api\\Responses\\Response" => [
                "class" => "\\WebX\\Route\\Api\\Responses\\ResponseImpl"
            ],
            "\\WebX\\Route\\Api\\Responses\\ContentResponse" => [
                "class" => "\\WebX\\Route\\Api\\Responses\\ContentResponseImpl"
            ],
            "\\WebX\\Route\\Api\\Responses\\JsonResponse" => [
                "class" => "\\WebX\\Route\\Api\\Responses\\JsonResponseImpl"
            ],
            "\\WebX\\Route\\Api\\Responses\\RedirectResponse" => [
                "class" => "\\WebX\\Route\\Api\\Responses\\RedirectResponseImpl"
            ],
            "\\WebX\\Route\\Api\\Responses\\TemplateResponse" => [
                "class" => "\\WebX\\Route\\Api\\Responses\\TemplateResponseImpl",
                "defefaultTemplate" => "default.twig",
                "templatePath" => "views"
            ]
        ]
    ];
?>