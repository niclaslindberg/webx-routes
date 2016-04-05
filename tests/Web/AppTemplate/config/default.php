<?php

return [

    "responses" => [
        "WebX\\Routes\\Api\\ResponseTypes\\TemplateResponseType" => [
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