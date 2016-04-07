<?php

namespace Test\WebX\Web;

use WebX\Ioc\Util\Bootstrap;
use WebX\Routes\Util\RoutesBootstrap;

class WebServerEchoTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var WebServer
     */
    private static $server;

    public static function setUpBeforeClass() {
        self::$server = new WebServer(__DIR__ . "/AppEcho/public/index.php");
        #sleep(5000);
    }

    public static function tearDownAfterClass() {
        self::$server->stop();
    }

    public function testRequestReaderQueryParameter() {
        $server = self::$server;

        $r11 = $server->get_contents("/queryParameter?param=xyz");
        $this->assertEquals("xyz", $r11);

    }


    public function test() {
        $server = self::$server;

        $r11 = $server->get_contents("/url/abc");
        $this->assertEquals("/url/abc", $r11);

    }


}