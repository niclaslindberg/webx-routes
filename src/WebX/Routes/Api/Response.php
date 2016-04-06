<?php
/**
 * User: niclas
 * Date: 2/4/16
 * Time: 5:47 PM
 */

namespace WebX\Routes\Api;


use WebX\Routes\Api\ResponseTypes\JsonResponseType;
use WebX\Routes\Api\ResponseTypes\RawResponseType;
use WebX\Routes\Api\ResponseTypes\StreamResponseType;
use WebX\Routes\Api\ResponseTypes\TemplateResponseType;

interface Response
{
    public function header($name, $value);

    public function cookie($name, $value, $ttl=0, $path = "/");

    public function status($httpStatus, $message = null);

    /**
     * @param mixed $value
     * @param null $path '.' notated path of where in the data structure the value is stored.
     * @return void
     */
    public function data($value, $path = null);

    public function type(ResponseType $responseType);

    /**
     * @return TemplateResponseType
     */
    public function typeTemplate();

    /**
     * @return RawResponseType
     */
    public function typeRaw();

    /**
     * @return JsonResponseType
     */
    public function typeJson();

    /**
     * @return StreamResponseType
     */
    public function typeStream();

    /**
     * @return DownloadResponseType
     */
    public function typeDownload();

    /**
     * @return RedirectResponseType
     */
    public function typeRedirect();

}