<?php

namespace Test\WebX\Route;

use WebX\Ioc\Util\Bootstrap;
use WebX\Route\Util\RoutesBootstrap;

class TestInit extends \PHPUnit_Framework_TestCase
{

    public function testSimpleResponse() {
        $app = RoutesBootstrap::createApp();
        $this->assertNotNull($app);
    }

}