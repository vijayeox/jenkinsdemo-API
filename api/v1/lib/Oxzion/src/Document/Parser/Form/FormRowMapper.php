<?php
namespace Oxzion\Document\Parser\Form;

use Oxzion\Document\Parser\Spreadsheet\BaseRowMapper;

class FormRowMapper extends BaseRowMapper{
    private $prevParentField = "";
    private $prevField = "";
    const COLUMNS = array("NAME",
                          "LABEL",
                          "PARENT",
                          "TYPE",
                          "DATA_TYPE",
                          "ITEM_NAME",
                          "ITEM_LABEL",
                          "PLACEHOLDER",
                          "DEFAULT",
                          "REQUIRED",
                          "MIN",
                          "MAX",
                          'DECIMAL_PLACES',
                          "PATTERN",
                          "FORMAT",
                          "ERROR_MSG",
                          "MASK");
    /**
    *   This method needs to be overridden by the custom implementations 
    */
    public function mapRow($rowData){
        $rowData = $this->convertToNamedArray($rowData);
        $name = $rowData['NAME'];
        if((!isset($name) || empty($name))){
            if($this->prevField == ""){
                return;
            }else{
                $name = $this->prevField;
            }
        }
        $fields = null;
        $parent = null;
        $parentField = $rowData['PARENT'];
        if(empty($parentField) && $this->prevParentField != "" && $name == $this->prevField){
            $parentField = $this->prevParentField;
        }
        if(!empty($parentField)){
            $parent = $this->mappedData[$parentField];
            if(isset($parent['FIELDS'])){
                $fields = $parent['FIELDS'];
            }else{
                $fields = array();
            }
            
        }
        $this->prevParentField = $parentField;    
        $rowData['REQUIRED'] = $rowData['REQUIRED'] == 'Yes' ? TRUE : FALSE;    
        if($this->prevField != $name ){
            $data = $rowData;
            $this->prevField = $name; 
        }else{
            if($parentField != ""){
                $data = isset($fields[$name]) ? $fields[$name] : $rowData;
            }else{
                $data = $this->mappedData[$name];    
            }
        }

        $item = $rowData['ITEM_NAME'];
        if(isset($item)){
            if(!isset($data['ITEMS'])){
                $itemList = array();
            }else{
                $itemList = $data['ITEMS'];
            }
            $itemDetails = $rowData;
            if(isset($rowData['ITEM_LABEL'])){
                $itemDetails["LABEL"] = $rowData['ITEM_LABEL'];
            }
            unset($itemDetails["ITEM_NAME"]);
            unset($itemDetails["NAME"]);
            unset($itemDetails["PARENT"]);
            unset($itemDetails["TYPE"]);
            unset($itemDetails["DATA_TYPE"]);
            unset($itemDetails["ITEM_LABEL"]);
            $itemList[$item] = $itemDetails;
            $data['ITEMS'] = $itemList;
        }
        unset($data['ITEM_NAME']);
        unset($data['ITEM_LABEL']);
        if($parentField != ""){
            $fields[$name] = $data;
            $parent['FIELDS'] = $fields;
            $this->mappedData[$parentField] = $parent;
        }else{
            $this->mappedData[$name] = $data;
        }
    }

    private function convertToNamedArray($rowData){
        $result = array();
        foreach (FormRowMapper::COLUMNS as $key => $column) {
            $result[$column] = isset($rowData[$key]) ? (trim($rowData[$key]) != "" ? $rowData[$key] : NULL ) : NULL;
        }

        return $result;
    }
    
}
