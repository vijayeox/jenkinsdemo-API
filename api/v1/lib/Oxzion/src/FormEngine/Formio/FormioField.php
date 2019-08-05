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
        if (isset($field['validate'])) {
            $this->data['required'] = isset($field['validate']['required'])?$field['validate']['required']:0;
        }
    }
    public function toArray()
    {
        return $this->data;
    }
}
