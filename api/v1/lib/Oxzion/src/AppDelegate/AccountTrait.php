<?php
namespace Oxzion\AppDelegate;

use Oxzion\Service\AccountService;
use Logger;

trait AccountTrait
{
    protected $logger;
    private $accountService;
    
    public function __construct()
    {
        $this->logger = Logger::getLogger(__CLASS__);
    }
    
    public function setAccountService(AccountService $accountService)
    {
        $this->logger->info("SET ACCOUNT SERVICE");
        $this->accountService = $accountService;
    }

    protected function registerAccount($data)
    {
        return $this->accountService->registerAccount($data);
    }
}
