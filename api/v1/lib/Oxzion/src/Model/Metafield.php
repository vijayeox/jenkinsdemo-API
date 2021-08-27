<?php
namespace Oxzion\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Metafield extends Entity
{
    protected $data = array(
        'id'=>0,
        'name'=>null,
        'account_id'=>null,
        'text'=>null,
        'data_type'=>null,
        'options'=>null,
        'expression'=>null,
        'validationtext'=>null,
        'helpertext'=>null,
        'created_by'=>null,
        'modified_by'=>null,
        'date_created'=>null,
        'date_modified'=>null,
    );
    public function validate()
    {
        $required = array('name','account_id','formid','data_type','sequence');
        $this->validateWithParams($required);
    }
}
