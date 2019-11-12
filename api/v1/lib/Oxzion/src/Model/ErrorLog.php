<?php
namespace Oxzion\Model;

use Oxzion\ValidationException;
use Oxzion\Model\Entity;

class ErrorLog extends Entity
{
    protected $data = array(
        'id'=>0,
        'error_type'=>null,
        'error_trace'=>null,
        'payload'=>null,
        'params'=>null,
        'date_created'=>null
    );
    public function validate()
    {
        
    }
}
