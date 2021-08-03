<?php

use Oxzion\Utils\RestClient;
//use Oxzion\HttpException;
//use Logger;

class ApiCall
{

    private $config;
    protected $logger;

    public function __construct()
    {
        $this->logger = Logger::getLogger(__CLASS__);
        include(__DIR__.'/../zendriveintegration/config.php');
        $this->config = $zendriveconfig;
        $this->restClient = new RestClient($this->config['zendriveServerUrl']);
    }

    public function getApiResponse($endpoint, $params)
    {
        try{
            $headers = array('Authorization' => 'Api-Key '.$this->config['authToken']);
            $this->logger->info("in apicalldelegatefile- params" . json_encode($params));
            $response = $this->restClient->postWithHeader($endpoint, $params, $headers);
            $this->logger->info("in apicalldelegatefile- params" . json_encode($response));
        }catch (Exception $e) {
            throw new Exception("Zendrive Integration Failed.", 0, $e);
        }
        return json_encode($response);
        
    }
}

?>