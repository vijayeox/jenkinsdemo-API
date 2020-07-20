<?php
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\UserContextTrait;
require_once __DIR__."/PolicyCheck.php";

class EFRToIPLUpgrade extends PolicyCheck
{
	use UserContextTrait;

    public function __construct(){
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {  
        $fileData = get_object_vars(json_decode($data['data']));
        if($fileData['product']=="Emergency First Response"){
            $new_data = array();
            $new_data['EFRFileId'] = $data['fileId'];
            $new_data['EFRWorkflowInstanceId'] = $data['workflowInstanceId'];
            $new_data['efrToIPLUpgrade'] = true;
            $new_data['efrAmountPaid'] = (!empty($fileData['liabilityCoverage1000000'])?$fileData['liabilityCoverage1000000'] : 0.00) + (!empty($fileData['amountPayable']) ? $fileData['amountPayable'] : 0.00) + 
                                         (!empty($fileData['endorAmountPayable']) ? $fileData['endorAmountPayable'] : 0.00);
            $new_data['product'] = "Individual Professional Liability";
            $new_data['disableUserInfoEdit'] = true;
            $new_data['excessLiability'] = isset($fileData['excessLiability'])?$fileData['excessLiability']:NULL;
            $new_data['padi']= isset($fileData['padi']) ? $fileData['padi'] : NULL ;
            $new_data['padiNotFound']= isset($fileData['padiNotFound']) ? $fileData['padiNotFound'] : NULL ;
            $new_data['padiNotFoundCsrReview']= isset($fileData['padiNotFoundCsrReview']) ? $fileData['padiNotFoundCsrReview'] : NULL ;
            $new_data['padiVerified']= isset($fileData['padiVerified']) ? $fileData['padiVerified'] : NULL ;
            $new_data['padi_empty']= isset($fileData['padi_empty']) ? $fileData['padi_empty'] : NULL ;
            $new_data['verified']= isset($fileData['verified']) ? $fileData['verified'] : NULL ;
            $new_data['businessPadiVerified']= isset($fileData['businessPadiVerified']) ? $fileData['businessPadiVerified'] : NULL ;

            $privileges = $this->getPrivilege();
            if(isset($privileges['MANAGE_POLICY_APPROVAL_WRITE']) && 
                $privileges['MANAGE_POLICY_APPROVAL_WRITE'] == true){
                $new_data['initiatedByCsr'] = true;
            }else{
                $new_data['initiatedByCsr'] = false;
            }
            $select = "Select firstname, MI as initial, lastname, business_name,rating FROM padi_data WHERE member_number ='".$fileData['padi']."'";
            $coverageOptions = array();
            $result = $persistenceService->selectQuery($select);
            if($result->count() > 0){
                $response = array();
                while ($result->next()) {
                    $response[] = $result->current();
                }
                $ratingApplicable = implode('","',array_column($response, 'rating'));
                $returnArray['rating'] = $ratingApplicable;
                $coverageSelect = 'Select DISTINCT coverage_level,coverage_name FROM coverage_options WHERE padi_rating  in ("'.$ratingApplicable.'") and category IS NULL';
                $this->logger->info("coverage select".$coverageSelect);
                $coverageLevels = $persistenceService->selectQuery($coverageSelect);
                if($result->count() > 0){
                    while ($coverageLevels->next()) {
                        $coverage = $coverageLevels->current();
                        $coverageOptions[] = array('label'=>$coverage['coverage_name'],'value'=>$coverage['coverage_level']);
                    }
                } else {
                    $coverageSelect = "Select DISTINCT coverage_name,coverage_level FROM coverage_options and category IS NULL";
                    $coverageLevels = $persistenceService->selectQuery($coverageSelect);
                    while ($coverageLevels->next()) {
                        $coverage = $coverageLevels->current();
                        $coverageOptions[] = array('label'=>$coverage['coverage_name'],'value'=>$coverage['coverage_level']);
                    }
                }
                $new_data['careerCoverageOptions'] = $coverageOptions;
            }
            $new_data['address1']= isset($fileData['address1']) ? $fileData['address1'] : NULL;
            $new_data['address2']= isset($fileData['address2']) ? $fileData['address2'] : NULL;
            $new_data['city']= isset($fileData['city']) ? $fileData['city'] : NULL;
            $new_data['country']= isset($fileData['country']) ? $fileData['country'] : NULL;
            $new_data['email']= isset($fileData['email']) ? $fileData['email'] : NULL;
            $new_data['fax']= isset($fileData['fax']) ? $fileData['fax'] : NULL;
            $new_data['firstname']= isset($fileData['firstname']) ? $fileData['firstname'] : NULL;
            $new_data['initial']= isset($fileData['initial']) ? $fileData['initial'] : NULL;
            $new_data['lastname']= isset($fileData['lastname']) ? $fileData['lastname'] : NULL;
            $new_data['home_country_code']= isset($fileData['home_country_code']) ? $fileData['home_country_code'] : NULL;
            $new_data['home_phone']= isset($fileData['home_phone']) ? $fileData['home_phone'] : NULL;
            $new_data['home_phone_number']= isset($fileData['home_phone_number']) ? $fileData['home_phone_number'] : NULL;
            $new_data['identifier_field']= isset($fileData['identifier_field']) ? $fileData['identifier_field'] : NULL;
            $new_data['mailaddress1']= isset($fileData['mailaddress1']) ? $fileData['mailaddress1'] : NULL;
            $new_data['mailaddress2']= isset($fileData['mailaddress2']) ? $fileData['mailaddress2'] : NULL;
            $new_data['phone']= isset($fileData['phone']) ? $fileData['phone'] : NULL;
            $new_data['phone_country_code']= isset($fileData['phone_country_code']) ? $fileData['phone_country_code'] : NULL;
            $new_data['phone_number']= isset($fileData['phone_number']) ? $fileData['phone_number'] : NULL;
            $new_data['physical_city']= isset($fileData['physical_city']) ? $fileData['physical_city'] : NULL;
            $new_data['physical_country']= isset($fileData['physical_country']) ? $fileData['physical_country'] : NULL;
            $new_data['physical_state']= isset($fileData['physical_state']) ? $fileData['physical_state'] : NULL;
            $new_data['physical_zip']= isset($fileData['physical_zip']) ? $fileData['physical_zip'] : NULL;
            $new_data['sameasmailingaddress']= isset($fileData['sameasmailingaddress']) ? $fileData['sameasmailingaddress'] : NULL;
            $new_data['state']= isset($fileData['state']) ? $fileData['state'] : NULL;
            $new_data['username']= isset($fileData['username']) ? $fileData['username'] : NULL;
            $new_data['zip']= isset($fileData['zip']) ? $fileData['zip'] : NULL;
            $new_data['state_in_short']= isset($fileData['state_in_short']) ? $fileData['state_in_short'] : NULL;
            $new_data['product_email_id'] = 'instructors@diveinsurance.com';
            $new_data = parent::execute($new_data, $persistenceService );
        }
        $data['data'] = $new_data;
        $data['workflow_uuid'] = $data['workflow_id'];
        return $data;
    }
}
