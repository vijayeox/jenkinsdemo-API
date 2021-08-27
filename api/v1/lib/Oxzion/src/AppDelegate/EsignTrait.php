<?php
namespace Oxzion\AppDelegate;

use Oxzion\Service\EsignService;
use Logger;

trait EsignTrait
{
    protected $logger;
    private $esignService;
    
    public function __construct()
    {
        $this->logger = Logger::getLogger(__CLASS__);
    }
    
    public function setEsignService(EsignService $esignService)
    {
        $this->logger->info("SET ESIGN SERVICE");
        $this->esignService = $esignService;
    }

    protected function setupDocument($ref_id, $documentUrl, array $signers)
    {
        return $this->esignService->setupDocument($ref_id, $documentUrl, $signers);
    }

    protected function getDocumentSigningLink($docId)
    {
        return $this->esignService->getDocumentSigningLink($docId);
    }
}
