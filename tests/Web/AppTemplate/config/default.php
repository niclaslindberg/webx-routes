<?php

return [

    "responseTypes" => [
        "WebX\\Routes\\Api\\Views\\TemplateResponseType" => [
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