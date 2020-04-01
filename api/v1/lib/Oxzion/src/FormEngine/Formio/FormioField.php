<?php
namespace Oxzion\FormEngine\Formio;

class FormioField
{
    protected $data;
    public function __construct($field)
    {
        $this->data['name'] = $field['key'];
        $this->data['text'] = $field['label'];
        if (isset($field['inputType'])) {
            $this->data['data_type'] = $field['inputType'];
        } else {
            $this->data['data_type'] = $field['type'];
        }
        $this->data['template'] = json_encode($field);
        if (isset($field['data'])) {
            $this->data['options'] = json_encode($field['data']);
        }
        if (isset($field['placeholder'])) {
            $this->data['helpertext'] = $field['placeholder'];
        }
        if(isset($field['properties']) && count($field['properties'])){
            if(isset($field['properties']['data_type'])){
                $this->data['data_type'] = $field['properties']['data_type'];
            }
        }
        if (isset($field['validate'])) {
            $this->data['required'] = isset($field['validate']['required'])?$field['validate']['required']:0;
        }
        if(isset($field['protected']) && ($field['protected']==1 || $field['protected']==false)){
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
