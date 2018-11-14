<?php
namespace User\Model;
use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class User extends Entity{

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

    public function validate(){
       $errors = array();
        if($this->data['gamelevel'] === null) {
            $errors["gamelevel"] = 'required';   
        }
        if($this->data['username'] === null) {
            $errors["username"] = 'required';  
        }
        if($this->data['password'] === null) {
            $errors["password"] = 'required';   
        }
        if($this->data['firstname'] === null) {
            $errors["firstname"] = 'required';   
        }
        if($this->data['lastname'] === null) {
            $errors["lastname"] = 'required';   
        }
        if($this->data['name'] === null) {
            $errors["name"] = 'required';   
        }
        if($this->data['role'] === null) {
            $errors["role"] = 'required';   
        }
        if($this->data['email'] === null) {
            $errors["email"] = 'required';   
        }
        if($this->data['status'] === null) {
            $errors["status"] = 'required';   
        }
        if($this->data['dob'] === null) {
            $errors["dob"] = 'required';   
        }
        if($this->data['designation'] === null) {
            $errors["designation"] = 'required';   
        }
        if($this->data['sex'] === null) {
            $errors["sex"] = 'required';   
        }
        if($this->data['managerid'] === null) {
            $errors["managerid"] = 'required';   
        }
        if($this->data['cluster'] === null) {
            $errors["cluster"] = 'required';   
        }
        if($this->data['level'] === null) {
            $errors["level"] = 'required';   
        }
        if($this->data['org_role_id'] === null) {
            $errors["org_role_id"] = 'required';   
        }
        if($this->data['doj'] === null) {
            $errors["doj"] = 'required';   
        }
        if(count($errors) > 0){
            $validationException = new ValidationException();
            $validationException->setErrors($errors);
            throw $validationException;
        }
    }
}
