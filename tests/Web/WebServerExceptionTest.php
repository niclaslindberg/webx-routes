<?php

namespace Test\WebX\Web;

use WebX\Ioc\Util\Bootstrap;
use WebX\Routes\Util\RoutesBootstrap;

class WebServerSegmentException extends \PHPUnit_Framework_TestCase
{

    /**
     * @var WebServer
     */
    private static $server;

    public static function setUpBeforeClass() {
        self::$server = new WebServer(__DIR__ . "/AppException/index.php");
    }

    public static function tearDownAfterClass() {
        self::$server->stop();
    }

    public function testExceptions() {
        $server = self::$server;

        $r11 = $server->get_contents("/s/s");
        $this->assertEquals("handlerS:s.s", $r11);

        $r12 = $server->get_contents("/s/e");
        $this->assertEquals("handlerS:s.e", $r12);


    }
}