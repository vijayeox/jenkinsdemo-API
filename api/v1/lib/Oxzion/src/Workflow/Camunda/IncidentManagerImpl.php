<?php
namespace Oxzion\Workflow\Camunda;

use Oxzion\Workflow\IncidentManager;
use Oxzion\Utils\RestClient;
use Exception;
use Logger;

class IncidentManagerImpl implements IncidentManager
{
    private $restClient;
    protected $logger;
    
    public function __construct($config)
    {
        $class= get_class($this);
        $class = substr($class, strrpos($class, "\\")+1);
        $this->initLogger();
        $this->restClient = new RestClient($config['workflow']['engineUrl']);
    }
    
    protected function initLogger()
    {
        $this->logger = Logger::getLogger(__CLASS__);
    }

    public function resolveIncident($id)
    {
        $query = 'incident/'.$id.'/resolve';
        try {
            $this->logger->info("Entering the resolve Incident method in IncidentManagerImpl File\n");
            $response =  $this->restClient->delete($query);
            $result = json_decode($response, true);
            return $result;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(),$e);
            throw $e;
        }
    }
    public function getIncident($id){
        $query = 'incident/'.$id;
        try {
            $this->logger->info("Entering the get Incident method in IncidentManagerImpl File\n");
            $response =  $this->restClient->get($query);
            $result = json_decode($response, true);
            return $result;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(),$e);
            throw $e;
        }
    }
}
?>