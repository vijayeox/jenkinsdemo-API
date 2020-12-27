<?php
namespace Esign\Controller;

use Oxzion\Controller\AbstractApiController;
use Oxzion\Service\EsignService;
use Zend\Db\Adapter\AdapterInterface;
use Exception;

class EsignController extends AbstractApiController
{
    private $esignService;

    /**
     * @ignore __construct
     */
    public function __construct(EsignService $esignService)
    {
        $this->setIdentifierName('esignId');
        $this->esignService = $esignService;
    }

    public function setEsignService($esignService){
        $this->esginService = $esignService;
    }
    /**
     * GET sttus API
     * @api
     * @link 
     * @method GET
     * @return get status 
     */
    public function getStatusAction()
    {
    	$docId = $this->params()->fromRoute()['docId'];
        try {
            $result = $this->esignService->getDocumentStatus($docId);
            return $this->getSuccessResponseWithData(['status' => $result]);
        }
        catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }

    // public function getSubscription(){

    // }
}
