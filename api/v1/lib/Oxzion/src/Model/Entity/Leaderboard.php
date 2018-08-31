<?php

namespace Oxzion\Model\Entity;
use Oxzion\Model\Entity;

class Leaderboard extends Entity{

    protected $data = array(
        'avatarid' => NULL,
        'goals' => NULL,
        'starpoints' => NULL,
        'teamgoal' => NULL,
        'total' => NULL,
    );

    public function __construct($data=null){
        $this->tablename = 'leaderboard';
        parent::__construct($data,$this);
    }
}