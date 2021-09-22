<?php
namespace Prehire\Service;

use Exception;
use Oxzion\EntityNotFoundException;
use Oxzion\ServiceException;
use Oxzion\OxServiceException;
use Oxzion\Service\AbstractService;
use Prehire\Model\Prehire;
use Prehire\Model\PrehireTable;
use Oxzion\Utils\RestClient;
use Oxzion\Encryption\TwoWayEncryption;

class FoleyService extends AbstractService{

    private $table;
    /**
     * @ignore __construct
     */

    public function __construct($config, $dbAdapter, PrehireTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        //$this->logger->info("config url-----" . print_r($config['foley'], true));
        $this->restClient = new RestClient($config['foley']['foleyurl']);
        $storedApiPas = TwoWayEncryption::decrypt($config['foley']['foleyapipassword']);
        $this->logger->info("decrypted passwird-----" . $storedApiPas);
        $this->foleyapiusername = $config['foley']['foleyapiusername'];
        $this->foleyapipassword = $storedApiPas;
    }
    
    public function invokeApplicantShellCreationAPI($endpoint, $data){
        
        $dataToPost = json_encode($data);
        $this->logger->info(__CLASS__ . "-> foley service request- " . $dataToPost);
        try{
            $headers = array('F-API-username' => $this->foleyapiusername, 'F-API-key'=>$this->foleyapipassword); //'Content-Type'=>'application/json',
            $response = $this->restClient->postWithHeader($endpoint, $dataToPost, $headers);
            $this->logger->info(__CLASS__ . "-> foley service response - " . json_encode($response, true));
        }catch (Exception $e) {
            throw new Exception("Foley Integration Failed.", 0, $e);
        }
    }
  
}




?>