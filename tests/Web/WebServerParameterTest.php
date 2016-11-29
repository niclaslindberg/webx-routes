<?php

namespace Test\WebX\Web;

use WebX\Ioc\Util\Bootstrap;
use WebX\Routes\Util\RoutesBootstrap;

class WebServerParameterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var WebServer
     */
    private static $server;

    public static function setUpBeforeClass() {
       self::$server = new WebServer(__DIR__ . "/AppParameter/index.php");
       #sleep(5000);
    }

    public static function tearDownAfterClass() {
        self::$server->stop();

    }

    public function testNoNull() {
        $server = self::$server;
        $response = $server->get_contents("/test1/param1/param2/param3");
        $this->assertEquals("param1-param2-param3",$response);
    }

    public function testDefaultWithMissing() {
        $server = self::$server;
        $response = $server->get_contents("/test2/param1/param2");
        $this->assertEquals("param1-param2-default",$response);
    }

    public function testDefaultWithOverride() {
        $server = self::$server;
        $response = $server->get_contents("/test2/param1/param2/override");
        $this->assertEquals("param1-param2-override",$response);
    }

    public function testDefaulNulltWithoutOverride() {
        $server = self::$server;
        $response = $server->get_contents("/test3/param1/param2");
        $this->assertEquals("param1-param2-",$response);
    }

}