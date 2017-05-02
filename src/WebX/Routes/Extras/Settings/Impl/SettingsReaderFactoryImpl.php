<?php

namespace WebX\Routes\Extras\Settings\Impl;

use WebX\Routes\Extras\Settings\Api\SettingsException;
use WebX\Routes\Extras\Settings\Api\SettingsReaderFactory;

class SettingsReaderFactoryImpl implements SettingsReaderFactory {

    public function __construct() {}

    private $readersByFile = [];

    public function create($file,$optional = false) {
        if(array_key_exists($file,$this->readersByFile)) {
            return $this->readersByFile[$file];
        }
        if(file_exists($file)) {
            if($content = file_get_contents($file)) {
                if(NULL!==($data = json_decode($content,true))) {
                    return $this->readersByFile[$file] = new SettingsReaderImpl($data);
                } else {
                    throw new SettingsException("Bad Json in config-file '{$file}'");
                }
            }
        }
        if($optional) {
            return $this->readersByFile[$file] = new SettingsReaderImpl([]);
        } else {
            throw new SettingsException("Missing config-file '{$file}'");
        }
    }
}