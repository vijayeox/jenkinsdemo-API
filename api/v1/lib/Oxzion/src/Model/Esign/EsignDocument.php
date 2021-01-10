<?php

namespace Oxzion\Model\Esign;

use Oxzion\Type;
use Oxzion\Model\Entity;

class EsignDocument extends Entity
{
    const IN_PROGRESS = 'IN_PROGRESS';
    const COMPLETED = 'COMPLETED';

    protected static $MODEL = [
        'id' =>             ['type' => Type::INTEGER,   'readonly' => TRUE , 'required' => FALSE],
        'ref_id' =>         ['type' => Type::STRING,    'readonly' => FALSE, 'required' => TRUE],
        'uuid' =>           ['type' => Type::UUID,      'readonly' => FALSE,  'required' => FALSE],
        'doc_id' =>         ['type' => Type::STRING,     'readonly' => FALSE,  'required' => FALSE],
        'status' =>    ['type' => Type::STRING,    'readonly' => FALSE, 'required' => TRUE, 'value' => self::IN_PROGRESS],
        'date_created' =>   ['type' => Type::TIMESTAMP, 'readonly' => TRUE,  'required' => FALSE],
        'created_by' =>     ['type' => Type::INTEGER,   'readonly' => TRUE,  'required' => FALSE]
    ];

    public function &getModel() {
        return self::$MODEL;
    }

    public function loadByDocId($docId){
        $obj = $this->table->getByDocId($docId);
        if (is_null($obj) || (0 == count($obj))) {
            throw new EntityNotFoundException('Entity not found.',
                ['entity' => $this->table->getTableGateway()->getTable(), 'doc_id' => $docId]);
        }
        $this->assignInternal($obj->toArray(), false);
        return $this;
    }
}
