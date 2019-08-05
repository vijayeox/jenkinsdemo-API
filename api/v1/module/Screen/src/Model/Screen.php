<?php
namespace Screen\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Screen extends Entity
{
    protected $data = array(
            'id' => null,
            'name' => null,
    );
}
