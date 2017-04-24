<?php

namespace Test\WebX\Internal;
use WebX\Routes\Impl\ArrayUtil;


/**
 * Class ConfigurationTest
 * @package Test\WebX\Internal
 */
class ArrayTest extends \PHPUnit_Framework_TestCase {

    public function testArrayMerge1() {
        $a = ["a"=>1];
        $b = ["b"=>2];

        $c = ArrayUtil::mergeRecursive($a,$b);
        $this->assertEquals(1,$c["a"]);
        $this->assertEquals(2,$c["b"]);
    }

    public function testArrayMerge2() {
        $a = ["a"=>[
                "b" => 1
            ]
        ];
        $b = ["b"=>2];

        $c = ArrayUtil::mergeRecursive($a,$b);
        $this->assertEquals(1,$c["a"]["b"]);
        $this->assertEquals(2,$c["b"]);
    }

    public function testArrayMerge3() {
        $a = ["a"=>[
            "b" => 2
            ]
        ];
        $b = ["a"=>[
            "c" => 3
        ]];

        $c = ArrayUtil::mergeRecursive($a,$b);
        $this->assertEquals(2,$c["a"]["b"]);
        $this->assertEquals(3,$c["a"]["c"]);
    }

    public function testArrayMerge4() {
        $a = "a";
        $b = "b";

        $c = ArrayUtil::mergeRecursive($a,$b);
        $this->assertEquals("b",$c);
    }

    public function testArrayMerge5() {
        $a = "a";
        $b = null;

        $c = ArrayUtil::mergeRecursive($a,$b);
        $this->assertEquals("a",$c);
    }

    public function testArrayMerge6() {
        $a = [
            "a" => [
                "a" => "a",
                "b" => "b"
            ]
        ];
        $b = [
            "a" => [
                "b" => [
                    "c" => "c"
                ]
            ]
        ];
        $c = ArrayUtil::mergeRecursive($a,$b);
        $this->assertEquals("c",$c["a"]["b"]["c"]);
        $this->assertEquals("a",$c["a"]["a"]);
    }


}