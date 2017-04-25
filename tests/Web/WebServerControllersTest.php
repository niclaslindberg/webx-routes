<?php

namespace Test\WebX\Web;

use WebX\Ioc\Util\Bootstrap;
use WebX\Routes\Util\RoutesBootstrap;

class WebServerControllersTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var WebServer
     */
    private static $server;

    public static function setUpBeforeClass() {
        self::$server = new WebServer(__DIR__ . "/AppController/index.php");
      # sleep(5000);
    }

    public static function tearDownAfterClass() {
        self::$server->stop();
    }

    public function testControllerAIndex() {
        $server = self::$server;
        $r11 = $server->get_contents("/controllerA");
        $this->assertEquals("Index", $r11);

    }

    public function testControllerANamedMethod() {
        $server = self::$server;
        $r11 = $server->get_contents("/controllerA/test1");
        $this->assertEquals("Test1", $r11);

    }

    public function testControllerANamedMethodWithParam() {
        $server = self::$server;
        $r11 = $server->get_contents("/controllerA/test2/abc");
        $this->assertEquals("Test2:abc", $r11);

    }

}