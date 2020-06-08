<?php

use Oxzion\AppDelegate\TemplateAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\UserContextTrait;

class GetSurplusLines extends TemplateAppDelegate
{
    use UserContextTrait;
    public function __construct(){
        parent::__construct();
    }

    // Premium Calculation values are fetched here
    public function execute(array $data,Persistence $persistenceService)
    {  
        if(AuthContext::isPrivileged('MANAGE_ADMIN_WRITE')){
            if($data['product'] == 'Individual Professional Liability'){
                $product = 'IPL';
            }else if($data['product'] == 'Emergency First Response'){
                $product = 'EFR';
            }else if($data['product'] == 'Dive Boat'){
                $product = 'DiveBoat';
            }else if($data['product'] == 'Dive Store'){
                $product = 'DiveStore';
            }else if($data['product'] == 'Group Professional Liability'){
                $product = 'Group';
            } 

            $destinationPath = $this->destination.AuthContext::get(AuthConstants::ORG_UUID).'/SurplusLines/'.$product;

            if($data['year'] == ""){
                $data['year'] = $this->getMaxYear($product,$destinationPath);
            }
            $data = $this->getSurplusLines($data,$product,$destinationPath,$persistenceService);

            return $data;
        }else{
            throw new DelegateException("You do not access to this API",'no_access');
        }
        
    }


    private function getSurplusLines(&$data,$product,$destinationPath,$persistenceService){
       $files = array();
       $surplusList = array();

       if(!is_dir($destinationPath.'/'.$data['year'])){
            $year = $this->getMaxYear($product,$destinationPath);
            $sourceDir = $destinationPath.'/'.$year.'/';
            $destDir = $destinationPath.'/'.$data['year'].'/';
            mkdir($destDir);
            $sourcefiles = scandir($sourceDir);
            for($i = 2; $i < sizeof($sourcefiles) - 2; $i++)
            {
               copy($sourceDir.$sourcefiles[$i],$destDir.$sourcefiles[$i]);
            }    
       }

       $directories = scandir($destinationPath.'/'.$data['year']);
       for($i = 2; $i < sizeof($directories) - 2; $i++)
       {
         $surplusData = array();
         $filename = basename($destinationPath.'/'.$data['year'].'/'.$directories[$i],'.tpl');
         $surplusData['state'] = $this->getStateName($filename,$persistenceService);
         $surplusData['surplusLine'] = file_get_contents($destinationPath.'/'.$data['year'].'/'.$directories[$i]);
         array_push($surplusList,$surplusData);       
        }

        
       return $surplusList;
    }

    private function getMaxYear($product,$destinationPath){
        $directories = scandir($destinationPath, 1);
        return $directories[0];
    }

    private function getStateName($state,$persistenceService){
        $selectQuery = "SELECT state FROM state_license WHERE state_in_short = '".$state."'";
        $result = $persistenceService->selectQuery($selectQuery);
        while ($result->next()) {
            $state = $result->current();
        }
        return $state['state'];
    }
}
