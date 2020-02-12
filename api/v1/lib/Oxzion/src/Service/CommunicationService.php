<?php
namespace Oxzion\Service;

use Exception;
use Oxzion\Service\AbstractService;
use Oxzion\ServiceException;
use Logger;

class CommunicationService
{
    
    public function __construct($config)
    {
        $this->logger = Logger::getLogger(get_class($this));
        $this->config = $config;
    }
    
    public function sendSms($dest_phone_number, $body) { 
        $this->logger->info("Entered ");
        try {
            $communicationEngine = $this->getCommunicationEngine($this->config);
            $sendSmsResult = $communicationEngine->sendSms($dest_phone_number, $body);
        } catch (Exception $e) { 
            $this->logger->error($e->getMessage()."-".$e->getTraceAsString());
            throw $e;
        }
        $this->logger->info("Exit ");
        return $sendSmsResult;
    }
   
    //
    public function getCommunicationEngine(array $communicationInfo) {
        $this->logger->info("Enter ");
        try {
            $className = "Oxzion\Communication\\".$communicationInfo['communication_client']."\CommunicationEngineImpl";
            if(class_exists($className)){
                $this->logger->info("Exit class found".$className);
                return (new $className($communicationInfo));
            } else {
                throw (new ServiceException("CommunicationEngine has not been implement ".$communicationInfo['communication_client']." missing!", 1));
            }
        } catch (Exception $e){
            $this->logger->error("Communication has not been implement ".$communicationInfo['communication_client']." missing!");
            throw (new ServiceException("Communication has not been implement ".$communicationInfo['communication_client']." missing!", 1));
        }
        $this->logger->info("Exit ");
    }
}
?>
