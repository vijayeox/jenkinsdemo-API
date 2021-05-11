<?php

use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\AppDelegate\AppDelegateTrait;
use Oxzion\AppDelegate\HttpClientTrait;

class GenerateCompliance extends AbstractDocumentAppDelegate
{

    use HttpClientTrait;
    use FileTrait;
    use AppDelegateTrait;

    protected $carrierTemplateList = array(
        "driverEmploymentApplication" => array(
            "type" => "pdf",
            "template" => "driverEmploymentApplication.pdf",
        )
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Executing Generate Compliance with data- " . json_encode($data, JSON_UNESCAPED_SLASHES));
        // Add logs for created by id and producer name who triggered submission
        return $data;
    }

}
