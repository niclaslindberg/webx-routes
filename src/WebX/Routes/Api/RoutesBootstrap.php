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
     * @param array $options
     * Required values:
     * 'home' (string) relative path from $_SERVER['DOCUMENT_ROOT'] defaults to ".."
     * </code>
     */
    public final static function run(Closure $closure, array $options = null,$configuration = null) {
        try {
            $routes = new RoutesImpl($options);
            $routes->setView($routes->run($closure,$configuration));
            $routes->render();
        } catch(Exception $e) {
            if(function_exists("dd")) {
                echo($e);
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