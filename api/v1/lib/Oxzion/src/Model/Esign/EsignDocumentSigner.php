<?php

namespace Oxzion\Model\Esign;

use Oxzion\Type;
use Oxzion\Model\Entity;

class EsignDocumentSigner extends Entity
{
    const IN_PROGRESS = 'IN_PROGRESS';
    const COMPLETED = 'COMPLETED';

    protected static $MODEL = [
        'id' =>                   ['type' => Type::INTEGER,   'readonly' => true , 'required' => false],
        'esign_document_id' =>    ['type' => Type::INTEGER,   'readonly' => false, 'required' => true],
        'email' =>                ['type' => Type::STRING,    'readonly' => false,  'required' => false],
        'status' =>               ['type' => Type::STRING,    'readonly' => false, 'required' => true, 'value' => self::IN_PROGRESS],
        'date_modified' =>        ['type' => Type::TIMESTAMP, 'readonly' => true,  'required' => false],
        'details' =>              ['type' => Type::STRING,    'readonly' => false,  'required' => true]
    ];

    public function &getModel()
    {
        return self::$MODEL;
    }
}
