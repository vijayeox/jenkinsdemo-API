<?php
namespace Callback\Service;

use Exception;
use Oxzion\Service\AbstractService;
use Oxzion\InvalidParameterException;
use Oxzion\Prehire\Foley\PrehireImpl;

class PrehireCallbackService extends AbstractService
{
    protected $dbAdapter;

    public function invokeImplementation($data)
    {
        try {
            $implementationType = $data['implementation'];
            switch($implementationType) {
                case 'foley':
                    $implementation = new PrehireImpl();
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
