<?php

namespace Oxzion\Model\Entity;
use Oxzion\Model\Entity;

class Form extends Entity{
	protected $data = array(
		'id' => null,
		'name' => null,
		'description' => null,
		'sequence' => null,
		'nextsequence' => null,
		'htmltext' => null,
//        'modulename' => null,
		'orgid' => null,
		'moduleid' => null,
		'canhaveparent' => null,
        //@ bug  #370659 
		'makeparentmandatory' => null,
        'canhavecategory' => null, //@BUG 14773
        'canhaveattachment' => null,
        'canhavespreadsheet' => null,
        'canhidetime' => null, //@Bug 372810
        'canhavewriteaccess' => null,
        'canhavereadaccess' => null,
        'canhaveemail' => null,
        'canhavedigitalsign' => null,
        'canassign' => null,
        'canassigngroup' => null,
        'canmultiassign' => null, //@BUG 14638
        'onlyadmincancreate' => null,
        'statusfield' => null,
        'emailfields' => null,
        'startdatefield' => null,
        'nextactiondatefield' => null,
        'enddatefield' => null,
        'printfields' => null,
        'defaultassigngroup' => null,
        'can_create_duplicate' => null,
        'nodelete' => null,
        'assignedtoview' => null,
        'hidecost' => null,
        'hidestarpoints' => null,
        'assignedtofromdefaultgroup' => null,
        'statuslist' => null,
        'statuslistcolor' => null,
        'hidetags' => null,
        'hideleveldifference' => null,
        'showallassignedgroup' => null,
        'showallownergroup' => null,
        'canhavekra' => null,
        'kracategories' => NULL,
        'krasubcategories' => NULL,
        'type' => null,
        'wizard_id' => null,
        'customcreate' => null,
        'customview' => null,
        'disable_mupdate' => null,
        'disable_inlineedit' => null,
        'discussionStartCount' => null, //@Bug 55005 Comment count
        'allow_moderator' => null,
        'disable_calendar' => null,
        'can_have_map' => null,
        'reffield1' => null,
        'reffield2' => null,
        'reffield3' => null,
        'reffield4' => null,
        'reffield5' => null,
        'template' => null,
        'canvas' => null,
        'formdeleteaccess' => null,
        'defaultgroupaccess' => null,
        'nextactiondatediff' => null,
        'enddatediff' => null,
        'category' => null,
        'can_have_dyn_info_view' => null,
        'can_have_quick_edit' => null,
        'can_have_assignments' => null,
        'can_have_spreadsheet' => null,
        'can_have_workrelated' => null,
        'can_have_comments' => null,
        'can_have_like' => null,
        'can_have_message' => null,
        'can_have_edit'  => null,
        'can_have_convert' => null,
        'can_have_lockrecord' => null,
        'can_have_stickynotes' => null,
        'can_have_logofactivities' => null,
        'can_have_pm' => null,
        'can_have_copyURL' => null,
        'can_have_print' => null,
        'ownerassignedcanedit' => null,
        'cancopywizardvalues' => null,
        'fieldview' => null,
        'resetbigfields'=>1,
        'savecopybtn'=>1,
        'savenewbtn'=>1,
        'savealertbtn'=>1
    );

    public function __construct($data=null){
        $this->tablename = 'metaforms';
        parent::__construct($data,$this);
    }
    public function getFields(){
    	$select = $this->sql->select()
        ->from('metafields')
        ->columns(array('*'))
        ->where(array('formid' => $this->data['id']));
        $selectString = $this->sql->getSqlStringForSqlObject($select);
        return $this->queryExecute($select);
    }
    public function getByModule($moduleid,$orgid){
    	$select = $this->sql->select()
        ->from($this->tablename)
        ->columns(array('*'))
        ->where(array('moduleid' => $moduleid));
        $selectString = $this->sql->getSqlStringForSqlObject($select);
        return $this->queryExecute($select);
    }
}