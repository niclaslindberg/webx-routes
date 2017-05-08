<?php

namespace WebX\Routes\Api;


interface WritableMap extends Map {
    /**
     * @param mixed $data
     * @param null|string|array $path Dot notated path of value in map
     * @return mixed
     */
    public function set($data,$path=null);


    /**
     * @return void
     */
    public function clear();
}