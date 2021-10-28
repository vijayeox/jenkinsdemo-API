<?php
namespace Oxzion\AppDelegate;

use Oxzion\ServiceException;
use Oxzion\OxServiceException;
use Oxzion\Insurance\InsuranceService;
use Logger;

trait InsuranceTrait
{
    protected $logger;
    private $service;
    private $insuranceService;

    public function __construct()
    {
        $this->logger = Logger::getLogger(__CLASS__);
    }
    public function setInsuranceService(InsuranceService $service)
    {
        $this->service = $service;
    }
    public function setInsuranceConfig($data)
    {
        $this->logger->info("Set service -> " . print_r($data, true));
        $this->insuranceService = $this->service->getService($data['client'], $data);
    }

    // eg. call $service->search(["searchString" => "demo", "startWith" => true]);
    public function __call($method, $params)
    {
        $this->logger->info("Call Service -> " . print_r([$method, $params], true));
        if (method_exists($this, $method)) {
            call_user_func_array($this->{$method}, $params);
        } elseif (method_exists($this->insuranceService, $method)) {
            return call_user_func_array($this->insuranceService->{$method}, $params);
        } else {
            throw new ServiceException("Method not avaliable for ", 'method.not.found', OxServiceException::ERR_CODE_NOT_FOUND);
        }
    }

}