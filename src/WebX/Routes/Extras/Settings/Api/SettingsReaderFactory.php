<?php

namespace WebX\Routes\Extras\Settings\Api;


interface SettingsReaderFactory {
    /**
     * @param $file
     * @param bool $optional
     * @return SettingsReader
     * @throws SettingsException
     */
    public function create($file, $optional = false);
}