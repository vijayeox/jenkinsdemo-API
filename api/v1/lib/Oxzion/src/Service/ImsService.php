<?php
namespace Oxzion\Service;

use Oxzion\Service\AbstractService;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Utils\FileUtils;
use Oxzion\Utils\SOAPUtils;

class ImsService extends AbstractService
{
    private $messageProducer;
    private $soapClient;
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
    private function getToken()
    {
        $soapClient = new SOAPUtils($this->getConfig()['apiUrl']."logon.asmx?wsdl");
        $response = $soapClient->makeCall('LoginIMSUser', $this->getConfig());
        return $response['LoginIMSUserResult']['Token'];
    }
    public function setSoapClient($handle)
    {
        $this->soapClient = new SOAPUtils($this->getConfig()['apiUrl'].$handle.".asmx?wsdl");
        $this->soapClient->setHeader('http://tempuri.org/IMSWebServices/'.$handle, 'TokenHeader', ['Token' => $this->getToken()]);
    }

    public function getFunctionStructure($function)
    {
        return $this->soapClient->getFunctionStruct($function);
    }

    public function createProducer($params, $data)
    {
        return $this->soapClient->makeCall('AddProducer', $data);
    }

}