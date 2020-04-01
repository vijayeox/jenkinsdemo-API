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
        if($data['product']=="Emergency First Response"){
            $fileData = $data;
            $data = array();
            $data['EFRFileId'] = $fileData['fileId'];
            $data['EFRWorkflowInstanceId'] = $fileData['parentWorkflowInstanceId'];
            $data['efrToIPLUpgrade'] = true;
            $data['efrAmountPaid'] = $fileData['amountPayable'];
            $data['product'] = "Individual Professional Liability";
            $data['parentWorkflowInstanceId'] = "";
            $data['fileId'] = "";

            $data['padi']=$fileData['padi'];
            $data['padiNotFound']=$fileData['padiNotFound'];
            $data['padiNotFoundCsrReview']=$fileData['padiNotFoundCsrReview'];
            $data['padiVerified']=$fileData['padiVerified'];
            $data['padi_empty']=$fileData['padi_empty'];
            $data['verified']=$fileData['verified'];
            $data['changePadi']=$fileData['changePadi'];
            $data['policy_exists']=$fileData['policy_exists'];
            $data['initiatedByCsr']=$fileData['initiatedByCsr'];

            $privileges = $this->getPrivilege();
            if(isset($privileges['MANAGE_POLICY_APPROVAL_WRITE']) && 
                $privileges['MANAGE_POLICY_APPROVAL_WRITE'] == true){
                $data['initiatedByCsr'] = true;
            }else{
                $data['initiatedByCsr'] = false;
            }

            $data['address1']= isset($fileData['address1']) ? $fileData['address1'] : NULL;
            $data['address2']= isset($fileData['address2']) ? $fileData['address2'] : NULL;
            $data['city']= isset($fileData['city']) ? $fileData['city'] : NULL;
            $data['country']= isset($fileData['country']) ? $fileData['country'] : NULL;
            $data['disableUserInfoEdit']= isset($fileData['disableUserInfoEdit']) ? $fileData['disableUserInfoEdit'] : NULL;
            $data['email']= isset($fileData['email']) ? $fileData['email'] : NULL;
            $data['fax']= isset($fileData['fax']) ? $fileData['fax'] : NULL;
            $data['firstname']= isset($fileData['firstname']) ? $fileData['firstname'] : NULL;
            $data['initial']= isset($fileData['initial']) ? $fileData['initial'] : NULL;
            $data['lastname']= isset($fileData['lastname']) ? $fileData['lastname'] : NULL;
            $data['home_country_code']= isset($fileData['home_country_code']) ? $fileData['home_country_code'] : NULL;
            $data['home_phone']= isset($fileData['home_phone']) ? $fileData['home_phone'] : NULL;
            $data['home_phone_number']= isset($fileData['home_phone_number']) ? $fileData['home_phone_number'] : NULL;
            $data['identifier_field']= isset($fileData['identifier_field']) ? $fileData['identifier_field'] : NULL;
            $data['mailaddress1']= isset($fileData['mailaddress1']) ? $fileData['mailaddress1'] : NULL;
            $data['mailaddress2']= isset($fileData['mailaddress2']) ? $fileData['mailaddress2'] : NULL;
            $data['phone']= isset($fileData['phone']) ? $fileData['phone'] : NULL;
            $data['phone_country_code']= isset($fileData['phone_country_code']) ? $fileData['phone_country_code'] : NULL;
            $data['phone_number']= isset($fileData['phone_number']) ? $fileData['phone_number'] : NULL;
            $data['physical_city']= isset($fileData['physical_city']) ? $fileData['physical_city'] : NULL;
            $data['physical_country']= isset($fileData['physical_country']) ? $fileData['physical_country'] : NULL;
            $data['physical_state']= isset($fileData['physical_state']) ? $fileData['physical_state'] : NULL;
            $data['physical_zip']= isset($fileData['physical_zip']) ? $fileData['physical_zip'] : NULL;
            $data['sameasmailingaddress']= isset($fileData['sameasmailingaddress']) ? $fileData['sameasmailingaddress'] : NULL;
            $data['state']= isset($fileData['state']) ? $fileData['state'] : NULL;
            $data['username']= isset($fileData['username']) ? $fileData['username'] : NULL;
            $data['zip']= isset($fileData['zip']) ? $fileData['zip'] : NULL;
            $data['state_in_short']= isset($fileData['state_in_short']) ? $fileData['state_in_short'] : NULL;
            $data = parent::execute($data, $persistenceService );
        }
        return $data;
    }
}