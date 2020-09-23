<?php
namespace App\Service;

use Oxzion\Model\App;
use Oxzion\Model\AppTable;
use Exception;
use Oxzion\Service\AbstractService;
use Oxzion\Service\AppService;
use Oxzion\FileNotFoundException;
use Oxzion\DuplicateFileException;
use Oxzion\App\AppArtifactNamingStrategy;
use Oxzion\Utils\ZipUtils;
use Oxzion\Utils\ZipException;
use Oxzion\Utils\FileUtils;
use Oxzion\DuplicateEntityException;
use Oxzion\EntityNotFoundException;
use Oxzion\InvalidApplicationArchiveException;

/* 
 * This service is for managing application artifact files like form definition 
 * files and workflow definition files.
 */
class AppArtifactService extends AbstractService
{
    private $table;
    private $appService;

    public function __construct($config, $dbAdapter, AppTable $table, AppService $appService) {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->appService = $appService;
    }

    public function saveArtifact($appUuid, $artifactType) {
        $app = new App($this->table);
        $app->loadByUuid($appUuid);
        $appData = [
            'uuid' => $appUuid,
            'name' => $app->getProperty('name')
        ];
        $appSourceDir = AppArtifactNamingStrategy::getSourceAppDirectory($this->config, $appData);
        if (!file_exists($appSourceDir)) {
            throw new FileNotFoundException(
                "Application source directory is not found.", ['directory' => $appSourceDir]);
        }
        $contentDir = $appSourceDir . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR;
        switch($artifactType) {
            case 'workflow':
                $targetDir = $contentDir . 'workflows';
            break;
            case 'form':
                $targetDir = $contentDir . 'forms';
            break;
            default:
                throw new Exception("Unexpected artifact type ${artifactType}.");
        }
        $targetDir = $targetDir . DIRECTORY_SEPARATOR;
        //Check whether any of the uploaded file(s) already exist(s) on the server.
        foreach($_FILES as $key => $fileData) {
            if (UPLOAD_ERR_OK != $fileData['error']) {
                throw new Exception('File upload failed.');
            }
            $filePath = $targetDir . $fileData['name'];
            if (file_exists($filePath)) {
                unlink($filePath);
                // throw new DuplicateFileException("File already exists.", ['file' => $filePath]);
            }
        }
        //Move/copy the files to destination.
        foreach($_FILES as $key => $fileData) {
            $filePath = $targetDir . $fileData['name'];
            if (!rename($fileData['tmp_name'], $filePath)) {
                throw new Exception('Failed to move file ' . $fileData['tmp_name'] . ' to destination.');
            }
            // returning here cause $_FILES will always be of length 1
            return [
                "originalName" => $fileData['name'],
                "size" => filesize($filePath) 
            ];
        }
    }

    public function deleteArtifact($appUuid, $artifactType, $artifactName) {
        $app = new App($this->table);
        $app->loadByUuid($appUuid);
        $appData = [
            'uuid' => $appUuid,
            'name' => $app->getProperty('name')
        ];
        $appSourceDir = AppArtifactNamingStrategy::getSourceAppDirectory($this->config, $appData);
        if (!file_exists($appSourceDir)) {
            throw new FileNotFoundException("Application source directory is not found.",
            ['directory' => $appSourceDir]);
        }
        $filePath = $appSourceDir . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR;
        switch($artifactType) {
            case 'workflow':
                $filePath = $filePath . 'workflows';
            break;
            case 'form':
                $filePath = $filePath . 'forms';
            break;
            default:
                throw new Exception("Unexpected artifact type ${artifactType}.");
        }
        $filePath = $filePath . DIRECTORY_SEPARATOR . $artifactName;
        if (!file_exists($filePath)) {
            throw new FileNotFoundException("Artifact file is not found.", ['file' => $filePath]);
        }
        if (!unlink($filePath)) {
            throw new Exception("Failed to delete artifact type=${artifactType}, file ${artifactName}.");
        }
    }

    public function uploadAppArchive() {
        $fileData = array_shift($_FILES);
        if (!isset($fileData)) {
            throw new Exception('File not uploaded!');
        }
        if ($fileData['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('File upload failed.');
        }
        $tempDir = FileUtils::createTempDir();
        //Extract application.yml to a unique temporary directory.
        try {
            ZipUtils::unzip($fileData['tmp_name'], $tempDir, ['application.yml']);
        }
        catch (ZipException $e) {
            throw new InvalidApplicationArchiveException('Invalid application archive.', null, $e);
        }

        //Load application.yml from tempDir where it has been extracted from zip archive.
        $yamlData = AppService::loadAppDescriptor($tempDir);
        $appUuid = $yamlData['app']['uuid'];

        //Ensure app entity with the UUID given in $yamlData does not exist on the server.
        $app = new App($this->table);
        try {
            $app->loadByUuid($appUuid);
            throw new DuplicateEntityException('Application with this UUID already exists on the server.', 
                ['app' => $appUuid]);
        }
        catch (EntityNotFoundException $ignored) {
            //Entity does not exist. Continue.
        }

        //Using application.yml data extract input zip archive to app source directory.
        try {
            $appSourceDir = AppArtifactNamingStrategy::getSourceAppDirectory($this->config, $yamlData['app']);
        }
        catch (Exception $e) {
            throw new InvalidApplicationArchiveException('Invalid application archive.', null, $e);
        }
        if (file_exists($appSourceDir)) {
            throw new DuplicateEntityException('Application with this UUID already exists on the server.', 
                ['app' => $appUuid]);
        }

        //Create $appSourceDir and extract uploaded zip archive into it.
        if (!mkdir($appSourceDir)) {
            throw new Exception("Failed to create directory ${appSourceDir}.");
        }
        try {
            ZipUtils::unzip($fileData['tmp_name'], $appSourceDir);
        }
        catch (ZipException $e) {
            throw new InvalidApplicationArchiveException('Invalid application archive.', null, $e);
        }

        //Remove tempDir after extracting the archive to target directory.
        FileUtils::rmDir($tempDir);

        //Create the app using $yamlData from the uploaded archive.
        $updatedYamlData = $this->appService->createApp($yamlData);

        //Ensure application UUID does not change - even by mistake!
        if ($yamlData['app']['uuid'] !== $updatedYamlData['app']['uuid']) {
            throw new Exception(
                'Application UUID in descriptor YAML does not match UUID returned by createApp service.');
        }

        return $updatedYamlData;
    }

    public function createAppArchive($appUuid) {
        $app = new App($this->table);
        $app->loadByUuid($appUuid);
        $appSourceDir = AppArtifactNamingStrategy::getSourceAppDirectory($this->config, $app->getProperties());
        if(!is_dir($appSourceDir)){
            throw new InvalidApplicationArchiveException('Application source directory does not exist.', null);
        }
        $tempFileName = FileUtils::createTempFileName();
        ZipUtils::zipDir($appSourceDir, $tempFileName);
        return [
            'name' => $app->getProperty('name'),
            'uuid' => $appUuid,
            'zipFile' => $tempFileName
        ];
    }
    public function getArtifacts($appUuid, $artifactType) {
        $app = new App($this->table);
        $app->loadByUuid($appUuid);
        $appData = [
            'uuid' => $appUuid,
            'name' => $app->getProperty('name')
        ];
        $appSourceDir = AppArtifactNamingStrategy::getSourceAppDirectory($this->config, $appData);
        if (!file_exists($appSourceDir)) {
            throw new FileNotFoundException(
                "Application source directory is not found.", ['directory' => $appSourceDir]);
        }
        $contentDir = $appSourceDir . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR;
        switch($artifactType) {
            case 'workflow':
                $targetDir = $contentDir . 'workflows';
            break;
            case 'form':
                $targetDir = $contentDir . 'forms';
            break;
            default:
                throw new Exception("Unexpected artifact type ${artifactType}.");
        }
        $targetDir = $targetDir . DIRECTORY_SEPARATOR;
        $files = array();
        if ($handle = opendir($targetDir)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $ext = pathinfo($entry, PATHINFO_EXTENSION);
                    if(($ext=='json' && $artifactType =='form') || ($ext=='bpmn' && $artifactType =='workflows') ){
                        $files[] = array('name'=>substr($entry, 0, strrpos($entry, '.')),'content'=>file_get_contents($targetDir.$entry));
                    }
                }
            }
            closedir($handle);
        }
        return $files;
    }
}
