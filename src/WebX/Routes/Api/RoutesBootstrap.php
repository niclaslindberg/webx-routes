<?php

namespace WebX\Routes\Api;

use Closure;
use Exception;
use WebX\Routes\Impl\AppImpl;
use WebX\Routes\Impl\RoutesImpl;

class RoutesBootstrap {

    private function __construct(){}

    /**
     * @param Closure|array $action
     * @param array $config
     * <code>
     * Required values:
     * 'home' (string) relative path from $_SERVER['DOCUMENT_ROOT'] defaults to ".."
     * </code>
     * @return void
     */
    public final static function run($action, array $config = null) {
        try {
            $routes = new RoutesImpl($config);
            $routes->initialize($action);
            $routes->render();
        } catch(Exception $e) {
            if(function_exists("dd")) {
                dd($e);
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