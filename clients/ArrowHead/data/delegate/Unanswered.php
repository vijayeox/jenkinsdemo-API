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
        return $finalFieldList;
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
                unset($unansweredQuestions[$key]);
            }
        }
    }

    private function getAnsweredQuestionsPrintReady($finalFieldList,&$answeredQuestions) {
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
                    $this->getAnsweredQuestionsPrintReady($finalFieldList,$answeredQuestions[$label]);
                } else {
                    $answeredQuestions[$label] = $value;
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
        if(isset($data['textField1'])) {
            unset($data['textField1']);
        }
        if(isset($data['validationMessage'])) {
            unset($data['validationMessage']);
        }
        if(isset($data['textField'])) {
            unset($data['textField']);
        }
        if(isset($data['submissionTime'])) {
            unset($data['submissionTime']);
        }
        if(isset($data['workFlowId'])) {
            unset($data['workFlowId']);
        }
        if(isset($data['documentsToBeGenerated'])) {
            unset($data['documentsToBeGenerated']);
        }
        if(isset($data['documentsSelectedCount'])) {
            unset($data['documentsSelectedCount']);
        }
        if(isset($data['umbrellaWarning'])) {
            unset($data['umbrellaWarning']);
        }
        if(isset($data['tankIndex'])) {
            unset($data['tankIndex']);
        }
        if(isset($data['locationNum'])) {
            unset($data['locationNum']);
        }
        if(isset($data['textFieldIgnore'])) {
            unset($data['textFieldIgnore']);
        }
        if(isset($data['managementSubmitApplication'])) {
            unset($data['managementSubmitApplication']);
        }
        if(isset($data['textFieldIgnore'])) {
            unset($data['textFieldIgnore']);
        }
    }

    private function answeredQuestionsDataMassaging(&$answeredQuestions) {
        //Coinsurance radio button
        if(isset($answeredQuestions['buildings'])) {
            foreach ($answeredQuestions['buildings'] as $key1 => $value1) {
                if(isset($value1['coinsuranceform'])) {
                    if($value1['coinsuranceform'] == 'yes') {
                        $answeredQuestions['buildings'][$key1]['coinsuranceform'] = "Coinsurance";
                    } else {
                        $answeredQuestions['buildings'][$key1]['coinsuranceform'] = "Monthly Limit";
                    }
                }
            }
        }
        //Remove location summary
        if(isset($answeredQuestions['financialsYTDSales'])) {
            foreach ($answeredQuestions['financialsYTDSales'] as $key => $value) {
                if(isset($value['total_newAutos'])) {
                    unset($answeredQuestions['financialsYTDSales'][$key]['total_newAutos']);
                }
                if(isset($value['total_usedAutos'])) {
                    unset($answeredQuestions['financialsYTDSales'][$key]['total_usedAutos']);
                }
                if(isset($value['total_fAndI'])) {
                    unset($answeredQuestions['financialsYTDSales'][$key]['total_fAndI']);
                }
                if(isset($value['total_rentalLeasing'])) {
                    unset($answeredQuestions['financialsYTDSales'][$key]['total_rentalLeasing']);
                }
                if(isset($value['total_service'])) {
                    unset($answeredQuestions['financialsYTDSales'][$key]['total_service']);
                }
                if(isset($value['total_bodyShop'])) {
                    unset($answeredQuestions['financialsYTDSales'][$key]['total_bodyShop']);
                }
                if(isset($value['total_parts'])) {
                    unset($answeredQuestions['financialsYTDSales'][$key]['total_parts']);
                }
                if(isset($value['total_total'])) {
                    unset($answeredQuestions['financialsYTDSales'][$key]['total_total']);
                }
            }
        }
        if(isset($answeredQuestions['financialsYTDGrossProfits'])) {
            foreach ($answeredQuestions['financialsYTDGrossProfits'] as $key => $value) {
                if(isset($value['total_newAutos'])) {
                    unset($answeredQuestions['financialsYTDGrossProfits'][$key]['total_newAutos']);
                }
                if(isset($value['total_usedAutos'])) {
                    unset($answeredQuestions['financialsYTDGrossProfits'][$key]['total_usedAutos']);
                }
                if(isset($value['total_fAndI'])) {
                    unset($answeredQuestions['financialsYTDGrossProfits'][$key]['total_fAndI']);
                }
                if(isset($value['total_rentalLeasing'])) {
                    unset($answeredQuestions['financialsYTDGrossProfits'][$key]['total_rentalLeasing']);
                }
                if(isset($value['total_service'])) {
                    unset($answeredQuestions['financialsYTDGrossProfits'][$key]['total_service']);
                }
                if(isset($value['total_bodyShop'])) {
                    unset($answeredQuestions['financialsYTDGrossProfits'][$key]['total_bodyShop']);
                }
                if(isset($value['total_parts'])) {
                    unset($answeredQuestions['financialsYTDGrossProfits'][$key]['total_parts']);
                }
                if(isset($value['total_total'])) {
                    unset($answeredQuestions['financialsYTDGrossProfits'][$key]['total_total']);
                }
            }
        }
        if(isset($answeredQuestions['financialsYTDSalesAnnualized'])) {
            foreach ($answeredQuestions['financialsYTDSalesAnnualized'] as $key => $value) {
                if(isset($value['total_newAutos'])) {
                    unset($answeredQuestions['financialsYTDSalesAnnualized'][$key]['total_newAutos']);
                }
                if(isset($value['total_usedAutos'])) {
                    unset($answeredQuestions['financialsYTDSalesAnnualized'][$key]['total_usedAutos']);
                }
                if(isset($value['total_fAndI'])) {
                    unset($answeredQuestions['financialsYTDSalesAnnualized'][$key]['total_fAndI']);
                }
                if(isset($value['total_rentalLeasing'])) {
                    unset($answeredQuestions['financialsYTDSalesAnnualized'][$key]['total_rentalLeasing']);
                }
                if(isset($value['total_service'])) {
                    unset($answeredQuestions['financialsYTDSalesAnnualized'][$key]['total_service']);
                }
                if(isset($value['total_bodyShop'])) {
                    unset($answeredQuestions['financialsYTDSalesAnnualized'][$key]['total_bodyShop']);
                }
                if(isset($value['total_parts'])) {
                    unset($answeredQuestions['financialsYTDSalesAnnualized'][$key]['total_parts']);
                }
                if(isset($value['total_total'])) {
                    unset($answeredQuestions['financialsYTDSalesAnnualized'][$key]['total_total']);
                }
            }
        }
        if(isset($answeredQuestions['financialsYTDGrossProfitsAnnualized'])) {
            foreach ($answeredQuestions['financialsYTDGrossProfitsAnnualized'] as $key => $value) {
                if(isset($value['total_newAutos'])) {
                    unset($answeredQuestions['financialsYTDGrossProfitsAnnualized'][$key]['total_newAutos']);
                }
                if(isset($value['total_usedAutos'])) {
                    unset($answeredQuestions['financialsYTDGrossProfitsAnnualized'][$key]['total_usedAutos']);
                }
                if(isset($value['total_fAndI'])) {
                    unset($answeredQuestions['financialsYTDGrossProfitsAnnualized'][$key]['total_fAndI']);
                }
                if(isset($value['total_rentalLeasing'])) {
                    unset($answeredQuestions['financialsYTDGrossProfitsAnnualized'][$key]['total_rentalLeasing']);
                }
                if(isset($value['total_service'])) {
                    unset($answeredQuestions['financialsYTDGrossProfitsAnnualized'][$key]['total_service']);
                }
                if(isset($value['total_bodyShop'])) {
                    unset($answeredQuestions['financialsYTDGrossProfitsAnnualized'][$key]['total_bodyShop']);
                }
                if(isset($value['total_parts'])) {
                    unset($answeredQuestions['financialsYTDGrossProfitsAnnualized'][$key]['total_parts']);
                }
                if(isset($value['total_total'])) {
                    unset($answeredQuestions['financialsYTDGrossProfitsAnnualized'][$key]['total_total']);
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
        $finalFieldList = $this->preProcessFields($fieldList);

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

        $this->getAllUnansweredAndAnsweredQuestions($fileData,$unansweredQuestions, $answeredQuestions);
        $this->answeredQuestionsDataMassaging($answeredQuestions);

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
        $this->getAnsweredQuestionsPrintReady($finalFieldList,$answeredQuestions);
        $generatedDocument = $this->documentBuilder->generateDocument('AnsweredQuestions',array('data' => json_encode($answeredQuestions)),$dest2);
        $data['answeredQuestionsDocument'] = $this->baseUrl."/".$data['appId']."/data/".AuthContext::get(AuthConstants::ORG_UUID)."/temp/".$uuid."/Answered.pdf";
        return $data;
    }
}
