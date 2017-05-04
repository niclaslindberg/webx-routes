<?php


namespace Test\WebX\Classes\Controllers;

use WebX\Routes\Api\Response;
use WebX\Routes\Api\ResponseTypes\RawResponseType;
use WebX\Routes\Api\Views\RawView;

class ControllerA {

    public function index(RawView $rawView,$param=null) {
        return $rawView->setData("Index{$param}");
    }

    public function test1(RawView $rawView) {
        return $rawView->setData("Test1");
    }

    public function test2(RawView $rawView,$param=null) {
        return $rawView->setData("Test2:" . $param);
    }
}

