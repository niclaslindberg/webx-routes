<?php


namespace WebX\Routes\Api\Views;
use WebX\Routes\Api\View;


interface RawView extends View {


    /**
     * @param int|string $data
     * @return RawView
     */
    public function setData($data);

    /**
     * @param $contentType
     * @return RawView
     */
    public function contentType($contentType);
}