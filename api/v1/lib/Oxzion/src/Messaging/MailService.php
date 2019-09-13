<?php
namespace Oxzion\Messaging;

class MailService extends GenericPayload {

	protected $data = array(
		'subject'=> 0,
		'body' => NULL,
		'from' => NULL,
		'to' => NULL,
        'attachments' => NULL
    );
    protected $payload;
    protected $topic = Constants::MAIL;

	public function __construct($data){
		if(isset($data['subject'])){
			$this->data['subject'] = $data['subject'];
		}
		if(isset($data['body'])){
			$this->data['body'] = $data['body'];
		}
		if(isset($data['from'])){
			$this->data['from'] = $data['from'];
		}
		if(isset($data['to'])){
			if(is_array($data['to'])){
				$this->data['to'] = $data['to'];
			} else {
				$this->data['to'] = array($data['to']);
			}
		}
		if(isset($data['attachments'])){
			if(is_array($data['attachments'])){
				$this->data['attachments'] = $data['attachments'];
			} else {
				$this->data['attachments'] = array($data['attachments']);
			}
		}
		$this->payload = new Payload($this->data);
	}
}
?>