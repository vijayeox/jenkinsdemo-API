<?php
namespace Esign\Controller;

use Oxzion\Controller\AbstractApiController;
use Oxzion\Service\EsignService;
use Zend\Db\Adapter\AdapterInterface;
use Exception;

class EsignCallbackController extends AbstractApiController
{
    private $esignService;

    /**
     * @ignore __construct
     */
    public function __construct(EsignService $esignService)
    {
        $this->esignService = $esignService;
    }

    public function setEsignService($esignService){
        $this->esginService = $esignService;
    }
    /**
     * sign event callback api
     * @api
     * @link 
     * @method POST
     * @return http status code
     */
    public function signEventAction()
    {
    	$data = $this->extractPostData();
        //TODO verify HASH 
        try {
            $dataR = $this->esignService->signEvent($data['documentId'],$data['eventType']);
            return $this->getSuccessResponse();
        }
        catch (Exception $e) {
            print_r($e->getMessage());exit();
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }

}
