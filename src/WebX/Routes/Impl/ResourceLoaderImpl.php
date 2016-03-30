<?php
/**
 * User: niclas
 * Date: 3/23/16
 * Time: 4:31 PM
 */

namespace WebX\Routes\Impl;


use WebX\Routes\Api\ResourceLoader;
use WebX\Routes\Api\the;

class ResourceLoaderImpl implements ResourceLoader
{
    private $absolutePaths = [];

    public function rootPaths() {
        return $this->absolutePaths;
    }

    public function prependPath($absolutePath)
    {
        array_unshift($this->absolutePaths,$this->processPath($absolutePath));
    }

    public function appendPath($absolutePath)
    {
        array_push($this->absolutePaths,$this->processPath($absolutePath));
    }

    public function load($relPath)
    {
        if($completePath = $this->absolutePath($relPath)) {
            return file_get_contents($completePath);
        }
        return null;
    }

    public function absolutePath($relPath)
    {
        if($relPath) {
            $relPath = ltrim($relPath,"/");
            foreach($this->absolutePaths as $absolutePath) {
                $path = $absolutePath . $relPath;
                if(file_exists($path)) {
                    return $path;
                }
            }
        }
        return false;
    }


    private function processPath($path) {
        if($path) {
            return rtrim($path,"/") . "/";
        }
    }

}