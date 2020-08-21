<?php

namespace App\Service;

class AppDirectoryStrategy {
    private static function getTemplateDirectory($config) {
        return $config['DATA_FOLDER'];
    }

    public static function getTemplateAppDirectory($config) {
        return self::getTemplateDirectory($config) . '/eoxapps';
    }

    private static function getSourceDirectory($config) {
        return $config['EOX_APP_SOURCE_DIR'];
    }

    public static function getSourceAppDirectory($config, $appData) {
        return self::getSourceDirectory($config) . self::makeAppDirectoryName($appData);
    }

    private static function getDeployDirectory($config) {
        return $config['EOX_APP_DEPLOY_DIR'];
    }

    public static function getDeployAppDirectory($config, $appData) {
        return self::getDeployDirectory($config) . self::makeAppDirectoryName($appData);
    }

    private static function makeAppDirectoryName($appData) {
        return $appData['uuid'];
    }
}
