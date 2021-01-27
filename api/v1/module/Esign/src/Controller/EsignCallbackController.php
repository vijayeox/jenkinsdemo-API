<?php
namespace Esign\Controller;

use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\Service\EsignService;
use Zend\Db\Adapter\AdapterInterface;
use Exception;

class EsignCallbackController extends AbstractApiControllerHelper
{
    private $esignService;
    private $config;

    /**
     * @ignore __construct
     */
    public function __construct(EsignService $esignService, $config)
    {
        $this->esignService = $esignService;
        $this->config = $config;
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
        $hashValue = $this->getHash($data);
        $header = $this->getHashHeader();
        if($hashValue == $header){
            try {
                $this->esignService->signEvent($data['documentId'],$data['eventType']);
                return $this->getSuccessResponse();
            }
            catch (Exception $e) {
                $this->log->error($e->getMessage(), $e);
                return $this->exceptionToResponse($e);
            }
        }
        return $this->getErrorResponse("Invalid Resource", 404);
    }

    public function getHash($data){
        $value = json_encode($data);
        $seed = $this->config['esign']['clientid'];
        $hashValue = hash_hmac('sha256', $value, $seed);
        return $hashValue;
    }

    public function getHashHeader(){
        $header = $this->request->getHeader("Content-HmacSHA256");
        if($header){
            return $header->getFieldValue();
        }else{
            return "";
        }
    }
}
