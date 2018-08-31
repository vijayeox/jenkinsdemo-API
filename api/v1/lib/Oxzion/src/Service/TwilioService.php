<?php
require __DIR__ .'/autoload.php';

use Twilio\Rest\Client;
use Twilio\Twiml;

class VA_ExternalLogic_TwilioService {
	private $host;
	private $voice;
	private $client;
	private $config;

	public function __construct(){
		$this->voice = array('voice' => 'alice');
		$this->host = VA_Logic_Session::getFullBaseUrl()."/api/twilio";
		$this->config = VA_Logic_Session::getTwilioConfig();
		$this->client = new Client($this->config['sid'], $this->config['token']);
	}

	public function sendSMS($to_number, $message) {
		try{
			$response = $this->client->messages->create(
				$to_number,
				array(
					"from" => $this->config['from_number'],
					"body" => $message,
					// "statusCallback" => $this->host
				)
			);
			return $this->response($response, "Sending...");
		} catch (Exception $e) { return array('status'=>400, 'message'=>$e->getMessage(), 'response'=>$e->__toString()); }
	}

	public function sendMMS($to_number, $message, $mediaurl = null) {
		try{
			$response = $this->client->messages->create(
				$to_number,
				array(
					"from" => $this->config['from_number'],
					"body" => $message,
					"mediaUrl" => $mediaurl,
					// "statusCallback" => $this->host
				)
			);
			return $this->response($response, "Sending...");
		} catch (Exception $e) { return array('status'=>400, 'message'=>$e->getMessage(), 'response'=>$e->__toString()); }
	}

	public function call($to_number, $message = null, $senddigits = null) {
		try {
			$response = $this->client->calls->create(
				$to_number,
				$this->config['from_number'],
				array(
					"url" => $this->host."/get",
					"method" => "GET",
					// "sendDigits" => $senddigits,
					"statusCallbackMethod" => "POST",
					"statusCallback" => $this->host,
					"statusCallbackEvent" => array(
						// "queued",
						"initiated", "ringing", "answered", "in-progress", "completed", "busy", "no-answer", "failed", "canceled"
					)
				)
			);
			return $this->response($response, "Connecting...");
		} catch (Exception $e) { return array('status'=>400, 'message'=>$e->getMessage(), 'response'=>$e->__toString()); }

	}

	public function response($response, $message) {
		if (!empty($response->statusCode)) {
			$status = $response->statusCode;
			$message = $response->message;
		} else {
			$status = 200;
		}
		return array('status'=>$status, 'message'=>$message, 'response'=>$response);
	}

	public function alert($data) {
		// if not set by default set callback_method to alertCallback
		if (empty($data['callback_method']))
			$data['callback_method'] = 'alertCallback';
		$avatar = VA_Logic_Session::getAvatar();
		if (!empty($avatar))
			$data["avatar"] = $avatar->username;
		$data['date'] = date("Y-m-d H:i:s");
		if ($data['method'] != "call")
			$data['status'] = 200;
		$key = md5(sha1($data['to']));

		// Connect
		$response = $this->$data['method']($data['to'], $data['message'], $data['data']);

		// Cache only after connection successfull
		if ($response['status'] == 200) {
			// Caching data
			$cache = VA_Logic_Session::getCoreCache();
			$cache_data = $cache->load("twilio_call_queue");
			if (isset($cache_data[$key]))
				unset($cache_data[$key]);
			$cache_data[$key] = $data;
			$cache->remove("twilio_call_queue");
			$cache->save($cache_data, "twilio_call_queue", array("twilio_call_queue"));
		}
		return $response;
	}

	public function alertCallback($data) {
		$twiml = new Twiml();
		$twiml->say($data['message'].". Thank you.", $this->voice);
		// $twiml->say("Please enter the digits displayed on the screen and press hash", $this->voice);
		// $gather = $twiml->gather(array('action' => $this->host."/api/twilio/post/key/".$key, 'method' => 'GET'));
		// $gather->say("Thank you. Details are on your screen.");
		// $twiml->say("You have not entered any digits. Please try again.", $this->voice);
		// $twiml->hangup();
		$response = $twiml->__toString();
		return $response;
	}

	public function invalidRequest() {
		$twiml = new Twiml();
		$twiml->say("Invalid Request", $this->voice);
		$response = $twiml->__toString();
		return $response;
	}

	// examples do not uncomment or remove this
	// public function say() {
	// 	$text = implode("",func_get_args());
	// 	$this->output[] = "<Say>$text</Say>";
	// 	return $this;
	// }

	// public function play()
	// {
	// 	$text = implode("",func_get_args());
	// 	$this->output[] = "<Play>$text</Play>";
	// 	return $this;
	// }

	// public function redirectGet()
	// {
	// 	$text = implode("",func_get_args());
	// 	$this->output[] = "<Redirect method='GET'>$text</Redirect>";
	// 	return $this;
	// }

	// public function gather($actionUrl, $numDigits=1, $finishOnKey='#', $timeout=5, $method='POST')
	// {
	// 	$this->output[] = "<Gather action='$actionUrl' numDigits='$numDigits'
	// 	finishOnKey='$finishOnKey' timeout='$timeout' method='$method'/>";
	// 	return $this;
	// }

	// public function record($actionUrl, $maxLength=60, $finishOnKey='#', $timeout=5, $method='POST')
	// {
	// 	$this->output[] = "<Record action='$actionUrl' maxLength='$maxLength'
	// 	finishOnKey='$finishOnKey' timeout='$timeout' method='$method'/>";
	// 	return $this;
	// }

	// public function hangup()
	// {
	// 	$this->output[] = "<Hangup/>";
	// 	return $this;
	// }

	// public function out()
	// {
	// 	$final ="<?xml version='1.0'?.>\n
	// 	<Response>
	// 	".implode("\n",$this->output)."
	// 	</Response>";
	// 	return $final;
	// }

}