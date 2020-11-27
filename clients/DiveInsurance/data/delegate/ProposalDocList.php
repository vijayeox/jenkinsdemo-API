<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;

class ProposalDocList extends AbstractAppDelegate
{
    public function __construct(){
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("Proposal Document Clean Up");
        $proposalDoc = array();
		$documents = array();
		if(isset($data['quoteDocuments'])){
			$documents = $data['quoteDocuments'];
			$documents = is_string($documents) ? json_decode($documents,true) : $documents;
			if(sizeof($documents) > 0){
				if(!isset($data['proposalDocuments'])) {
					$data['proposalDocuments'] = array();
				}else{
					$data['proposalDocuments'] = is_string($data['proposalDocuments']) ? json_decode($data['proposalDocuments'],true) : $data['proposalDocuments'];
				}
				$proposalLength = sizeof($data['proposalDocuments']);
				$key = 'proposalDocument_'.$proposalLength;
				foreach($documents as $val){
					array_push($proposalDoc,$val);
				}
				$data['proposalDocuments'][$key] = $proposalDoc;
			}
		}
		if(isset($data['userApproved'])){
            $data['userApproved'] = "";
		}
		if(isset($data['paymentVerified'])){
            $data['paymentVerified'] = "";
		}
        return $data;
    }
}
