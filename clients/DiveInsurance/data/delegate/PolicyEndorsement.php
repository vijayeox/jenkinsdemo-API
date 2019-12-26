<?php

require_once __DIR__."/PolicyDocument.php";

class PolicyEndorsement extends PolicyDocument
{
     public function __construct(){
        parent::__construct();
        $this->type = 'endorsement';
        $this->template = array(
        'Dive Boat' 
            => array('header' => 'DB_Endorsement_header.html',
                     'footer' => 'DB_Endorsement_footer.html',
                     'template' => 'DiveBoat_Endorsement')
        );
    }
}
