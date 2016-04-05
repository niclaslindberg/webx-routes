<?php

namespace Test\WebX\Web;

use WebX\Ioc\Util\Bootstrap;
use WebX\Routes\Util\RoutesBootstrap;

class WebServerEchoTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var WebServer
     */
    private static $server;

    public static function setUpBeforeClass() {
        self::$server = new WebServer(__DIR__ . "/AppEcho/public/index.php");
        #sleep(5000);
    }

    public static function tearDownAfterClass() {
        self::$server->stop();
    }

    public function test() {
        $server = self::$server;

        $r11 = $server->get_contents("/abc");
        $this->assertEquals("/abc", $r11);

    }
}