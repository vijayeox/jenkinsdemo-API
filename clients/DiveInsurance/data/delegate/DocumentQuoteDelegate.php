<?php
use Oxzion\Db\Persistence\Persistence;
require_once __DIR__."/DocumentSaveDelegate.php";

class DocumentQuoteDelegate extends DocumentSaveDelegate {
    public function __construct() {
        parent::__construct();
    }
    public function execute(array $data, Persistence $persistenceService) {
        $this->logger->info("DocumentQuoteDelegate Delegate -".print_r($data,true));
        $data = parent::execute($data,$persistenceService);
        $this->processAttachments($data);
        return $data;
    }

    private function processAttachments(&$data){
        if(isset($data['csr_attachments']) && (!empty($data['csr_attachments']))){
            if(is_string($data['csr_attachments'])){
                $data['csr_attachments'] = json_decode($data['csr_attachments'], true);
            }
            if(!isset($data['csrApprovalAttachments'])){
                $data['csrApprovalAttachments'] = array();
            }else if(is_string($data['csrApprovalAttachments'])){
                $data['csrApprovalAttachments'] = json_decode($data['csrApprovalAttachments'], true);
            }
            foreach ($data['csr_attachments'] as $key => $value) {
                $data['csrApprovalAttachments'][] = $value;
            }
                $data['csr_attachments'] = "";
        }
    }

    
}
