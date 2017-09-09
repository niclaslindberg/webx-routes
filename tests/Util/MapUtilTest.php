<?php

namespace Test\WebX\Util;
use WebX\Routes\Impl\ArrayUtil;
use WebX\Routes\Utils\MapUtil;


/**
 * Class ConfigurationTest
 * @package Test\WebX\Internal
 */
class MapUtilTest extends \PHPUnit_Framework_TestCase {


    public function testDotNotedWith0() {

        $a = [
            "key" => ["v1","v2"]
        ];
        $aMap = MapUtil::readable($a);

        $this->assertEquals("v1",$aMap->asString("key.0"));
        $this->assertEquals("v2",$aMap->asString("key.1"));

    }

    public function testNumericallIndexedArray() {

        $a = [
            ["a"=>1],
            ["b"=>2]
        ];
        $aMap = MapUtil::readable($a);

        $this->assertEquals(1,$aMap->asInt("0.a"));
        $this->assertEquals(2,$aMap->asInt("1.b"));

        $b = [
            "a",
            "b"
        ];
        $bMap = MapUtil::readable($b);
        $this->assertEquals("a",$bMap->asString("0"));
        $this->assertEquals("b",$bMap->asString(1));

    }

}