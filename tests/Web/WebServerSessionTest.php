<?php

namespace Test\WebX\Web;

use WebX\Ioc\Util\Bootstrap;
use WebX\Routes\Util\RoutesBootstrap;

class WebServerSessionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var WebServer
     */
    private static $server;

    public static function setUpBeforeClass() {
        self::$server = new WebServer(__DIR__ . "/AppSessionTest/index.php");
       # sleep(500000);
    }

    public static function tearDownAfterClass() {
        self::$server->stop();
    }

    public function test1() {
        $server = self::$server;

        $r11 = $server->get_contents("/default/increment");
        $this->assertEquals("1", $r11);

        $r11 = $server->get_contents("/default/increment");
        $this->assertEquals("2", $r11);

    }
}