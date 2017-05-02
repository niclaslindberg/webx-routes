<?php
/**
 * User: niclas
 * Date: 5/2/17
 * Time: 9:14 PM
 */

namespace WebX\Routes\Extras\Settings\Impl;


use WebX\Routes\Extras\Settings\Api\SettingsReader;
use WebX\Routes\Impl\ReaderImpl;

class SettingsReaderImpl extends ReaderImpl implements SettingsReader {

    public function __construct(array $array = null) {
        parent::__construct($array);
    }

}