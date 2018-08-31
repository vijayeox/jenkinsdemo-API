<?php

namespace Oxzion\Model\Entity;
use Oxzion\Model\Entity;
use Oxzion\Utils\Utilities;

class File extends Entity{

    protected $data = array(
        'id' => NULL,
        'assessid' => NULL,
        'name' => NULL,
        'description' => NULL,
        'htmltext' => NULL,
        'leaf' => NULL,
        'color' => NULL,
        'durationunit' => NULL,
        'percentdone' => NULL,
        'duration' => NULL,
        'orgid' => NULL,
        'formid' => NULL,
        'createdid' => NULL,
        'original_createdid' => NULL,
        'modifiedid' => NULL,
        'assignedto' => NULL,
        'assignedgroup' => NULL,
        'date_created' => NULL,
        'date_modified' => NULL,
        'ownergroupid' => NULL,
        'parentinstformid' => NULL,
        'status' => NULL,
        'duplicate' => NULL,
        'startdate' => NULL,
        'nextactiondate' => NULL,
        'emailaddress1' => NULL,
        'enddate' => NULL,
        'cost' => NULL,
        'starpoints' => NULL,
        'testerid' => NULL,
        'testercode' => NULL,
        'field3' => NULL,
        'tags' => NULL,
        'category' => NULL,
        'goals' => NULL,
        'kracategory' => NULL,
        'krasubcategory' => NULL,
        'observer' => NULL,
        'location' => NULL,
        'pod' => '0',
        'observeravatardel' => NULL,
        'observergroupdel' => NULL,
        'comment_moderator' => '1',
        'reffield1' => NULL,
        'reffield2' => NULL,
        'reffield3' => NULL,
        'reffield4' => NULL,
        'reffield5' => NULL,
    );
    protected $obj = array();

    public function __construct($data=null){
        $this->tablename = 'instanceforms';
        parent::__construct($data,$this);
        if($this->data['id']){
            $this->constructObj();
        }
    }
    public function checkAccess($avatar){
        $groups = array_column($avatar->getGroups(),"id");
        if(in_array($this->data['assignedgroup'], $groups)||in_array($this->data['ownergroupid'], $groups)||$this->data['assignedto']==$avatar->id||$this->data['createdid']==$avatar->id||$this->data['original_createdid']==$avatar->id){
            return $this->constructObj();
        } else {
            return array('response'=>'No Access');
        }
    }
    public function constructObj(){
        $select = $this->sql->select()
        ->from('instanceforms')
        ->columns(array('*'))
        ->join(array('a' => 'avatars'), 'a.id = instanceforms.assignedto',array('assignedtoname'=>'name'))
        ->join(array('b' => 'avatars'), 'b.id = instanceforms.createdid',array('createdbyname'=>'name'))
        ->join(array('c' => 'avatars'), 'c.id = instanceforms.original_createdid',array('origcreatedbyname'=>'name'))
        ->join(array('d' => 'avatars'), 'd.id = instanceforms.modifiedid',array('modifiedidname'=>'name'))
        ->join(array('e' => 'groups'), 'e.id = instanceforms.assignedgroup',array('assignedgroupname'=>'name'))
        ->join(array('f' => 'groups'), 'f.id = instanceforms.ownergroupid',array('ownergroupname'=>'name'))
        ->join(array('g' => 'metaforms'), 'g.id = instanceforms.formid',array('formname'=>'name','statuslist'))
        ->join(array('h' => 'organizations'), 'h.id = instanceforms.orgid',array('orgname'=>'name'),'left')
        ->join(array('i' => 'instanceforms'), 'i.id = instanceforms.orgid',array('parentname'=>'name'),'left')
        ->where(array('instanceforms.id'=>$this->data['id']));
        $selectString = $this->sql->getSqlStringForSqlObject($select);
        $result = $this->queryExecute($select)[0];
        $statusarray= Utilities::expandArray($result['statuslist']);
        $result['status'] = $statusarray[$result['status']];
        $form = new Form($result['formid']);
        $fieldlist = $form->getFields();
        // print_r($fieldlist);exit;
        return $result;
    }
}