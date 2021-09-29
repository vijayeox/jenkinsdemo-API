<?php 
namespace Prehire\Controller;

use Oxzion\Controller\AbstractApiController;
use Prehire\Service\FoleyService;
use Oxzion\ValidationException;
use Oxzion\InvalidParameterException;
use Prehire\Model\Prehire;
use Prehire\Model\PrehireTable;
use Zend\Db\Adapter\AdapterInterface;
use Exception;

class FoleyController extends AbstractApiController
{
    private $foleyService;
    /**
     * @ignore __construct
     */
    public function __construct(PrehireTable $table, FoleyService $foleyService, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, Prehire::class);
        $this->foleyService = $foleyService;
        //$this->setIdentifierName('prehireId');
    }

    public function foleyEndpointAction()
    {
        $data = $this->extractPostData();
        $routeparams = $this->params()->fromRoute();
        $this->log->info(__CLASS__ . "-> foley endpoint - " . json_encode($routeparams, true));

        $this->log->info(__CLASS__ . "-> foley API Data - " . json_encode($data, true));
        if(!isset($routeparams['type']))
            throw new InvalidParameterException('Incorrect Request Provided');
        try {
            switch($routeparams['type']) {
                case 'ApplicantShell':
                    $result = $this->foleyService->invokeApplicantShellCreationAPI('createapplicant/',$data);
                    break;
                case 'OrderMVR':
                    $result = $this->foleyService->invokeOrderMvrAPI('ordermvr/',$data);
                    break;
                case 'OrderCHQuery':
                    $result = $this->foleyService->invokeCHQueryAPI('CHQueryOrder/',$data);
                    break;
                case 'OrderDrugTest':
                    
                    break;
                case 'OrderBGC':
                    
                    break;
                default:
                    throw new InvalidParameterException('Incorrect Request Type '.$routeparams['type']);
            }
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
        return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
    }
}

?>