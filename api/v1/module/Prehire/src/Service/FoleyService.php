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
        //$this->restClient = new RestClient($config['foley']['foleyurl']);
        $storedApiPas = TwoWayEncryption::decrypt($config['foley']['foleyapipassword']);
        $this->logger->info("decrypted passwird-----" . $storedApiPas);
        $this->foleyapiusername = $config['foley']['foleyapiusername'];
        $this->foleyapipassword = $storedApiPas;
        $this->foleyUrl = $config['foley']['foleyurl'];
    }
    
    private function getResponse($endpoint, $dataToPost){
        $this->logger->info(__CLASS__ . "-> foley service request- " . $dataToPost);
        $this->logger->info(__CLASS__ . "-> foley service endpoint- " . $endpoint);
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->foleyUrl.$endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $dataToPost,
        CURLOPT_HTTPHEADER => array(
            
            "content-type: application/json",
            "f-api-key: $this->foleyapipassword",
            "f-api-username: $this->foleyapiusername",
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($httpcode == 200) {
            return $response = json_decode($response,true);;
        }else{
            if ($err) {
            
                $this->logger->info(__CLASS__ . "-> Foley Integration Failed. - " . json_encode($response, true));
            }
        }
    }

    public function invokeApplicantShellCreationAPI($endpoint, $data){
        
        $dataToPost = json_encode($data);
        try{
            $response = $this->getResponse($endpoint, $dataToPost);
            //save entry in table
            $form = new Prehire($this->table);
            $datatoSave['user_id'] = $this->getIdFromUuid('ox_user','ea040af7-bad4-4292-83ad-3ac1b33b1a6b');
            $datatoSave['implementation'] = 'foley';
            $datatoSave['request_type'] = $endpoint;
            $datatoSave['request'] = $dataToPost;
            $form->assign($datatoSave);
            try {
                $this->beginTransaction();
                $form->save();
                $this->commit();
            } catch (Exception $e) {
                $this->rollback();
                throw $e;
            }
            return $response;


        }catch (Exception $e) {
            throw new Exception("Foley Integration Failed.", 0, $e);
        }
    }
    
    public function invokeOrderMvrAPI($endpoint, $data){
        
        $dataToPost = json_encode($data);
        try{
            $response = $this->getResponse($endpoint, $dataToPost);
            
            //save entry in table
            $form = new Prehire($this->table);
            $datatoSave['user_id'] = $this->getIdFromUuid('ox_user','ea040af7-bad4-4292-83ad-3ac1b33b1a6b');
            $datatoSave['implementation'] = 'foley';
            $datatoSave['request_type'] = $endpoint;
            $datatoSave['request'] = $dataToPost;
            $form->assign($datatoSave);
            try {
                $this->beginTransaction();
                $form->save();
                $this->commit();
            } catch (Exception $e) {
                $this->rollback();
                throw $e;
            }
            
            return $response;

        }catch (Exception $e) {
            throw new Exception("Foley Integration Failed.", 0, $e);
        }
    }
    
    public function invokeCHQueryAPI($endpoint, $data){
        
        $dataToPost = json_encode($data);
        try{
            $response = $this->getResponse($endpoint, $dataToPost);
            
            //save entry in table
            $form = new Prehire($this->table);
            $datatoSave['user_id'] = $this->getIdFromUuid('ox_user','ea040af7-bad4-4292-83ad-3ac1b33b1a6b');
            $datatoSave['implementation'] = 'foley';
            $datatoSave['request_type'] = $endpoint;
            $datatoSave['request'] = $dataToPost;
            $form->assign($datatoSave);
            try {
                $this->beginTransaction();
                $form->save();
                $this->commit();
            } catch (Exception $e) {
                $this->rollback();
                throw $e;
            }
            
            return $response;

        }catch (Exception $e) {
            throw new Exception("Foley Integration Failed.", 0, $e);
        }
    }
}




?>