<?php

namespace Oxzion\Model\Esign;

use Oxzion\Type;
use Oxzion\Model\Entity;
use Oxzion\EntityNotFoundException;

class EsignDocument extends Entity
{
    const IN_PROGRESS = 'IN_PROGRESS';
    const COMPLETED = 'COMPLETED';

    protected static $MODEL = [
        'id' =>             ['type' => Type::INTEGER,   'readonly' => true , 'required' => false],
        'ref_id' =>         ['type' => Type::STRING,    'readonly' => false, 'required' => true],
        'uuid' =>           ['type' => Type::UUID,      'readonly' => false, 'required' => false],
        'doc_id' =>         ['type' => Type::STRING,    'readonly' => false, 'required' => false],
        'docPath' =>        ['type' => Type::STRING,    'readonly' => false, 'required' => false],
        'status' =>         ['type' => Type::STRING,    'readonly' => false, 'required' => true, 'value' => self::IN_PROGRESS],
        'date_created' =>   ['type' => Type::TIMESTAMP, 'readonly' => true,  'required' => false],
        'created_by' =>     ['type' => Type::INTEGER,   'readonly' => true,  'required' => false]
    ];

    public function &getModel()
    {
        return self::$MODEL;
    }

    public function loadByDocId($docId)
    {
        $obj = $this->table->getByDocId($docId);
        if (is_null($obj) || (0 == count($obj))) {
            throw new EntityNotFoundException('Entity not found.', ['entity' => $this->table->getTableGateway()->getTable(), 'doc_id' => $docId]);
        }
        $this->assignInternal($obj->toArray(), false);
        return $this;
    }
}
