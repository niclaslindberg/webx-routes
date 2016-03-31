<?php

return [

    "responses" => [
        "WebX\\Routes\\Api\\Responses\\TemplateResponse" => [
            "config" => [
                "configurator" => function(Twig_Environment $twig) {
                    $lexer = new Twig_Lexer($twig, array(
                        'tag_variable'  => array('{{{', '}}}'),
                    ));
                    $twig->setLexer($lexer);
                }
            ]
        ]
    ]

];