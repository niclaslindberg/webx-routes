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

    public function test() {
        $server = self::$server;
        $response = $server->get_contents("/viaResponseType");
        $this->assertEquals("a",$response);

    }

    public function testViaResponse() {
        $server = self::$server;
        $response = $server->get_contents("/viaResponse");
        $this->assertEquals("b",$response);
    }

    public function testEmpty() {
        $server = self::$server;
        $response = $server->get_contents("/emptyTemplate");
        $this->assertEquals("emptyresult",$response);
    }
}