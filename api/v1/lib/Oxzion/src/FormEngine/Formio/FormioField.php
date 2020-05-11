<?php
namespace Oxzion\FormEngine\Formio;
use Logger;

class FormioField
{
    protected $data; 
    protected $logger;

    protected function initLogger()
    {
        $this->logger = Logger::getLogger(__CLASS__);
    }
    public function __construct($field)
    {   
        $this->initLogger();
        $this->data['name'] = $field['key'];
        $this->data['text'] = $field['label'];
        if(isset($field['parent'])){
            $parent = new FormioField($field['parent']);
            $this->data['parent'] = $parent->toArray();
        }
        $this->data['type'] = isset($field['type']) ? $field['type'] : $field['inputType'];
        if(isset($field['properties']) && count($field['properties'])){
            if(isset($field['properties']['data_type'])){
                $this->data['data_type'] = $field['properties']['data_type'];
            }
        }
        switch ($this->data['type']) {
            case 'day':
                foreach ($field['fields'] as $key => $value) {
                 if (isset($value['required']) == 1) {
                    $this->data['required'] = 1;
                 }
                 if (isset($value['hide']) && (($value['hide'] == 1) || ($value['hide'] == true) || ($value['hide'] == 'true'))) {
                     $this->data['data_type'] = 'text';
                 }else{
                    $this->data['data_type'] = 'date';
                 }
                }
                break;
            case 'select':
                $this->data['data_type'] = isset($field['multiple'])? 'list':'text';
                break;
            case 'checkbox':
                $this->data['data_type'] = 'boolean';
                break;
            case 'number':
            case 'currency':
                $this->data['data_type'] = 'numeric';
                break;
            case 'datetime':
                $this->data['data_type'] = 'datetime';
                break;
            case 'time':
                $this->data['data_type'] = 'time';
                break;
            case 'Date':
                $this->data['data_type'] = 'date';
                break;
            case 'file':
            case 'document';
            case 'signature':
                $this->data['data_type'] = 'file';
                break;
            case 'selectboxes':
            case 'tags':
            $this->data['data_type'] = 'list';
            break;
            case 'datagrid':
            case 'editgrid':
                $this->data['data_type'] = 'grid';
                break;
            case 'survey':
                $this->data['data_type'] = 'survey';
                break;
            case 'datamap':
                $this->data['data_type'] = 'map';
                break;
            case 'url':
                $this->data['data_type'] = 'url';
                break;
            default:
                $this->data['data_type'] = 'text';
                break;
        }
        $this->data['template'] = json_encode($field);
        if (isset($field['data'])) {
            $this->data['options'] = json_encode($field['data']);
        }
        if (isset($field['placeholder'])) {
            $this->data['helpertext'] = $field['placeholder'];
        }
        if (isset($field['validate'])) {
            $this->data['required'] = isset($field['validate']['required'])?$field['validate']['required']:0;
        }
        if(isset($field['protected']) && ($field['protected']==1 || $field['protected']==true)){
            $this->data = null;
        }
        if(isset($field['persistent']) && ($field['persistent']==false || $field['persistent']==0  || $field['persistent']=='')){
            $this->data = null;
        }
    }
    public function toArray()
    {
        return $this->data;
    }
}
