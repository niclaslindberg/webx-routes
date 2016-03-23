<?php

namespace WebX\Routes\Api;

interface ResourceLoader {


    /**
     * Adds an absolute or relative path to the beginning of the path list.
     * @param $path
     * @return void
     */
    public function prependPath($path);

    /**
     * Adds an absolute or relative path to the end of the path list.
     * @param $path
     * @return void
     */
    public function appendPath($path);

    /**
     * Returns the content of the file from the first found in all added paths.
     * @param $rel
     * @return string
     */
    public function load($rel);


    /**
     * @param $rel the relative path to resolve in the different paths.
     * @return string
     */
    public function absolutePath($rel);
}

?>