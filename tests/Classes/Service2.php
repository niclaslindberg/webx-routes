<?php

namespace Test\WebX\Classes;


class Service2 implements IService
{

    private $v;

    public function returnSame($in) {
        return $in;
    }

    public function setValue($v)
    {
        $this->v = $v;
    }

    public function getValue()
    {
        return $this->v;
    }

}