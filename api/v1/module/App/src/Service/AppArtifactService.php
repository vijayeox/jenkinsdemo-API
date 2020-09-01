<?php
namespace App\Service;

use App\Model\App;
use App\Model\AppTable;
use Exception;
use Oxzion\Service\AbstractService;
use Oxzion\FileNotFoundException;
use Oxzion\DuplicateFileException;
use Oxzion\App\AppArtifactNamingStrategy;

/* 
 * This service is for managing application artifact files like form definition 
 * files and workflow definition files.
 */
class AppArtifactService extends AbstractService
{
    private $table;

    public function __construct($config, $dbAdapter, AppTable $table) {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
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
        //Check whether any of the uploaded file(s) already exists on the server.
        foreach($_FILES as $key => $fileData) {
            if (UPLOAD_ERR_OK != $fileData['error']) {
                throw new Exception('File upload failed.');
            }
            $filePath = $targetDir . $fileData['name'];
            if (file_exists($filePath)) {
                throw new DuplicateFileException("File already exists.", ['file' => $filePath]);
            }
        }
        //Move/copy the files to destination.
        foreach($_FILES as $key => $fileData) {
            $filePath = $targetDir . $fileData['name'];
            if (!rename($fileData['tmp_name'], $filePath)) {
                throw new Exception('Failed to move file ' . $fileData['tmp_name'] . ' to destination.');
            }
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
}
