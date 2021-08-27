<?php
namespace Callback\Service;

use Exception;
use Oxzion\AccessDeniedException;
use Oxzion\Encryption\Crypto;
use Oxzion\Service\AbstractService;
use Oxzion\InvalidParameterException;
use Oxzion\Prehire\Foley\PrehireImpl;
use Oxzion\Encryption\TwoWayEncryption;
use Prehire\Service\PrehireService;

class PrehireCallbackService extends AbstractService
{
    protected $config;
    protected $prehireService;

    public function __construct($config,PrehireService $prehireService)
    {
        parent::__construct($config, null);
        $this->config = $config;
        $this->prehireService = $prehireService;
    }

    public function invokeImplementation($data)
    {
        $this->validateUser($this->config);
        try {
            $implementationType = $data['implementation'];
            switch($implementationType) {
                case 'foley':
                    $implementation = new PrehireImpl($this->prehireService);
                    $implementation->executeProcess($data);
                    break;
                default:
                    $this->logger->error("Prehire implementation provided is incorrect - " . $implementationType);
                    throw (new InvalidParameterException("Prehire Service provided is incorrect"));
            }
        } catch (Exception $e) {
            throw $e;    
        }
    }

    private function validateUser($config) {
        if(!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
            throw new AccessDeniedException('Incorrect Authentication type');
        }
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];
        $storedUsername = $config['foley']['username'];
        $storedPass = TwoWayEncryption::decrypt($config['foley']['password']);
        if(($username !== $storedUsername) || ($password !== $storedPass)) {
            throw new AccessDeniedException('Incorrect credentials entered');
        }
    }

    private function getPrehireImplementation($implementation)
    {
        //Avoid using due to injection type security breaches
        try {
            $className = "Oxzion\Prehire\\" . $implementation . "\PrehireImpl";
            if (class_exists($className)) {
                return (new $className($implementation));
            } else {
                throw (new InvalidParameterException("Prehire implementation provided is incorrect"));
            }
        } catch (Exception $e) {
            $this->logger->error("Prehire implementation provided is incorrect - " . $implementation);
            throw (new InvalidParameterException("Prehire Service provided is incorrect"));
        }
    }
}
