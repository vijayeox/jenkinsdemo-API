<?php
namespace Oxzion\Insurance\Ims;

use Oxzion\Utils\FileUtils;
use Oxzion\Utils\SOAPUtils;
use Oxzion\ServiceException;
use Oxzion\OxServiceException;
use Oxzion\Service\AbstractService;
use Oxzion\Messaging\MessageProducer;

class Service extends AbstractService
{
    private $messageProducer;
    private $soapClient;
    private $handle;
    private $token;
    /**
    * @ignore __construct
    */
    public function __construct($config, $dbAdapter, MessageProducer $messageProducer)
    {
        parent::__construct($config, $dbAdapter);
        $this->messageProducer = $messageProducer;
    }
    private function getConfig()
    {
        return $this->config['ims'];
    }
    public function setSoapClient($handle)
    {
        $this->handle = $handle;
        $this->soapClient = new SOAPUtils($this->getConfig()['apiUrl'].$this->handle.".asmx?wsdl");
        $this->soapClient->setHeader('http://tempuri.org/IMSWebServices/'.$this->handle, 'TokenHeader', ['Token' => $this->getToken()]);
    }
    private function getToken()
    {
        if ($this->token) {
            return $this->token;
        }
        $config = $this->getConfig();
        $soapClient = new SOAPUtils($config['apiUrl']."logon.asmx?wsdl");
        $LoginIMSUser = $soapClient->makeCall('LoginIMSUser', $config);
        $this->token = $LoginIMSUser['LoginIMSUserResult']['Token'];
        // echo "<pre>";print_r($this->token);exit;
        return $this->token;
    }
    public function makeCall(string $method, array $data)
    {
        return $this->soapClient->makeCall($method, $data);
    }

    public function getFunctionStructure($function)
    {
        return $this->soapClient->getFunctionStruct($function);
    }

    public function search($data)
    {
        switch ($this->handle) {
            case 'InsuredFunctions':
                $response = $this->searchInsured($data);
                break;
            case 'ProducerFunctions':
                $response = $this->searchProducer($data);
                break;
            default:
                throw new ServiceException("Search not avaliable for ".$this->handle, 'search.not.found', OxServiceException::ERR_CODE_NOT_FOUND);
                break;
        }
        return $response;
    }

    public function create(&$data, $commit = true)
    {
        switch ($this->handle) {
            case 'InsuredFunctions':
                $response = $this->createInsured($data);
                break;
            case 'ProducerFunctions':
                $response = $this->createProducer($data);
                break;
            default:
                throw new ServiceException("Create not avaliable for ".$this->handle, 'search.not.found', OxServiceException::ERR_CODE_NOT_FOUND);
                break;
        }
        return $response;
    }

    public function searchInsured($data)
    {
        $searchMethod = 'FindInsuredByName';
        $searchMethods = array(
            'SSN' => 'FindInsuredBySSN',
            'insuredContactGuid' => 'GetInsuredGuidFromContactGuid',
        );
        foreach ($searchMethods as $key => $method) {
            if (isset($data[$key])) {
                $searchMethod = $method;
                break;
            }
        }
        $InsuredResult = $this->makeCall($searchMethod, $data);
        $InsuredGuid = array('InsuredGuid' => current($InsuredResult));

        $GetInsured = $this->makeCall('GetInsured', array('insuredGuid' => current($InsuredGuid)));
        $GetInsuredPolicyInfo = $this->makeCall('GetInsuredPolicyInfo', array('insuredGuid' => current($InsuredGuid)));
        $GetInsuredPrimaryLocation = $this->makeCall('GetInsuredPrimaryLocation', array('insuredGuid' => current($InsuredGuid)));
        $HasSubmissions = $this->makeCall('HasSubmissions', array('insuredguid' => current($InsuredGuid)));

        return array_merge($InsuredGuid, $GetInsured, $GetInsuredPrimaryLocation, $HasSubmissions, $GetInsuredPolicyInfo);
    }
    public function createInsured($data)
    {
        return $this->makeCall('AddInsured', $data);
    }

    public function searchProducer($data)
    {
        $searchMethod = 'ProducerSearch';
        $searchMethods = array(
            'producerLocationGuid' => 'GetProducerInfo',
            'producerContactGuid' => 'GetProducerInfoByContact'
        );
        foreach ($searchMethods as $key => $method) {
            if (isset($data[$key])) {
                $searchMethod = $method;
                break;
            }
        }
        $ProducerInfo = $this->makeCall($searchMethod, $data);

        if (!current($ProducerInfo)) {
            throw new ServiceException("Producer not found", 'search.not.found', OxServiceException::ERR_CODE_NOT_FOUND);
        }
        if ($searchMethod == 'ProducerSearch') {
            $ProducerSearchResult = &$ProducerInfo[key($ProducerInfo)];
            $ProducerInfo = &$ProducerSearchResult[key($ProducerSearchResult)];
        }
        foreach ($ProducerInfo as &$Producer) {
            if (isset($Producer['LocationCode'])) {
                $GetProducerContactByLocationCode = $this->makeCall('GetProducerContactByLocationCode', array('locationCode' => $Producer['LocationCode']));
                $Producer += $GetProducerContactByLocationCode;
                $Producer += $this->makeCall('GetProducerContactInfo', array('producerContactGuid' => current($GetProducerContactByLocationCode)['GetProducerContactByLocationCodeResult']));
            }
        }
        return array_values($ProducerInfo);
    }
    public function createProducer($data)
    {
        return $this->makeCall('AddProducer', $data);
    }
}