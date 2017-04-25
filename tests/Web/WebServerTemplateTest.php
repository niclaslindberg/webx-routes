<?php

namespace Test\WebX\Web;

use WebX\Ioc\Util\Bootstrap;
use WebX\Routes\Util\RoutesBootstrap;

class WebServerTemplateTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var WebServer
     */
    private static $server;

    public static function setUpBeforeClass() {
       self::$server = new WebServer(__DIR__ . "/AppTemplate/index.php");
       #sleep(5000);
    }

    public static function tearDownAfterClass() {
        self::$server->stop();

    }

    public function testValue() {
        $server = self::$server;
        $response = $server->get_contents("/theValue");
        $this->assertEquals("theValue",$response);

    }

    public function testEmpty() {
        $server = self::$server;
        $response = $server->get_contents("/");
        $this->assertEquals("emptyresult",$response);
    }
}