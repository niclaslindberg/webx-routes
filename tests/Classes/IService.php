<?php

namespace Test\WebX\Classes;


interface IService
{
    public function returnSame($in);

    /**
     * @param $v
     * @return mixed
     */
    public function setValue($v);

    /**
     * Returns the previously set value
     * @return mixed
     */
    public function getValue();
}