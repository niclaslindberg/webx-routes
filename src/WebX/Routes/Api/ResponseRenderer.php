<?php
/**
 * User: niclas
 * Date: 2/4/16
 * Time: 5:47 PM
 */

namespace WebX\Routes\Api;


interface ResponseRenderer
{

    public function render(ResponseWriter $writer, $data);
}