<?php

namespace WebX\Routes\Impl\ResponseTypes;


class FilenameMimetypeFactory
{

    /**
     * @param $fileSuffix
     * @return null|string the mimetype for the
     */
    public static function findMimeType($fileName) {

        $map = require("mimetypes.php");
        $parts = explode(".",$fileName);

        $fileSuffix = end($parts);

        if(isset($map[$fileSuffix])) {
            return $map[$fileSuffix];
        }
        return null;
    }
}