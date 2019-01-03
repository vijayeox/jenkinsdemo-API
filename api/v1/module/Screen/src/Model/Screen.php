<?php
namespace Screen\Model;
use Bos\Model\Entity;
use Oxzion\ValidationException;


class Screen extends Entity{

    protected $data = array(
            'id' => NULL,
            'name' => NULL,
    );
}