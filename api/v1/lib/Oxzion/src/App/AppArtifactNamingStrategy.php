<?php

namespace Oxzion\App;

class AppArtifactNamingStrategy {
    private static $listener = NULL;

    public static function setArtifactListener(AppArtifactListener $listener) {
        self::$listener = $listener;
    }

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
        $srcAppDir = self::getSourceDirectory($config) . self::makeAppDirectoryName($appData);
        if (self::$listener) {
            self::$listener->directoryNameCreated($srcAppDir);
        }
        return $srcAppDir;
    }

    private static function getDeployDirectory($config) {
        return $config['EOX_APP_DEPLOY_DIR'];
    }

    public static function getDeployAppDirectory($config, $appData) {
        $deployAppDir = self::getDeployDirectory($config) . self::makeAppDirectoryName($appData);
        if (self::$listener) {
            self::$listener->directoryNameCreated($deployAppDir);
        }
        return $deployAppDir;
    }

    private static function makeAppDirectoryName($appData) {
        return $appData['uuid'];
    }

    public static function getDatabaseName($appData) {
        $dbName = self::normalizeAppName($appData['name']) . 
            '___' . self::normalizeUuid($appData['uuid']);
        if (self::$listener) {
            self::$listener->databaseNameCreated($dbName);
        }
        return $dbName;
    }

    private static function normalizeUuid($uuid) {
        return str_replace('-', '', $uuid);
    }

    private static function normalizeAppName($appName) {
        $appName = str_replace(' ', '', $appName); //Remove space characters.
        return preg_replace('/[^A-Za-z0-9_]/', '', $appName, -1); // Removes special chars.
    }
}
