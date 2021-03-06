<?php

namespace Test\WebX\Util;
use WebX\Routes\Api\Map;
use WebX\Routes\Impl\ArrayUtil;
use WebX\Routes\Impl\WritableMapImpl;
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

    public function testInsertIndexed() {

        $aMap = MapUtil::writable();
        $aMap->set("a",0);
        $aMap->set("b",1);

        $this->assertEquals("a",$aMap->asString(0));
        $this->assertEquals("a",$aMap->asString("0"));
        $this->assertEquals("b",$aMap->asString(1));
        $this->assertEquals("b",$aMap->asString("1"));

    }

    public function testDefaultMap() {
        $a = [
            "key" => ["v1","v2"]
        ];
        $aMap = MapUtil::readable($a);
        $this->assertNull($aMap->asMap("noKey"));
        $this->assertInstanceOf(Map::class,$aMap->asMap("noKey",[]));

    }

    public function testSetToNullMap() {
        $a = [
            "level1" => [
                "level2" => "value2",
                "level2a" => "value2a"
            ]
        ];
        $aMap = MapUtil::writable($a);
        $this->assertEquals("value2",$aMap->asString("level1.level2"));
        $aMap->delete("level1.level2");
        $this->assertNull($aMap->asString("level1.level2"));

        $this->assertEquals("value2a",$aMap->asString("level1.level2a"));
        $aMap->set(null,"level1.level2a");
        $this->assertNull($aMap->asString("level1.level2a"));

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


        $cMap = MapUtil::writable();
        $cMap->set("dummy",1);
        $this->assertEquals("dummy",$cMap->asAny(1));

    }

    public function testNumericInsertArray() {
            $map = new WritableMapImpl();
            $map->set("a","0.a");
            $map->set("b","1.a");

            $array = $map->raw();
            $this->assertEquals("a",$array[0]["a"]);
            $this->assertEquals("b",$array[1]["a"]);
    }

    public function testAsMap() {
        $a = ["a"=>"v1"];
        $b = ["b" => MapUtil::readable($a)];
        $a["map"] = $b;
        $aMap = MapUtil::readable($a);
        $this->assertInstanceOf(Map::class,$aMap->asMap("map"));
        $this->assertEquals("v1",$aMap->asString("a"));
    }

    public function testWritableDeleteNotExistsSingleLevel() {
        $map = MapUtil::writable([]);
        $map->delete("errors");
        $this->assertNull($map->asAny("errors"));
    }

    public function testWritableDeleteExistsDoubleLevel() {
        $map = MapUtil::writable();
        $map->delete("errors.type");
        //Should not break

        $map = MapUtil::writable([
            "errors" => [
                "test1" => 1,
                "test2" => 2
            ]
        ]);
        $map->delete("errors.test1");
        $this->assertEquals($map->asAny("errors.test2"),2);
        $this->assertNull($map->asAny("errors.test1"));
    }

}