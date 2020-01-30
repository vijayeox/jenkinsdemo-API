<?php
namespace Oxzion\Communication;

interface Communication
{
    public function loadConfiguration($data);

    public function sendSms($dest_phone_number, $body);
}
