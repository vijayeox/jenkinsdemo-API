<?php
namespace Oxzion\Service;

use Exception;
use Oxzion\Service\AbstractService;
use Oxzion\ServiceException;
use Logger;

class CommunicationService
{
    
    public function __construct($serviceName)
    {
        $this->serviceName = $serviceName;
        $this->logger = Logger::getLogger(get_class($this));
        $this->config = array("Twillio" => array("account_sid" => "AC953e0337acd4c093c3b91dc3e8f39581",
         "auth_token" => "9f667ccdda5f6b38ad8cdc1c6e450512",
         "twilio_phone_number" => "+12816168126"));
    }
    
    public function sendSms($dest_phone_number, $body) { 
        $this->logger->info("Entered ");
        try {
            $communicationEngine = $this->getCommunicationEngine();
            $sendSmsResult = $communicationEngine->sendSms($dest_phone_number, $body);
        } catch (Exception $e) { 
            $this->logger->error($e->getMessage()."-".$e->getTraceAsString());
            throw $e;
        }
        $this->logger->info("Exit ");
        return $sendSmsResult;
    }

    //
    public function makeCall($dest_phone_number, $body) { 
        $this->logger->info("Entered ");
        try {
            $communicationEngine = $this->getCommunicationEngine();
            $sendSmsResult = $communicationEngine->sendSms($dest_phone_number, $body);
        } catch (Exception $e) { 
            $this->logger->error($e->getMessage()."-".$e->getTraceAsString());
            throw $e;
        }
        $this->logger->info("Exit ");
        return $sendSmsResult;
    }
   
    //
    public function getCommunicationEngine() {
        $this->logger->info("Enter ");
        try {
            $className = "Oxzion\Communication\\".$this->serviceName."\CommunicationEngineImpl";
            if(class_exists($className)){
                $this->logger->info("Exit class found".$className);
                return (new $className($this->config[$this->serviceName]));
            } else {
                throw (new ServiceException("CommunicationEngine has not been implement ".$this->serviceName." missing!", 1));
            }
        } catch (Exception $e){
            $this->logger->error("Communication has not been implement ".$this->serviceName." missing!");
            throw (new ServiceException("Communication has not been implement ".$this->serviceName." missing!", 1));
        }
        $this->logger->info("Exit ");
    }
}
?>
