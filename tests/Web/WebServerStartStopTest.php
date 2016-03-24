<?php

namespace Test\WebX\Web;

use WebX\Ioc\Util\Bootstrap;
use WebX\Routes\Util\RoutesBootstrap;

class WebServerStartStopTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var WebServer
     */
    private static $server;

    public static function setUpBeforeClass() {
        self::$server = new WebServer(__DIR__ . "/web_echo.php");
    }

    public static function tearDownAfterClass() {
        self::$server->stop();
    }


    public function testSartStop() {
        $content = self::$server->get_contents();
        $this->assertEquals("hello",$content);
    }
}