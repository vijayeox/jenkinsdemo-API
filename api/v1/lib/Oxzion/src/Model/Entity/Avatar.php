<?php
namespace Oxzion\Model\Entity;

use Oxzion\Model\Entity;
use Oxzion\Model\Table\AvatarTable;

class Avatar extends Entity{

    protected $data = array(
        'id' => NULL,
        'gamelevel' => NULL,
        'username' => NULL,
        'password' => NULL,
        'firstname' => NULL,
        'lastname' => NULL,
        'name' => NULL,
        'role' => '',
        'last_login' => NULL,
        'orgid' => NULL,
        'email' => NULL,
        'emailnotify' => 'Active',
        'sentinel' => 'On',
        'icon' => NULL,
        'gamemodeIcon' => NULL,
        'status' => 'Active',
        'ipaddress' => NULL,
        'country' => NULL,
        'dob' => NULL,
        'designation' => NULL,
        'phone' => NULL,
        'address' => NULL,
        'sex' => NULL,
        'website' => NULL,
        'about' => NULL,
        'interest' => NULL,
        'hobbies' => NULL,
        'managerid' => NULL,
        'alertsacknowledged' => '1',
        'pollsacknowledged' => '1',
        'selfcontribute' => NULL,
        'contribute_percent' => NULL,
        'statusbox' => 'Matrix|Leaderboard|Alerts',
        'eid' => NULL,
        'defaultgroupid' => NULL,
        'cluster' => '0',
        'level' => NULL,
        'open_new_tab' => '0',
        'listtoggle' => NULL,
        'defaultmatrixid' => '0',
        'lastactivity' => '0',
        'locked' => '0',
        'signature' => NULL,
        'location' => NULL,
        'org_role_id' => '1',
        'in_game' => '0',
        'mission_link' => NULL,
        'instanceform_link' => NULL,
        'timezone' => 'Asia/Kolkata',
        'inmail_label' => '2=>Comment|3=>Observer|4=>Personal',
        'avatar_date_created' => NULL,
        'doj' => NULL,
        'password_reset_date' => NULL,
        'otp' => NULL,
    );
    public function __construct($data=null){
        $this->tablename = 'avatars';
        parent::__construct($data,$this);
    }
    public function getAvatarByUsername($username){
        $select = $this->sql->select()
        ->from('avatars')
        ->columns(array())
        ->where(array('username = '.$username));
        $selectString = $sql->getSqlStringForSqlObject($select);
        $results = $this->queryExecute($select);
        return new Avatar($results[0]['id']);
    }
    public function getGroups(){
        $select = $this->sql->select()
        ->from('groups_avatars')
        ->columns(array())
        ->join('groups', 'groups.id = groups_avatars.groupid')
        ->where(array('groups_avatars.avatarid' => $this->data['id']));
        return $this->queryExecute($select);
    }
    public function getFlag($flag){
        $select = $this->sql->select()
        ->from('avatar_flags')
        ->columns(array('value'))
        ->where(array('avatarid' => $this->data['id'],'flag' => $flag));
        $selectString = $this->sql->getSqlStringForSqlObject($select);
        return $this->queryExecute($select);
    }
    public function getModules(){
        $select = $this->sql->select()
        ->from('groups_modules')
        ->columns(array())
        ->join('modules', 'modules.id = groups_modules.moduleid')
        ->where(array('groupid'=>array_column($this->getGroups(), 'id')));
        $selectString = $this->sql->getSqlStringForSqlObject($select);
        return $this->queryExecute($select);
    }
}