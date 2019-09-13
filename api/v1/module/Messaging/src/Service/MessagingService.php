<?php
namespace Messaging\Service;

use Exception;
use Oxzion\Messaging\MessageProducer;

class MessagingService {

    public function __construct(){
      $this->messageProducer = MessageProducer::getInstance();
    }


    public function setMessageProducer($messageProducer)
    {
		  $this->messageProducer = $messageProducer;
    }

    public function send($data){
      if(isset($data['topic'])) {
          try {
              $this->messageProducer->sendTopic(json_encode($data) , $data['topic']);
              $result = $data;
          } catch (Exception $e) {
            return 0;
          }
      } else {
          if(isset($data['queue'])){
              try {
                  $this->messageProducer->sendQueue(json_encode($data) , $data['queue']);
                  $result = $data;
              } catch (Exception $e) {
                  return 0;
              }
          } else {
              return 0;
          }
      }
      return $result;
    }
}
?>