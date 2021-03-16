<?php

use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\FieldTrait;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\Utils\FileUtils;
use Oxzion\Utils\UuidUtil;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;

class Unanswered extends AbstractDocumentAppDelegate
{
    use FieldTrait;
    use FileTrait;

    protected $template;

    public function __construct()
    {
        parent::__construct();
    }

    private function isJson($string) {
        $array = json_decode($string, true);
        if(is_array($array) && json_last_error() == JSON_ERROR_NONE){
            return true;
        }
        return false;
    }

    private function getLabel($fieldList,$name) {
        $label = $name;
        $arr = [];
        foreach ($fieldList as $field) {
            if($name == $field['name']) {
                if($field['type'] == 'hidden' || $field['type'] == 'button' || $field['type'] == 'htmlelement') {
                    continue;
                }
                if($field['type'] == 'file' || $field['type'] == 'document') {
                    return false;
                }
                if($field['template']) {
                    $label = json_decode($field['template'],true);
                    $label = $label['label'];
                }
                else {
                    $label = $field['text'];
                }
            }
        }
        return $label;
    }

    private function getFieldLabel($field) {
        $label = $field['name'];
        if($field['template']) {
            $label = json_decode($field['template'],true);
            $label = $label['label'];
        }
        else {
            $label = $field['text'];
        }
        return $label;
    }

    private function preProcessFields($fieldList) {
        $finalFieldList = [];
        //Array to identify the drop down fields that require further mapping
        //Example: 10000 -> $10000 or 0.85 -> 85%   
        $dropDownMappings = [];
        foreach ($fieldList as $field) {
            $label = $field['name'];
            if( ($field['type'] == 'hidden') || ($field['type'] == 'file') || ($field['type'] == 'document') || ($field['type'] == 'button') || ($field['type'] == 'htmlelement') ) {
                continue;
            }
            elseif($field['type'] == 'select') {
                //Flatten select component
                $finalFieldList[$label] = $this->getFieldLabel($field);
                $options = json_decode($field['options'],true);
                if(isset($options['values']) && !empty($options['values'])) {
                    $dropDownMappings[$label] = true;
                    foreach ($options['values'] as $key => $value) {
                        $finalFieldList[$value['value']] = $value['label'];
                    }
                }
            }
            elseif($field['type'] == 'selectboxes') {
                //Flatten select component
                $finalFieldList[$label] = $this->getFieldLabel($field);
                $template = json_decode($field['template'],true);
                if(isset($template['values']) && !empty($template['values'])) {
                    $dropDownMappings[$label] = true;
                    foreach ($template['values'] as $key => $value) {
                        $finalFieldList[$value['value']] = $value['label'];
                    }
                }
            }

            elseif($field['type'] == 'survey') {
                // Flatten survey component
                $finalFieldList[$label] = $this->getFieldLabel($field);
                $template = json_decode($field['template'],true);
                if(isset($template['questions']) && !empty($template['questions'])) {
                    $dropDownMappings[$label] = true;
                    foreach ($template['questions'] as $key => $value) {
                        $finalFieldList[$value['value']] = $value['label'];
                    }
                }
            }
            else {
                $finalFieldList[$label] = $this->getFieldLabel($field);
            }
        }
        foreach ($finalFieldList as $key => $value) {
            // Remove empty nodes
            if($value == '') {
                unset($finalFieldList[$key]);
            }
        }
        return Array(
            'finalFieldList' => $finalFieldList,
            'dropDownMappings' => $dropDownMappings
        );
    }

    private function getTempLocation($name,$uuid) {
        $dest =  $this->destination.AuthContext::get(AuthConstants::ORG_UUID)."/temp/".$uuid."/".$name;
        $dest = $dest.".pdf";
        $dest = strval($dest);
        $fileCheck = FileUtils::fileExists($dest);
        if($fileCheck) {
            unlink($dest);
        }
        return $dest;
    }

    private function getAllUnansweredAndAnsweredQuestions(&$fileData,&$unansweredQuestions,&$answeredQuestions) {
        foreach ($fileData as $key => &$value) {
            if(empty($value) && $value !== 0) {
                $unansweredQuestions[$key] = null;
            } elseif (is_array($value)) {
                $this->getAllUnansweredAndAnsweredQuestions($value,$unansweredQuestions[$key],$answeredQuestions[$key]);
                //remove empty nodes
                if (!$unansweredQuestions[$key]) {
                    unset($unansweredQuestions[$key]);
                }
                if (!$answeredQuestions[$key]) {
                    unset($answeredQuestions[$key]);
                }
            } elseif($this->isJson($value)){
                $value = json_decode($value,true);
                $this->getAllUnansweredAndAnsweredQuestions($value,$unansweredQuestions[$key],$answeredQuestions[$key]);
                //remove empty nodes
                if (!$unansweredQuestions[$key]) {
                    unset($unansweredQuestions[$key]);
                }
                if (!$answeredQuestions[$key]) {
                    unset($answeredQuestions[$key]);
                }
            } else {
                // Handle 1 and 0 showing up
                if(is_bool($value)) {
                    if($value) {
                        $value = "yes";
                    } else {
                        $value = "no";
                    }
                }
                $answeredQuestions[$key] = $value;
            }
        }
    }

    private function removeRequiredFromUnanswered(&$unansweredQuestions,&$requiredUnansweredQuestions) {
        foreach ($unansweredQuestions as $key => $value) {
            if(isset($requiredUnansweredQuestions[$key])) {
                if(is_array($requiredUnansweredQuestions[$key])) {
                    $this->removeRequiredFromUnanswered($unansweredQuestions[$key],$requiredUnansweredQuestions[$key]);
                } else {
                    unset($unansweredQuestions[$key]);
                }
            }
        }
    }

    private function getUnansweredQuestionPrintReady($finalFieldList,&$unansweredQuestions,$fieldList) {
        foreach ($unansweredQuestions as $key => $value) {
            if(is_array($value)) {
                if(!is_numeric($key)) {
                    $unansweredQuestions[$key]['label'] = isset($finalFieldList[$key]) ? $finalFieldList[$key] : null;
                    if($unansweredQuestions[$key]['label'] == null) {
                        unset($unansweredQuestions[$key]);
                        continue;
                    }
                }
                $this->getUnansweredQuestionPrintReady($finalFieldList,$unansweredQuestions[$key],$fieldList);
            } else {
                if($key != "label") {
                    $unansweredQuestions[$key] = isset($finalFieldList[$key]) ? $finalFieldList[$key] : null;
                }
            }
            if(!$unansweredQuestions[$key]) {
                unset($unansweredQuestions[$key]); //remove node elements
            }
            if(array_key_exists($key, $unansweredQuestions)) {
                //Remove empty arrays with only labels
                $temp = $unansweredQuestions;
                if(array_key_exists('label', $temp)) {
                    unset($temp['label']);
                    if(empty($temp)) {
                        unset($unansweredQuestions[$key]);
                    }
                }
            }
        }
    }

    private function getAnsweredQuestionsPrintReady($finalFieldList,&$answeredQuestions,$dropDownMappings) {
        $counters = array();
        foreach ($answeredQuestions as $key => $value) {
            if(isset($finalFieldList[$key]) || is_numeric($key)){
                $label = is_numeric($key) ? $key : $finalFieldList[$key];
                if(isset($answeredQuestions[$label]) && !is_numeric($label)) {
                    $counters[$label] = isset($counters[$label]) ? $counters[$label] + 1 : 1;
                    $label = strval($label."-(".$counters[$label].")");
                }
                if(is_array($value)) {
                    $answeredQuestions[$label] = $value;
                    $this->getAnsweredQuestionsPrintReady($finalFieldList,$answeredQuestions[$label],$dropDownMappings);
                } else {
                    $value = is_numeric($value)?(string)$value:$value;
                    if(isset($dropDownMappings[$key]) && isset($finalFieldList[$value])){
                        $answeredQuestions[$label] = $finalFieldList[$value];
                    }
                    else{
                        $answeredQuestions[$label] = $value;
                    }
                    
                }
                if($key != $label) {
                    unset($answeredQuestions[$key]);
                }
            } else {
                unset($answeredQuestions[$key]);
            }
        }
    }

    private function decodeFileData(&$data) {
        foreach ($data as $key => $value) {
            if(is_string($value) && $this->isJson($value)) {
                $data[$key] = json_decode($value,true);
            } if(is_array($value)) {
                $this->decodeFileData($data[$key]);
            }
        }
    }

    private function cleanData(&$data) {
        if(array_key_exists('textField1',$data)) {
            unset($data['textField1']);
        }
        if(array_key_exists('validationMessage',$data)) {
            unset($data['validationMessage']);
        }
        if(array_key_exists('textField',$data)) {
            unset($data['textField']);
        }
        if(array_key_exists('submissionTime',$data)) {
            unset($data['submissionTime']);
        }
        if(array_key_exists('workFlowId',$data)) {
            unset($data['workFlowId']);
        }
        if(array_key_exists('documentsToBeGenerated',$data)) {
            unset($data['documentsToBeGenerated']);
        }
        if(array_key_exists('documentsSelectedCount',$data)) {
            unset($data['documentsSelectedCount']);
        }
        if(array_key_exists('umbrellaWarning',$data)) {
            unset($data['umbrellaWarning']);
        }
        if(array_key_exists('tankIndex',$data)) {
            unset($data['tankIndex']);
        }
        if(array_key_exists('locationNum',$data)) {
            unset($data['locationNum']);
        }
        if(array_key_exists('textFieldIgnore',$data)) {
            unset($data['textFieldIgnore']);
        }
        if(array_key_exists('managementSubmitApplication', $data)) {
            unset($data['managementSubmitApplication']);
        }
        if(array_key_exists('textFieldIgnore',$data)) {
            unset($data['textFieldIgnore']);
        }
    }

    private function fileDataMassaging(&$fileData) {
        //Coinsurance radio button
        if(isset($fileData['buildings'])) {
            foreach ($fileData['buildings'] as $key1 => $value1) {
                if(isset($value1['coinsuranceform'])) {
                    if($value1['coinsuranceform'] == 'yes') {
                        $fileData['buildings'][$key1]['coinsuranceform'] = "Coinsurance";
                    } else {
                        $fileData['buildings'][$key1]['coinsuranceform'] = "Monthly Limit";
                    }
                }
            }
        }

        //Remove location summary from financials
        if(isset($fileData['financialsYTDSales'])) {
            foreach ($fileData['financialsYTDSales'] as $key => $value) {
                if(isset($value['total_newAutos'])) {
                    unset($fileData['financialsYTDSales'][$key]['total_newAutos']);
                }
                if(isset($value['total_usedAutos'])) {
                    unset($fileData['financialsYTDSales'][$key]['total_usedAutos']);
                }
                if(isset($value['total_fAndI'])) {
                    unset($fileData['financialsYTDSales'][$key]['total_fAndI']);
                }
                if(isset($value['total_rentalLeasing'])) {
                    unset($fileData['financialsYTDSales'][$key]['total_rentalLeasing']);
                }
                if(isset($value['total_service'])) {
                    unset($fileData['financialsYTDSales'][$key]['total_service']);
                }
                if(isset($value['total_bodyShop'])) {
                    unset($fileData['financialsYTDSales'][$key]['total_bodyShop']);
                }
                if(isset($value['total_parts'])) {
                    unset($fileData['financialsYTDSales'][$key]['total_parts']);
                }
                if(isset($value['total_total'])) {
                    unset($fileData['financialsYTDSales'][$key]['total_total']);
                }
            }
        }
        if(isset($fileData['financialsYTDGrossProfits'])) {
            foreach ($fileData['financialsYTDGrossProfits'] as $key => $value) {
                if(isset($value['total_newAutos'])) {
                    unset($fileData['financialsYTDGrossProfits'][$key]['total_newAutos']);
                }
                if(isset($value['total_usedAutos'])) {
                    unset($fileData['financialsYTDGrossProfits'][$key]['total_usedAutos']);
                }
                if(isset($value['total_fAndI'])) {
                    unset($fileData['financialsYTDGrossProfits'][$key]['total_fAndI']);
                }
                if(isset($value['total_rentalLeasing'])) {
                    unset($fileData['financialsYTDGrossProfits'][$key]['total_rentalLeasing']);
                }
                if(isset($value['total_service'])) {
                    unset($fileData['financialsYTDGrossProfits'][$key]['total_service']);
                }
                if(isset($value['total_bodyShop'])) {
                    unset($fileData['financialsYTDGrossProfits'][$key]['total_bodyShop']);
                }
                if(isset($value['total_parts'])) {
                    unset($fileData['financialsYTDGrossProfits'][$key]['total_parts']);
                }
                if(isset($value['total_total'])) {
                    unset($fileData['financialsYTDGrossProfits'][$key]['total_total']);
                }
            }
        }
        if(isset($fileData['financialsYTDSalesAnnualized'])) {
            foreach ($fileData['financialsYTDSalesAnnualized'] as $key => $value) {
                if(isset($value['total_newAutos'])) {
                    unset($fileData['financialsYTDSalesAnnualized'][$key]['total_newAutos']);
                }
                if(isset($value['total_usedAutos'])) {
                    unset($fileData['financialsYTDSalesAnnualized'][$key]['total_usedAutos']);
                }
                if(isset($value['total_fAndI'])) {
                    unset($fileData['financialsYTDSalesAnnualized'][$key]['total_fAndI']);
                }
                if(isset($value['total_rentalLeasing'])) {
                    unset($fileData['financialsYTDSalesAnnualized'][$key]['total_rentalLeasing']);
                }
                if(isset($value['total_service'])) {
                    unset($fileData['financialsYTDSalesAnnualized'][$key]['total_service']);
                }
                if(isset($value['total_bodyShop'])) {
                    unset($fileData['financialsYTDSalesAnnualized'][$key]['total_bodyShop']);
                }
                if(isset($value['total_parts'])) {
                    unset($fileData['financialsYTDSalesAnnualized'][$key]['total_parts']);
                }
                if(isset($value['total_total'])) {
                    unset($fileData['financialsYTDSalesAnnualized'][$key]['total_total']);
                }
            }
        }
        if(isset($fileData['financialsYTDGrossProfitsAnnualized'])) {
            foreach ($fileData['financialsYTDGrossProfitsAnnualized'] as $key => $value) {
                if(isset($value['total_newAutos'])) {
                    unset($fileData['financialsYTDGrossProfitsAnnualized'][$key]['total_newAutos']);
                }
                if(isset($value['total_usedAutos'])) {
                    unset($fileData['financialsYTDGrossProfitsAnnualized'][$key]['total_usedAutos']);
                }
                if(isset($value['total_fAndI'])) {
                    unset($fileData['financialsYTDGrossProfitsAnnualized'][$key]['total_fAndI']);
                }
                if(isset($value['total_rentalLeasing'])) {
                    unset($fileData['financialsYTDGrossProfitsAnnualized'][$key]['total_rentalLeasing']);
                }
                if(isset($value['total_service'])) {
                    unset($fileData['financialsYTDGrossProfitsAnnualized'][$key]['total_service']);
                }
                if(isset($value['total_bodyShop'])) {
                    unset($fileData['financialsYTDGrossProfitsAnnualized'][$key]['total_bodyShop']);
                }
                if(isset($value['total_parts'])) {
                    unset($fileData['financialsYTDGrossProfitsAnnualized'][$key]['total_parts']);
                }
                if(isset($value['total_total'])) {
                    unset($fileData['financialsYTDGrossProfitsAnnualized'][$key]['total_total']);
                }
            }
        }

        //Unnecessary hidden fields in location schedule
        if(isset($fileData['locations'])) {
            foreach ($fileData['locations'] as $key => $value) {
                if(array_key_exists('buildinginterest', $value)) {
                    unset($fileData['locations'][$key]['buildinginterest']);
                }
                if(array_key_exists('d', $value)) {
                    unset($fileData['locations'][$key]['d']);
                }
            }
        }

        if(isset($fileData['employeeList'])) {
            foreach ($fileData['employeeList'] as $key => $value) {
                if(array_key_exists('total_fTEmployeesFurnishedAnAuto', $value)) {
                    unset($fileData['employeeList'][$key]['total_fTEmployeesFurnishedAnAuto']);
                }
                if(array_key_exists('total_pTEmployeesFurnishedAnAuto', $value)) {
                    unset($fileData['employeeList'][$key]['total_pTEmployeesFurnishedAnAuto']);
                }
                if(array_key_exists('total_fTEmployeesWhoAreNotFurnished', $value)) {
                    unset($fileData['employeeList'][$key]['total_fTEmployeesWhoAreNotFurnished']);
                }
                if(array_key_exists('total_pTEmployeesWhoAreNotFurnished', $value)) {
                    unset($fileData['employeeList'][$key]['total_pTEmployeesWhoAreNotFurnished']);
                }
                if(array_key_exists('total_fTAllOtherEmployees', $value)) {
                    unset($fileData['employeeList'][$key]['total_fTAllOtherEmployees']);
                }
                if(array_key_exists('total_pTAllOtherEmployees', $value)) {
                    unset($fileData['employeeList'][$key]['total_pTAllOtherEmployees']);
                }
                if(array_key_exists('total_nonEmployeesUnderTheAge', $value)) {
                    unset($fileData['employeeList'][$key]['total_nonEmployeesUnderTheAge']);
                }
                if(array_key_exists('total_nonEmployeesYearsOldorolder', $value)) {
                    unset($fileData['employeeList'][$key]['total_nonEmployeesYearsOldorolder']);
                }
                if(array_key_exists('total_contractDriversNonEmployees', $value)) {
                    unset($fileData['employeeList'][$key]['total_contractDriversNonEmployees']);
                }
                if(array_key_exists('total_total', $value)) {
                    unset($fileData['employeeList'][$key]['total_total']);
                }
            }
        }

        if(isset($fileData['dol_12MonthAvg'])) {
            //Lack of default values
            foreach ($fileData['dol_12MonthAvg'] as $key => $value) {
                if(!array_key_exists('new', $value)) {
                    $fileData['dol_12MonthAvg'][$key]['new'] = null;
                }
                if(!array_key_exists('used', $value)) {
                    $fileData['dol_12MonthAvg'][$key]['used'] = null;
                }
                if(!array_key_exists('demosFurnishedAutos', $value)) {
                    $fileData['dol_12MonthAvg'][$key]['demosFurnishedAutos'] = null;
                }
                if(!array_key_exists('loanersShopService', $value)) {
                    $fileData['dol_12MonthAvg'][$key]['loanersShopService'] = null;
                }
                if(!array_key_exists('floor_new', $value)) {
                    $fileData['dol_12MonthAvg'][$key]['floor_new'] = null;
                }
                if(!array_key_exists('floor_used', $value)) {
                    $fileData['dol_12MonthAvg'][$key]['floor_used'] = null;
                }
                if(!array_key_exists('floor_demosFurnishedAutos', $value)) {
                    $fileData['dol_12MonthAvg'][$key]['floor_demosFurnishedAutos'] = null;
                }
                if(!array_key_exists('floor_loanersShopService', $value)) {
                    $fileData['dol_12MonthAvg'][$key]['floor_loanersShopService'] = null;
                }
            }
        }
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        // $this->logger->info("Executing Unanswered Delegate with Data" . print_r($data,true));
        $unansweredQuestionArr = $unansweredQuestionFields = array();
        $sequence = $data['sequence'];
        $uuid = UuidUtil::uuid();
        //Get temp location to save file
        $dest = $this->getTempLocation('Unanswered',$uuid);
        $dest2 = $this->getTempLocation('Answered',$uuid);

        //List of all field for dealer policy
        $fieldListArr = $this->getFields($data['appId'],array('entityName' => 'Dealer Policy'));
        $fieldList = isset($fieldListArr['data']) ? $fieldListArr['data'] : array();
        $processedFields = $this->preProcessFields($fieldList);
        $finalFieldList = $processedFields['finalFieldList'];
        $dropDownMappings = $processedFields['dropDownMappings'];
        $fileDataRaw = $this->getFile($data['fileId']);
        $fileData = $fileDataRaw['data'];

        $answeredQuestions = [];
        $unansweredQuestions = [];
        //For file data when it gets encoded and stored in the db
        $this->decodeFileData($fileData);
        //Sequence outer keys
        $temp = array();
        foreach ($sequence as $key => $value) {
            foreach ($fileData as $key1 => $value1) {
                if($value == $key1) {
                    $temp[$value] = $value1;
                }
            }
        }
        $fileData = $temp;

        $this->fileDataMassaging($fileData);
        $this->getAllUnansweredAndAnsweredQuestions($fileData,$unansweredQuestions, $answeredQuestions);

        //Data grids not filled to be added which can't be done for the children as the leaf nodes need to be removed
        foreach ($fileData as $key => $value) {
            if(is_string($value)) {
                $temp = json_decode($value,true);
                if(empty($temp) && is_array($temp)) {
                    $unansweredQuestions[$key] = null;
                }
            }
        }

        if(isset($data['unansweredQuestions'])) {
            $unansweredQuestionFields = array_column($data['unansweredQuestions'], 'api');
            $requiredUnansweredQuestions = [];
            //Required unanswered questions
            foreach (array_column($data['unansweredQuestions'],'api') as $api) {
                $rowData = &$requiredUnansweredQuestions;
                foreach (explode('.', $api) as $api_part) {
                    if (preg_match_all('/^(\w+)\[(\d+)\]$/', $api_part, $api_parts)) {
                        $rowData[$api_parts[1][0]]['label'] = isset($finalFieldList[$api_part]) ? $finalFieldList[$api_part] : $this->getLabel($fieldList,$api_parts[1][0]);
                        $rowData = &$rowData[$api_parts[1][0]][$api_parts[2][0]];
                    } else {
                        $label = isset($finalFieldList[$api_part]) ? $finalFieldList[$api_part] : $this->getLabel($fieldList,$api_part);
                        $rowData[$api_part] = $label;
                    }
                }
            }

            $this->removeRequiredFromUnanswered($unansweredQuestions,$requiredUnansweredQuestions);
            $this->cleanData($unansweredQuestions);
            $this->getUnansweredQuestionPrintReady($finalFieldList,$unansweredQuestions,$fieldList);
            $unansweredQuestionsArray = array('unansweredQuestions' => $unansweredQuestions, 'requiredUnansweredQuestions' => $requiredUnansweredQuestions );
            $generatedDocument = $this->documentBuilder->generateDocument('UnansweredQuestions',array('data' => json_encode($unansweredQuestionsArray)),$dest);
            $data['unansweredQuestionsDocument'] = $this->baseUrl."/".$data['appId']."/data/".AuthContext::get(AuthConstants::ORG_UUID)."/temp/".$uuid."/Unanswered.pdf";
        }

        $this->cleanData($answeredQuestions);
        $this->getAnsweredQuestionsPrintReady($finalFieldList,$answeredQuestions,$dropDownMappings);
        $generatedDocument = $this->documentBuilder->generateDocument('AnsweredQuestions',array('data' => json_encode($answeredQuestions)),$dest2);
        $data['answeredQuestionsDocument'] = $this->baseUrl."/".$data['appId']."/data/".AuthContext::get(AuthConstants::ORG_UUID)."/temp/".$uuid."/Answered.pdf";
        return $data;
    }
}
