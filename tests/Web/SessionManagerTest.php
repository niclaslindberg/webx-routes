<?php

namespace Test\WebX\Web;

use WebX\Ioc\Util\Bootstrap;
use WebX\Routes\Impl\RoutesImpl;
use WebX\Routes\Impl\SessionManagerImpl;
use WebX\Routes\Util\RoutesBootstrap;

class SessionManagerTest extends \PHPUnit_Framework_TestCase {

    public function testEmpty() {

        $text = "asdasdasd";
        $secret = "X";

        $encrytped = SessionManagerImpl::encrypt($text,$secret);

        $this->assertNotEquals($text,$encrytped);

        $decrypted = SessionManagerImpl::decrypt($encrytped,$secret);

        $this->assertEquals($text,$decrypted);

    }
}