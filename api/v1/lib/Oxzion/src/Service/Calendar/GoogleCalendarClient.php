<?php
namespace Calendar;
include_once __DIR__.'/../autoload.php';
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use date;
use Auth\GoogleOAuth;
class GoogleCalendarClient extends CalendarClient{
	private $service;
	private $client;
	private $email;

	public function __construct($email, $accessToken){
		$this->email = $email;
		$this->client = $this->_getClient($accessToken);
		$this->service = new Google_Service_Calendar($this->client);
	}
	private function _getClient($accessToken){
		$client = self::getClient();
		if(is_string($accessToken)){
			$accessToken = (array) json_decode($accessToken);
		}

		$client->setAccessToken($accessToken);

		return $client;
	}

	static function getClient(){
		return GoogleOAuth::getClient();
  	}

	function isAccessTokenExpired(){
		return $this->client->isAccessTokenExpired();
	}

	function fetchAccessTokenWithRefreshToken(){
		$this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
		return json_encode($this->client->getAccessToken());
	}

	/**
	 *	$calendarId (string) - Calendar identifier. To retrieve calendar IDs use the 
	 *							getCalendarList method. If you want to access the primary 
	 *							calendar of the currently logged in user, use the "primary" 
	 *							keyword.
	 *	$options (array) - Optional 
	 *		maxResults (int) - Maximum number of events returned on one result page. By 
	 *							default the value is 250 events. The page size can never be 
	 *							larger than 2500 events. Optional.
	 *		orderBy (string) - The order of the events returned in the result. Optional. 
	 *							'startTime' :  Order by the start date/time (ascending). This 
	 *											is only available when querying single events
	 *		q (string)			- Free text search terms to find events that match these 
	 *								terms in any field. Optional.
	 *		singleEvents (boolean) - Whether to expand recurring events into instances and 
	 *									only return single one-off events and instances of 
	 *									recurring events, but not the underlying recurring 
	 *									events themselves. Optional. The default is True.
	 *		timeMin (datetime)	- Lower bound (inclusive) for an event's end time to filter 
	 *								by. Optional. The default is not to filter by end time. 
	 *								Must be an RFC3339 timestamp with mandatory time zone 
	 *								offset, e.g., 2011-06-03T10:00:00-07:00, 
	 *								2011-06-03T10:00:00Z. Milliseconds may be provided but 
	 *								will be ignored.
	 *		timeMax (datetime) 	- Upper bound (exclusive) for an event's start time to filter 
	 *								by. Optional. The default is not to filter by start time. 
	 *								Must be an RFC3339 timestamp with mandatory time zone 
	 *								offset, e.g., 2011-06-03T10:00:00-07:00, 
	 *								2011-06-03T10:00:00Z. Milliseconds may be provided but 
	 *								will be ignored.
	 *		providerNextPageDetails (string) - The details that will be used to fetch the 
	 *											next page 
	 *	returns	array with following structure
	 *		name (string)			- Calendar Title
	 *		providerNextPageDetails (string) - present when more pages are available and has 
	 *											to be provided to fecth the next page in the 
	 *											options parameter for this api.
	 *		defaultReminderMinutes (int) - Number of minutes before the start of the event 
	 *										when the reminder should trigger. This is a 
	 *										calendar level  default value that is applied on 
	 *										all events in the calendar
	 *		items (array of CalendarEvent)	- List if calendarEvent objects 
	 */
	function getEvents($calendarId='primary', array $options = array() ){
		$options = array_merge(array('maxResults' => 100,
									'singleEvents' => TRUE,
									'timeMin' => date('c') ), $options);
		if(isset($options['providerNextPageDetails'])){
			$options['pageToken'] = $options['providerNextPageDetails'];
		}
		$results = $this->service->events->listEvents($calendarId, $options);
		$events = array("name" => $results['summary'], "items" => array());
		if(isset($results['nextPageToken'])){
			$events["providerNextPageDetails"] = $results["nextPageToken"];
		}
		if(isset($results['modelData']['defaultReminders'])){
			$events['defaultReminderMinutes'] = $results['modelData']['defaultReminders'][0]['minutes'];
		}
		if(isset($results['modelData']['items'])){
			foreach ($results['modelData']['items'] as $value) {
				$events['items'][] = new GoogleEvent((array)$value);
			}
		}
		if(isset($results['items'])){
			foreach ($results['items'] as $value) {
				$events['items'][] = new GoogleEvent((array)$value);
			}
		}
		return $events;
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
	function getCalendarList(array $options = array()){
		$options = array_merge(array('maxResults' => 100));
		$results = $this->service->calendarList->listCalendarList($options);
		$calendars = array("items" => array());
		if(isset($results['nextPageToken'])){
			$calendars["providerPageDetails"] = $results["nextPageToken"];
		}
		foreach ($results as $value) {
			$calendars['items'][] = array("id" => $value['id'],"name" => $value["summary"],'color'=>$value['backgroundColor']);
		}		  

		return $calendars;
	}
	
	function getEvent($eventId, $calendarId = "primary"){
		$result = $this->service->events->get($calendarId, $eventId);
		$event = (array)$result;
		$event['modelData'] = (array)$result->toSimpleObject();
		$eventdata = new GoogleEvent($event);
		return $eventdata;
	}

	function createEvent($event, $calendarId = 'primary'){
		$gevent = new Google_Service_Calendar_Event($event->toProviderArray());
		//print_r($gevent);
		$result = $this->service->events->insert($calendarId, $gevent,array('sendNotifications'=>true));
		$tmp = (array)$result;
		$tmp['modelData'] = (array)$result->toSimpleObject();
		return new GoogleEvent($tmp);
	}

	function updateEvent($event, $calendarId = 'primary'){
		$result = $this->service->events->update($calendarId, $event->id, new Google_Service_Calendar_Event($event->toProviderArray()));
		$tmp = (array)$result;
		$tmp['modelData'] = (array)$result->toSimpleObject();
		return new GoogleEvent($tmp);
	}

	function deleteEvent($eventId, $calendarId = 'primary', $options = array()){
		return $this->service->events->delete($calendarId, $eventId, $options);
	}
}
?>