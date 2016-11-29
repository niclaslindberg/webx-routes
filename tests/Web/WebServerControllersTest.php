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

    public function testFullClassName() {
        $server = self::$server;
        $r11 = $server->get_contents("/fullClassName");
        $this->assertEquals("1", $r11);

    }

    public function testControllerName() {
        $server = self::$server;
        $r11 = $server->get_contents("/controller");
        $this->assertEquals("2", $r11);

    }

    public function testControllerNameWithLastNameSpacePart() {
        $server = self::$server;
        $r11 = $server->get_contents("/lastNamespaceAndcontroller");
        $this->assertEquals("3", $r11);

    }

    public function testControllerNameWithoutMethod() {
        $server = self::$server;
        $r11 = $server->get_contents("/path4/test4");
        $this->assertEquals("4", $r11);

    }
    public function testControllerNameWithNonExistingMethod() {
        $server = self::$server;
        $r11 = $server->get_contents("/path4/test5");
        $this->assertEquals(404,$server->statusCode());
    }

    public function testControllerNameWithIndexMethod() {
        $server = self::$server;
        $r11 = $server->get_contents("/path4");
        $this->assertEquals("index",$r11);
    }

}