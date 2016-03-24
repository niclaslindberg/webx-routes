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
        self::$server = new WebServer(__DIR__ . "/web_segment.php");
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
    }
}