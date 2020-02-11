<?php
namespace Oxzion\AppDelegate;

use Oxzion\Db\Persistence\Persistence;
use Oxzion\Service\FileService;
use Logger;

trait FileTrait
{
    protected $logger;
    protected $fileService;
    protected $appId;
    
    public function __construct(){
        $this->logger = Logger::getLogger(__CLASS__);
    }
    public function setFileService(FileService $fileService){
        $this->logger->info("SET FILE SERVICE");
        $this->fileService = $fileService;
    }
    public function setAppId($appId){
        $this->logger->info("SET APP ID");
        $this->appId = $appId;
    }

    protected function getFile($id){
        $this->logger->info("GET FILE");
        return $this->fileService->getFile($id);
    }

    protected function getFileList($params,$filterparams = null){
        $this->logger->info("SET FILE LIST");
        return $this->fileService->getFileList($this->appId,$params,$filterparams);
    }

    protected function saveFile($params,$fileId){
        $this->logger->info("SAVE FILE");
        return $this->fileService->updateFile($params,$fileId);
    }
}
