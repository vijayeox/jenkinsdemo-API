<?php
use Oxzion\Db\Persistence\Persistence;

require_once __DIR__ . "/DispatchDocument.php";

class DispatchRenewalPolicy extends DispatchDocument
{

    public $template = array();

    public function __construct()
    {
        $this->template = array(
            'Dive Store' => 'diveStoreRenewalMailTemplate');
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $data['template'] = $this->template[$data['product']];
        $data['subject'] = 'Renewal Policy';
        $response = $this->dispatch($data);
        return $response;
    }
}
