<?php


namespace WebX\Routes\Api\Views;
use WebX\Routes\Api\View;


interface RawView extends View {
    /**
     * @param $contentType
     * @return RawView
     */
    public function contentType($contentType);
}