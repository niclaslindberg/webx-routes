<?php
/**
 * User: niclas
 * Date: 2/4/16
 * Time: 5:47 PM
 */

namespace WebX\Routes\Api;


use WebX\Routes\Api\ResponseTypes\DownloadResponseType;
use WebX\Routes\Api\ResponseTypes\FileContentResponseType;
use WebX\Routes\Api\ResponseTypes\JsonResponseType;
use WebX\Routes\Api\ResponseTypes\RawResponseType;
use WebX\Routes\Api\ResponseTypes\RedirectResponseType;
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
    public function typeTemplate($id=null);

    /**
     * @return RawResponseType
     */
    public function typeRaw($data=null);

    /**
     * @param mixed $data Shortcut to Response::data()
     * @return JsonResponseType
     */
    public function typeJson($data=null);

    /**
     * @return DownloadResponseType
     */
    public function typeDownload();

    /**
     * @return RedirectResponseType
     */
    public function typeRedirect($url=null);

    /**
     * @param string $file Shortcut for FileContentResponseType::file (optional)
     * @return FileContentResponseType
     */
    public function typeFileContent($file=null);

    /**
     * The current type set for this reponse
     * @return ResponseType|null
     */
    public function currentResponseType();

    public function currentStatus();
}