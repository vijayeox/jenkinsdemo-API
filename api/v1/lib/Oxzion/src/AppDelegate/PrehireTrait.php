<?php
namespace Oxzion\AppDelegate;

use Prehire\Service\FoleyService;
use Logger;

trait PrehireTrait
{
    protected $logger;
    private $foleyService;
    

    public function __construct()
    {
        $this->logger = Logger::getLogger(__CLASS__);
    }
    
    public function setFoleyService(FoleyService $foleyService)
    {
        $this->logger->info("SET FOLEY SERVICE");
        $this->foleyService = $foleyService;
    }

    protected function createApplicantShell($endpoint, $data) {
        return $this->foleyService->invokeApplicantShellCreationAPI($endpoint, $data);
    }

    
}
