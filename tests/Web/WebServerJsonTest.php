<?php

namespace Test\WebX\Web;

use WebX\Ioc\Util\Bootstrap;
use WebX\Routes\Util\RoutesBootstrap;

class WebServerJsonTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var WebServer
     */
    private static $server;

    public static function setUpBeforeClass() {
       self::$server = new WebServer(__DIR__ . "/AppJson/index.php");
     #   sleep(5000);
    }

    public static function tearDownAfterClass() {
        self::$server->stop();
    }

    public function testJsonMixedFromRoutesAndView() {
        $server = self::$server;

        $response = $server->get_contents("/normal");
        $json = json_decode($response,true);

        $this->assertEquals("a",$json["a"]["a"]);
        $this->assertEquals("c",$json["a"]["b"]["c"]);

    }

    public function testMultipleRootArraysPass() {
        $server = self::$server;
        $response = $server->get_contents("/rootArray");
        $json = json_decode($response,true);
        $this->assertEquals("2",$json["b"]);
    }

    public function testScalar() {
        $server = self::$server;
        $response = $server->get_contents("/scalar");
        $json = json_decode($response,true);
        $this->assertEquals("1",$json);
    }
}