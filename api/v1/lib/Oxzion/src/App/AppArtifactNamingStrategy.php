<?php

namespace Oxzion\App;

use Exception;

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
        $uuid = $appData['uuid'];
        if (empty($uuid)) {
            throw new Exception('Application UUID is required.');
        }
        return $uuid;
    }

    public static function getDatabaseName($appData) {
        $appName = $appData['name'];
        if (empty($appName)) {
            throw new Exception('App name is required.');
        }
        $uuid = $appData['uuid'];
        if (empty($uuid)) {
            throw new Exception('Application UUID is required.');
        }
        $dbName = self::normalizeAppName($appName) . 
            '___' . self::normalizeUuid($uuid);
        if (self::$listener) {
            self::$listener->databaseNameCreated($dbName);
        }
        return $dbName;
    }

    private static function normalizeUuid($uuid) {
        if (empty($uuid)) {
            throw new Exception('Application UUID is required.');
        }
        return str_replace('-', '', $uuid);
    }

    public static function normalizeAppName($appName) {
        if (empty($appName)) {
            throw new Exception('App name is required.');
        }
        $appName = str_replace(' ', '', $appName); //Remove space characters.
        return preg_replace('/[^A-Za-z0-9_]/', '', $appName, -1); // Removes special chars.
    }
}
