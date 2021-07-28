<?php 

namespace Oxzion\Service;

use Oxzion\Service\AbstractService;
use Oxzion\ServiceException;
use Oxzion\Utils\RestClient;
use Exception;

class ApiCallService extends AbstractService
{

    public function __construct($config, $dbAdapter)
    {
        parent::__construct($config, $dbAdapter);
        $this->restClient = new RestClient($this->config['zendrive']['zendriveServerUrl']);
    }

    public function getVendorApiResponse($endpoint, $params)
    {
        try{
            //$clientUrl = $url.$endpoint;
            $headers = array('Authorization' => 'Api-Key '.$this->config['zendrive']['authToken']);
            //$fleet_uuid = $params['fleet_uuid'];
            //$fleet_id = $this->getIdFromUuid('ox_account',$fleet_uuid);
            //$this->logger->info("in APIservice- fleetid" . $fleet_id);
            //unset($params['fleet_uuid']);
            //$params['fleet_id'] = $fleet_id;
            $this->logger->info("in APIservice- params" . json_encode($params));
            $response = $this->restClient->postWithHeader($endpoint, $params, $headers);
            return json_encode($response);
        }catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logger->info("Failed to get API Response" . $e);
        }
        
    }
}




?>