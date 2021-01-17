<?php

namespace Oxzion\Model\Esign;

use Oxzion\Type;
use Oxzion\Model\Entity;

class EsignDocumentSigner extends Entity
{
    const IN_PROGRESS = 'IN_PROGRESS';
    const COMPLETED = 'COMPLETED';

    protected static $MODEL = [
        'id' =>                   ['type' => Type::INTEGER,   'readonly' => TRUE , 'required' => FALSE],
        'esign_document_id' =>    ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => TRUE],
        'email' =>                ['type' => Type::STRING,    'readonly' => FALSE,  'required' => FALSE],
        'status' =>               ['type' => Type::STRING,    'readonly' => FALSE, 'required' => TRUE, 'value' => self::IN_PROGRESS],
        'date_modified' =>        ['type' => Type::TIMESTAMP, 'readonly' => TRUE,  'required' => FALSE],
        'details' =>              ['type' => Type::STRING,    'readonly' => FALSE,  'required' => TRUE]
    ];

    public function &getModel() {
        return self::$MODEL;
    }
}
