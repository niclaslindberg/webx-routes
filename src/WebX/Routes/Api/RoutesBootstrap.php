<?php

namespace WebX\Routes\Api;

use Closure;
use Exception;
use WebX\Routes\Impl\AppImpl;
use WebX\Routes\Impl\RoutesImpl;

class RoutesBootstrap {

    private function __construct(){}

    /**
     * @param Closure $closure the initial action
     * @param array|string|null $configuration
     * @param mixed|mixed[] $options
     * Required values:
     * 'home' (string) relative path from $_SERVER['DOCUMENT_ROOT'] defaults to ".."
     * </code>
     */
    public final static function run(Closure $closure, $configuration = null, array $parameters = null, array $options = null, array $optionFiles = null) {
        try {
            $routes = new RoutesImpl($options, $optionFiles);
            $routes->runAction(true,$closure,$configuration,$parameters ?: []);
            $routes->render();
        } catch(Exception $e) {
            if(function_exists("dd")) {
                var_dump($e);
            } else {
                header("Content-Type: text/html; charset=utf-8");
                echo("<html><body>");
                echo(sprintf("<h1>Uncaught exception:%s [%s]</h1>" , $e->getMessage() , get_class($e)));
                var_dump($e);
                echo("</body></html>");
            }
        }
    }
}

?>