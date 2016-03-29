<?php

namespace Test\WebX\Classes;


class Service implements IService
{
    public function returnSame($in) {
        return $in;
    }
}