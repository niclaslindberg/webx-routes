<?php

namespace WebX\Routes\Utils;

use WebX\Routes\Impl\ReaderImpl;
use WebX\Routes\Api\Reader;
use WebX\Routes\Api\RoutesException;

final class ConfigReader {

    private function __construct() {}

    private static $readersByFile = [];

    /**
     * @param $file
     * @param bool $optional
     * @return Reader
     * @throws RoutesException
     */
    public static function create($file,$optional = false) {
        if(array_key_exists($file,self::$readersByFile)) {
            return self::$readersByFile[$file];
        }
        if(file_exists($file)) {
            if($content = file_get_contents($file)) {
                if(NULL!==($data = json_decode($content,true))) {
                    return self::$readersByFile[$file] = new ReaderImpl($data);
                } else {
                    throw new RoutesException("Bad Json in config-file '{$file}'");
                }
            }
        }
        if($optional) {
            return self::$readersByFile[$file] = new ReaderImpl([]);
        } else {
            throw new RoutesException("Missing config-file '{$file}'");
        }
    }
}