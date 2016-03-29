<?php

namespace Test\WebX\Web;

use WebX\Ioc\Util\Bootstrap;
use WebX\Routes\Util\RoutesBootstrap;

class WebServerSegmentTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var WebServer
     */
    private static $server;

    public static function setUpBeforeClass() {
        self::$server = new WebServer(__DIR__ . "/AppSegment/index.php");
   #     sleep(5000);
    }

    public static function tearDownAfterClass() {
        self::$server->stop();
    }

    public function testCallSegmentOnDifferentLevels() {
        $server = self::$server;

        $r11 = $server->get_contents("/1/1");
        $this->assertEquals("1.1", $r11);

        $r12 = $server->get_contents("/1/2");
        $this->assertEquals("1.2", $r12);

        $r21 = $server->get_contents("/2/1");
        $this->assertEquals("2.1", $r21);

        $r22 = $server->get_contents("/2/2");
        $this->assertEquals("2.2", $r22);

        $r3 = $server->get_contents("/3");
        $this->assertEquals("3", $r3);

        $r4 = $server->get_contents("/something_not_existing");
        $this->assertEquals("void", $r4);

        $r1 = $server->get_contents("/1");
        $this->assertEquals("void", $r1); //Should continue to onAlways (no response generated in route for "1")

    }
}