<?php

namespace Test\WebX\Internal;


use WebX\Routes\Impl\ConfigurationImpl;

/**
 * Class ConfigurationTest
 * @package Test\WebX\Internal
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{

    public function testConfigurationMergedIntRead() {

        $a = [
            "l1"=>[
               "l2" => [
                   "a" => 1,
                   "b" => 2
               ]
            ]
        ];
        $b = [
            "l1" => [
                "l2" => [
                    "c" => 3
                ]
            ]
        ];

        $configuration = new ConfigurationImpl($a);
        $configuration->pushArray($b);

        $this->assertEquals(1,$configuration->asInt("l1.l2.a"));
        $this->assertEquals(2,$configuration->asInt("l1.l2.b"));
        $this->assertEquals(3,$configuration->asInt("l1.l2.c"));


    }

    public function testConfigurationMergedHigherLevel() {

        $a = [
            "l1"=>[
                "l2" => [
                    "a" => 1,
                    "b" => 2
                ]
            ]
        ];
        $b = [
            "l1" => [
                "l2" => [
                    "c" => 3,
                    "b" => 4
                ]
            ]
        ];

        $configuration = new ConfigurationImpl($a);
        $configuration->pushArray($b);

        $reader = $configuration->asReader("l1.l2");

        $this->assertEquals(1,$reader->asInt("a"));
        $this->assertEquals(4,$reader->asInt("b"));
        $this->assertEquals(3,$reader->asInt("c"));


    }

}