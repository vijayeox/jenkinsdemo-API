<?php

namespace Oxzion\Model\Entity;
use Oxzion\Model\Entity;
use Oxzion\Model\Table\AppTable;

class App extends Entity{

    protected $data = array(
        'id' => NULL,
        'name' => NULL,
        'description' => NULL,
        'sequence' => NULL,
        'htmltext' => NULL,
        'type' => NULL,
        'viewtype' => NULL,
        'customname' => NULL,
        'logo' => NULL,
        'email' => 'Active',
        'appcolor' => 'blue',
        'helppdf' => NULL,
        'matrix_reference_name' => NULL,
        'hidepivotgrid0' => NULL,
        'hidepivotgrid1' => NULL,
        'hidepivotgrid2' => NULL,
    );
    public function __construct($data=null){
        $this->tablename = 'modules';
        parent::__construct($data,$this);
    }
    public function getForms($orgid){
        $select = $this->sql->select()
        ->from('metaforms')
        ->columns(array('*'))
        ->where(array('moduleid' => $this->data['id'],'orgid'=>$orgid));
        $selectString = $this->sql->getSqlStringForSqlObject($select);
        return $this->queryExecute($select);
    }
}