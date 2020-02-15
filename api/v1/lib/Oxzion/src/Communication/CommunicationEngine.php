<?php
namespace Oxzion\Communication;

interface CommunicationEngine
{
    public function sendSms($dest_phone_number, $body);

    public function makeCall($dest_phone_number, $voiceText ,$end_point);
}
