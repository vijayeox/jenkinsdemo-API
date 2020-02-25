<?php
namespace Oxzion\Communication\Twillio;
use Twilio\Rest\Client;
use Twilio\TwiML\VoiceResponse;
use Oxzion\Communication\CommunicationEngine;
use Oxzion\ServiceException;
use Logger;
// use Zend\Hydrator\ReflectionHydrator;

class CommunicationEngineImpl implements CommunicationEngine
{
    private $logger;
    private $twilio_phone_number; //valid and verified twillio number from www.twilio.com/console
    private $client;

    public function __construct(array $config) {
        $this->logger = Logger::getLogger(__CLASS__);
        $this->twilio_phone_number = $config['twilio_phone_number'];
        // $config['account_sid'] Your Account SID from www.twilio.com/console
        // $config['auth_token'] Your Auth Token from www.twilio.com/console
        $this->client = new Client($config['account_sid'], $config['auth_token']);
    }

    //
    public function sendSms($dest_phone_number, $body) {
        $this->logger->info("Entered ");
        $message;
        // $hydrator = new ReflectionHydrator();
        try{
            $message = $this->client->messages->create(
                $dest_phone_number,
                array(
                    "from" => $this->twilio_phone_number,
                    "body" => $body
                )
            );
        } catch (Exception $e) {
                $this->logger->error($e->getMessage(), $e);
                throw new ServiceException($e->getMessage(), "could.not.send.sms");
        }
        $this->logger->info("Exit ");
        // $data = $hydrator->extract($message);
        return $message;
    }

    //
    public function makeCall($dest_phone_number, $voiceText ,$end_point = 'https://twimlets.com/holdmusic?Bucket=com.twilio.music.ambient') {
        $this->logger->info("Entered ");
        $call;
        try{
            $call = $this->client->calls->create(
                $dest_phone_number, //The phone number you wish to dial
                $this->twilio_phone_number, //Verified Outgoing Caller ID or Twilio number
                array(
                    "statusCallback" => "https://www.myapp.com/events",
                    "statusCallbackMethod" => "POST",
                    "twiml" => $this->textToSpeech($voiceText)
                )
            );
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw new ServiceException($e->getMessage(), "could.not.make.call");
    }
    $this->logger->info("Exit ");
    return $call;           
    }

    private function textToSpeech($voiceText) {
        // check this document for more options https://www.twilio.com/docs/voice/twiml/say/text-speech
        $twiml = new VoiceResponse();
        $twiml->say($voiceText);
        return (string)$twiml;
    }

    
}
