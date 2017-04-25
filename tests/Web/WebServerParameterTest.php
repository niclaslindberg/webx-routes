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

    public function testParameterToClosure() {
        $server = self::$server;
        $response = $server->get_contents("/test1/hello");
        $this->assertEquals("hello",$response);
    }


}