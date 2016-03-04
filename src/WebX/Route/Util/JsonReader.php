<?php

namespace WebX\Route\Util;



class JsonReader {

    public static function readJsonFile($path) {
        if(file_exists($path)) {
            if(NULL!== ($json = json_decode(file_get_contents($path),TRUE))) {
                return $json;
            } else {
                throw new \Exception(sprintf("File %s does not contain valid JSON",$path));
            }
        } else {
            throw new \Exception(sprintf("File %s does not exist",$path));
        }

    }

}


?>