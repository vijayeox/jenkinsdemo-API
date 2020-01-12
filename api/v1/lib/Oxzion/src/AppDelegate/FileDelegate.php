<?php
namespace Oxzion\AppDelegate;

use Oxzion\Service\FileService;
use Logger;


abstract class FileDelegate implements AppDelegate
{
	use UserContextTrait;
	protected $logger;
	protected $fileService;
	protected $appId;
	
	public function __construct(){
		$this->logger = Logger::getLogger(__CLASS__);
	}
	public function setFileService(FileService $fileService){
		$this->fileService = $fileService;
	}
	public function setAppId($appId){
		$this->appId = $appId;
	}
	protected function getFileList($params,$filterparams = null){
		return $this->fileService->getFileList($this->appId,$params,$filterparams);
	}
}
