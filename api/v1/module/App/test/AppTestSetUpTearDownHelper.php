<?php

namespace AppTest;

use Oxzion\App\AppArtifactListener;
use Exception;
use PDO;
use Oxzion\Utils\FileUtils;
use Oxzion\App\AppArtifactNamingStrategy;
use Symfony\Component\Yaml\Yaml;

class AppTestSetUpTearDownHelper implements AppArtifactListener {
    private $dbConfig;
    private $config;
    private $dbConnection = NULL;
    private $directoriesCreated = array();
    private $databasesCreated = array();

    function __construct($config) {
        AppArtifactNamingStrategy::setArtifactListener($this);
        $this->dbConfig = $config['db'];
        $this->config = $config;
    }

    //Begin AppArtifactListener interface implementation.
    public function directoryNameCreated(string $directoryPath) {
        if (!in_array($directoryPath, $this->directoriesCreated)) {
            $this->directoriesCreated[] = $directoryPath;
        }
    }

    public function databaseNameCreated(string $databaseName) {
        if (!in_array($databaseName, $this->databasesCreated)) {
            $this->databasesCreated[] = $databaseName;
        }
    }
    //End AppArtifactListener interface implementation.

    //Fetch a separate connection for database because - Connection used in the test
    //case runs in its own transaction. We don't want to use that connection for 
    //cleaning up the database.
    private function setupDatabaseConnection() {
        if (!is_null($this->dbConnection)) {
            return;
        }
        $dbConfig = $this->dbConfig;
        $dbConnection = new PDO($dbConfig['dsn'], $dbConfig['username'], $dbConfig['password']);
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->dbConnection = $dbConnection;
    }

    public function cleanDirectories() {
        foreach ($this->directoriesCreated as $dirPath) {
            try {
                if (file_exists($dirPath)) {
                    FileUtils::rmDir($dirPath);
                }
            }
            catch (Exception $e) {
                print('ERROR:' . $e->getMessage());
            }
        }
        $this->directoriesCreated = array(); //Clear the array.
    }

    public function cleanDatabase() {
        $this->setupDatabaseConnection();
        foreach ($this->databasesCreated as $dbName) {
            try {
                $this->dbConnection->exec("DROP DATABASE IF EXISTS " . $dbName);
            }
            catch (Exception $e) {
                print('ERROR:' . $e->getMessage());
            }
        }
        $this->databasesCreated = array(); //Clear the array.
    }

    private function removeSymlinks(string $dir) : void {
        if (DIRECTORY_SEPARATOR != $dir[strlen($dir)-1]) {
            $dir = $dir . DIRECTORY_SEPARATOR;
        }
        $files = scandir( $dir );
        foreach( $files as $file ) {
            if (('.' == $file) || ('..' == $file) || ('.gitignore' == $file[0])) {
                continue;
            }
            $fullPath = $dir . $file;
            if (is_link($fullPath)) {
                if (!unlink($fullPath)) {
                    throw new Exception("Failed to unlink ${fullPath}. Error:" . print_r(error_get_last(),true));
                }
            }
        }
    }

    private function removeSubDirectories(string $dir, $exclude = null) : void {
        if (DIRECTORY_SEPARATOR != $dir[strlen($dir)-1]) {
            $dir = $dir . DIRECTORY_SEPARATOR;
        }
        $files = scandir( $dir );
        foreach( $files as $file ) {
            if (('.' == $file) || ('..' == $file)) {
                continue;
            }
            if (!is_null($exclude) && in_array($file, $exclude)) {
                continue;
            }
            $fullPath = $dir . $file;
            FileUtils::rmDir($dir . '/' . $file);
        }
    }

    private function cleanViewAppsDir() {
        $this->removeSubDirectories(__DIR__ . '/sampleapp/view/apps/', 
            ['.gitignore', 'README.md']); //Don't delete these files.
        $this->removeSymlinks($this->config['APPS_FOLDER']);
        $dirsToDelete = [
            __DIR__ . '/sampleapp/view/gui', 
            __DIR__ . '/sampleapp/content/workflows',
            __DIR__ . '/sampleapp/test'
        ];
        foreach ($dirsToDelete as $dir) {
            if (file_exists($dir)) {
                FileUtils::rmDir($dir);
            }
        }
        $filesToDelete = [
            __DIR__ . '/sampleapp/.env.sample', 
            __DIR__ . '/sampleapp/Readme.md', 
            __DIR__ . '/sampleapp/composer.json', 
            __DIR__ . '/sampleapp/content/entity/Readme', 
            __DIR__ . '/sampleapp/content/pages/Readme',
            __DIR__ . '/sampleapp/data/delegate/Readme',
            __DIR__ . '/sampleapp/data/template/Readme',
            __DIR__ . '/sampleapp/phpunit',
            __DIR__ . '/sampleapp/phpunit.xml'
        ];
        foreach ($filesToDelete as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }

    private function clearAppDescriptor() {
        $file = __DIR__ . '/sampleapp/application.yml';
        if (file_exists($file)) {
            if (!unlink($file)) {
                throw new Exception('Could not Delete File: ' . 
                    $file . 'Error:' . print_r(error_get_last(), true));
            }
        }
    }

    public function setupAppDescriptor(string $descriptor) {
        if (!copy(__DIR__ . '/sampleapp/' . $descriptor, 
            __DIR__ . '/sampleapp/application.yml')) {
                throw new Exception('Could not Delete File: ' . print_r(error_get_last(), true));
        }
    }

    public function setupAppInSourceLocation(string $descriptor){
        $this->setupAppDescriptor($descriptor);
        $path = __DIR__ . '/sampleapp';
        $yaml = Yaml::parse(file_get_contents($path.'/application.yml'));
        $appId = $yaml['app']['uuid'];
        $appSourceDir = AppArtifactNamingStrategy::getSourceAppDirectory($this->config, ['uuid' => $appId]);
        $appSourceDir;
        if (!file_exists($appSourceDir)) {
            FileUtils::createDirectory($appSourceDir);
        }
        FileUtils::copyDir($path, $appSourceDir);
    }

    public function cleanAll() {
        $this->cleanDirectories();
        $this->cleanDatabase();
        $this->cleanViewAppsDir();
        $this->clearAppDescriptor();
    }
}

?>
