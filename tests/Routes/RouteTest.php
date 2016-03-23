<?php

namespace Test\WebX\Routes;

use WebX\Ioc\Util\Bootstrap;
use WebX\Routes\Util\RoutesBootstrap;

class TestInit extends \PHPUnit_Framework_TestCase
{

    public function testSimpleResponse() {
        $app = RoutesBootstrap::createApp();
        $this->assertNotNull($app);
    }

}