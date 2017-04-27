<?php

namespace Test\WebX\Web;

use WebX\Ioc\Util\Bootstrap;
use WebX\Routes\Util\RoutesBootstrap;

class WebServerParameterOnRootTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var WebServer
     */
    private static $server;

    public static function setUpBeforeClass() {
       self::$server = new WebServer(__DIR__ . "/AppParameterOnRoot/index.php");
       #sleep(5000);
    }

    public static function tearDownAfterClass() {
        self::$server->stop();

    }

    public function testParameterOneParam() {
        $server = self::$server;
        $response = $server->get_contents("/test1");
        $data = json_decode($response,true);
        $this->assertEquals("test1",$data["p1"]);
        $this->assertNull($data["p2"]);
        $this->assertNull($data["p3"]);
    }

    public function testParameterTwoParams() {
        $server = self::$server;
        $response = $server->get_contents("/test1/test2");
        $data = json_decode($response,true);
        $this->assertEquals("test1",$data["p1"]);
        $this->assertEquals("test2",$data["p2"]);
        $this->assertNull($data["p3"]);
    }

    public function testParameterThreeParams() {
        $server = self::$server;
        $response = $server->get_contents("/test1/test2/test3");
        $data = json_decode($response,true);
        $this->assertEquals("test1",$data["p1"]);
        $this->assertEquals("test2",$data["p2"]);
        $this->assertEquals("test3",$data["p3"]);
    }

}