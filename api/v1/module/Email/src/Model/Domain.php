<?php
namespace Email\Model;

use Oxzion\Model\Entity;

class Domain extends Entity
{
    protected $data = array(
        'id' => 0,
        'name' => 0,
        'imap_server' => 0,
        'imap_port' => 0,
        'imap_secure' => null,
        'imap_short_login' => null,
        'smtp_server' => 0,
        'smtp_port' => 0,
        'smtp_secure' => null,
        'smtp_short_login' => null,
        'smtp_auth' => null,
        'smtp_use_php_mail' => null,
        'created_by' => null,
        'modified_id' => null,
        'date_created' => null,
        'date_modified' => null,

    );

    public function validate()
    {
        $dataArray = array("name", "imap_server", "imap_port", "smtp_server", "smtp_port");
        $this->validateWithParams($dataArray);
    }
}
