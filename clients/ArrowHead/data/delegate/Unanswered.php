<?php

use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\FieldTrait;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\Utils\FileUtils;

class Unanswered extends AbstractDocumentAppDelegate
{
    use FieldTrait;
    use FileTrait;

    protected $template;

    public function __construct()
    {
        parent::__construct();
    }

    //results for array1 (when it is in more, it is in array1 and not in array2. same for less)
    private function compare_multi_Arrays($array1, $array2){
        $result = array("more"=>array(),"less"=>array(),"diff"=>array());
        foreach($array1 as $k => $v) {
          if(is_array($v) && isset($array2[$k]) && is_array($array2[$k])){
            $sub_result = $this->compare_multi_Arrays($v, $array2[$k]);
            //merge results
            foreach(array_keys($sub_result) as $key){
              if(!empty($sub_result[$key])){
                $result[$key] = array_merge_recursive($result[$key],array($k => $sub_result[$key]));
              }
            }
          }else{
            if(isset($array2[$k])){
              if($v !== $array2[$k]){
                $result["diff"][$k] = array("from"=>$v,"to"=>$array2[$k]);
              }
            }else{
              $result["more"][$k] = $v;
            }
          }
        }
        foreach($array2 as $k => $v) {
            if(!isset($array1[$k])){
                $result["less"][$k] = $v;
            }
        }
        return $result;
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
                if($field['type'] == 'hidden') {
                    continue;
                }
                if($field['type'] == 'file' || $field['type'] == 'document') {
                    return false;
                }
                if($field['template']) {
                    $label = json_decode($field['template'],true);
                    if(isset($label['validate']['customMessage'])){
                        $label = $label['validate']['customMessage'];
                    }
                    else {
                        $label = $label['label'];
                    }
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
            if(isset($label['validate']['customMessage'])){
                $label = $label['validate']['customMessage'];
            }
            else {
                $label = $label['label'];
            }
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
            if( ($field['type'] == 'hidden') || ($field['type'] == 'file') || ($field['type'] == 'document') ) {
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

    private function getTempLocation($name) {
        $dest =  tempnam(sys_get_temp_dir(),$name);
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
                $unansweredQuestions[$key]['label'] = isset($finalFieldList[$key]) ? $finalFieldList[$key] : $this->getLabel($fieldList,$key);
                $this->getUnansweredQuestionPrintReady($finalFieldList,$unansweredQuestions[$key],$fieldList);
            } else {
                if($key != "label") {
                    $unansweredQuestions[$key] = isset($finalFieldList[$key]) ? $finalFieldList[$key] : $this->getLabel($fieldList,$key);
                }
            }
        }
    }

    private function getAnsweredQuestionsPrintReady($finalFieldList,&$answeredQuestions) {
        foreach ($answeredQuestions as $key => $value) {
            if(isset($finalFieldList[$key]) || is_numeric($key)){
                $label = is_numeric($key) ? $key : $finalFieldList[$key];
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

    public function execute(array $data, Persistence $persistenceService)
    {
        // $this->logger->info("Executing Unanswered Delegate with Data" . print_r($data,true));
        $unansweredQuestionArr = $unansweredQuestionFields = array();

        //Get temp location to save file
        $dest = $this->getTempLocation('Unanswered');
        $dest2 = $this->getTempLocation('Answered');

        //List of all field for dealer policy
        $fieldListArr = $this->getFields($data['appId'],array('entityName' => 'Dealer Policy'));
        $fieldList = isset($fieldListArr['data']) ? $fieldListArr['data'] : array();
        $finalFieldList = $this->preProcessFields($fieldList);
        // echo "<pre>";print_r($finalFieldList);exit;

        $fileDataRaw = $this->getFile($data['fileId']);
        $fileData = $fileDataRaw['data'];

        $answeredQuestions = [];
        $unansweredQuestions = [];
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
                        $rowData[$api_parts[1][0]]['label'] = isset($finalFieldList[$key]) ? $finalFieldList[$key] : $this->getLabel($fieldList,$api_parts[1][0]);
                        $rowData = &$rowData[$api_parts[1][0]][$api_parts[2][0]];
                    } else {
                        $label = isset($finalFieldList[$key]) ? $finalFieldList[$key] : $this->getLabel($fieldList,$api_part);
                        $rowData[$api_part] = $label;
                    }
                }
            }

            $this->removeRequiredFromUnanswered($unansweredQuestions,$requiredUnansweredQuestions);
            $this->getUnansweredQuestionPrintReady($finalFieldList,$unansweredQuestions,$fieldList);
            $unansweredQuestionsArray = array('unansweredQuestions' => $unansweredQuestions, 'requiredUnansweredQuestions' => $requiredUnansweredQuestions );

            $generatedDocument = $this->documentBuilder->generateDocument('UnansweredQuestions',array('data' => json_encode($unansweredQuestionsArray)),$dest);
            $data['unansweredQuestionsDocument'] = $generatedDocument;
        }

        $this->getAnsweredQuestionsPrintReady($finalFieldList,$answeredQuestions);
        $generatedDocument = $this->documentBuilder->generateDocument('AnsweredQuestions',array('data' => json_encode($answeredQuestions)),$dest2);
        $data['answeredQuestions'] = $generatedDocument;
        return $data;
    }
}
