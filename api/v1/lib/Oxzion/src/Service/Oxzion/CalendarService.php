<?php
namespace Oxzion;

use Calendar\CalendarClient;
use Exception;

class CalendarService {

	const PROVIDER_GOOGLE = CalendarClient::GOOGLE;
	const PROVIDER_OUTLOOK = CalendarClient::OUTLOOK;

	private $dao;

	public function __construct(){
		$this->dao = new Dao();
	}

	function getCredentials($userid, $email, $provider){
		$sql = "select credentials, refresh_token from oauth2_setting WHERE userid = ".$userid." AND provider = '".$provider."' AND email = '".$email."'" ;

		if(!$result = $this->dao->execQuery($sql)){ //a RUNNING job exists for the userid
			return;
		}

		$row = $result->fetch_assoc();
		$credentials = $row['credentials'];
		if(isset($credentials)){
			$credentials = (array)json_decode($credentials);
			$credentials['refresh_token'] = $row['refresh_token'];
		}
		return $credentials;
	}

	function saveCredentials($userid, $email, $provider, $credentials){
		$rToken;
		if(is_array($credentials)){
			if(isset($credentials['refresh_token'])){
				$rToken = $credentials['refresh_token'];
			}
			$credentials = json_encode($credentials);
		}else{
			$rToken = (array)json_decode($credentials);
			if(isset($rToken['refresh_token'])){
				$rToken = $rToken['refresh_token'];
			}
		}
		$sql = "select count(id) cnt from oauth2_setting where userid = ".$userid." AND email = '".$email."' AND provider = '".$provider."'";
 		if(!$result = $this->dao->execQuery($sql)){ //a RUNNING job exists for the userid
			return false;
		}
 		$row = $result->fetch_assoc();
		$val = $row['cnt'] > 0;
		$result->free();
		$refreshToken = "";
		
		if($val){
			if(isset($rToken)){
				$refreshToken = ", refresh_token = '".$rToken."' ";
			}
			$sql = "update oauth2_setting set credentials = '".$credentials."'".$refreshToken." where userid = ".$userid." AND provider = '".$provider."' AND email = '".$email."'";
		}else{
			if(!isset($rToken)){
				throw new Exception("No refresh token please set approval prompt to force and perform the authentication");
			}
			$sql = "INSERT INTO oauth2_setting (userid, email, provider, credentials, refresh_token) VALUES (".$userid.", '".$email."', '".$provider."', '".$credentials."', '".$rToken."')";
		}
		return $this->dao->execUpdate($sql);
	}

	private function getClient($userid, $email, $provider){
		$accessToken = $this->getCredentials($userid, $email, $provider);
		$client = CalendarClient::getCalendarClient($userid, $provider, $accessToken);
		return $client;
	}

	/**
	 *	$calendarId (string) - Calendar identifier. To retrieve calendar IDs use the 
	 *							getCalendarList method. If you want to access the primary 
	 *							calendar of the currently logged in user, use the "primary" 
	 *							keyword. Default 'primary'. 
	 * 	$options : array of following fields
	 *			maxResults (int) : Maximum number of events returned on one result page 
	 *								default 100. Cannot be greater than 250
	 *			singleEvents (boolean) : Whether to expand recurring events into instances and *								   only return single one-off events and instances of 
	 *									 recurring events, but not the underlying recurring 
	 *									 events themselves. Optional. The default is True. 
	 *			timneMin (string) : Lower bound (inclusive) for an event's end time to filter 
	 *								by. Optional. The default is not to filter by end time. 
	 *								Must be an RFC3339 timestamp with mandatory time zone 
	 *								offset, e.g., 2011-06-03T10:00:00-07:00, 
	 *								2011-06-03T10:00:00Z. Milliseconds may be provided but 
	 *								will be ignored.
	 *			timeMax (string) : Upper bound (exclusive) for an event's start time to filter 
	 *								by. Optional. The default is not to filter by start time. 
	 *								Must be an RFC3339 timestamp with mandatory time zone 
	 *								offset, e.g., 2011-06-03T10:00:00-07:00, 
	 *								2011-06-03T10:00:00Z. Milliseconds may be provided but 
	 *								will be ignored.
	 */
	function getEvents($userid, $email, $provider, $calendarId = 'primary', array $options = array()){
		$client = $this->getClient($userid, $email, $provider);
		return $client->getEvents($calendarId, $options);
	}

	/**
	 *	$options - optional parameters
	 *		maxResults (int) - Maximum number of entries returned on one result page. By 
	 *							default the value is 100 entries. The page size can never be *
	 *							larger than 250 entries. Optional.
	 *		providerNextPageDetails (string) - The details that will be used to fetch the 
	 *											next page 
	 *	returns array - with following structure
	 *		providerNextPageDetails (string) - The details that will be used to fetch the 
	 *											next page 
	 *		items (array) - array of calendars with 'id' and 'name' 
	 */
	function getCalendarList($userid, $email, $provider, array $options = array()){
		$client = $this->getClient($userid, $email, $provider);
		return $client->getCalendarList($options);	
	}

	function getEvent($userid, $email, $provider, $eventId, $calendarId = 'primary'){
		$client = $this->getClient($userid, $email, $provider);
		return $client->getEvent($eventId, $calendarId);		
	}

	function createEvent($userid, $email, $provider, $event, $calendarId = 'primary'){
		$client = $this->getClient($userid, $email, $provider);
		return $client->createEvent($event, $calendarId);
	}
}
?>