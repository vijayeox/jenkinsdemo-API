<?php
namespace Calendar;
include_once __DIR__.'/../autoload.php';
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessToken;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model\Event;
use Microsoft\Graph\Model\Calendar;
use DateTime;
use DateInterval;

class OutlookCalendarClient extends CalendarClient{
	private $service;
	private $accessToken;
	private $email;

	public function __construct($email, $accessToken){
		$this->email = $email;
		$this->service = $this->_getService($accessToken);
	}
function isAccessTokenExpired(){
		return $this->accessToken->hasExpired();
	}
	function getAccessToken(){
		return $this->accessToken->getToken();
	}
	private function _getService($accessToken){
		$service = new Graph();
		if(is_string($accessToken)){
			$accessToken = (array) json_decode($accessToken);
		}
		$this->accessToken = new AccessToken($accessToken);
		$service->setAccessToken($this->accessToken->getToken());
  		return $service;
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
	 *											next page. In this case it is the # of records to skip. 
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
		$url = "/me/calendar/calendarView?";
		if($calendarId != 'primary'){
			$url = "/me/calendars/".$calendarId."/calendarView?";
		}
		$endTime = new DateTime();
		$endTime->add(new DateInterval('P30D'));
		$defaultOptions = array('$top' => 100,
								'startdatetime' => date('c'),
								'enddatetime' => $endTime->format('c'));

		$filter = "";
		foreach ($options as $key => $value) {
			switch ($key) {
				case 'maxResults':
					$defaultOptions['$top'] = $value;
					break;
				case 'orderBy':
					//TODO translate the value to appropriate field
					$defaultOptions['$orderBy'] = $value;
					break;	
				case 'q':
					//Not supported now
					break;	
				case 'singleEvents':
					if(!$value){
						$url = "/me/calendar/events?";
						if($calendarId != 'primary'){
							$url = "/me/calendars/".$calendarId."/events?";
						}
					}
					break;
				case 'timeMin':
					if(isset($options['singleEvents']) && !$options['singleEvents']){
						$defaultOptions['startdatetime'] = date("Y-m-d\TH:i:s\Z",strtotime($value));
					}else{
						$filter = $filter =='' ? 'Start/DateTime ge '.$value : $filter.' and Start/DateTime ge '.$value;
					}
					break;
				case 'timeMax':	
					if(isset($options['singleEvents']) && !$options['singleEvents']){
						$defaultOptions['enddatetime'] = date("Y-m-d\TH:i:s\Z",strtotime($value));
					}else{
						$filter = $filter =='' ? 'End/DateTime le '.$value : $filter.' and End/DateTime le '.$value;
					}
					break;
				case 'providerNextPageDetails':
					$defaultOptions['$skip'] = $value;
					break;
				
			}
		}
		$url = $url.http_build_query($defaultOptions);
		$results = $this->service->createRequest('GET', $url)
						->addHeaders(array ('X-AnchorMailbox' => $this->email))
                  		->setReturnType(Event::class)
                  		->execute();
        
		$events = array("name" => 'default', "items" => array());
		foreach ($results as $key => $value) {
			$events['items'][] = new OutlookEvent($value->jsonSerialize());
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
		$url = "/me/calendars?";
		$defaultOptions = array('$top' => 100);
		if(isset($options['maxResults'])){
			$defaultOptions['$top'] = $options['maxResults'];
		}
		if(isset($options['providerNextPageDetails'])){
			$defaultOptions['$skip'] = $options['providerNextPageDetails'];
		}
		$url = $url.http_build_query($defaultOptions);
		$results = $this->service->createRequest('GET', $url)
						->addHeaders(array ('X-AnchorMailbox' => $this->email))
                  		->setReturnType(Calendar::class)
                  		->execute();
        
		$calendars = array("items" => array());
		if(isset($results['nextPageToken'])){
			$calendars["providerPageDetails"] = $results["nextPageToken"];
		}
		foreach ($results as $key => $value) {
			$color = $value->getColor();
			if($color->value() == 'auto'){
				$color = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
			}
			$calendars['items'][] = array("id" => $value->getId(),"name" => $value->getName(),'color'=>$color);
			
		}
		return $calendars;
	}
	
	function getEvent($eventId, $calendarId = "primary"){
		$url = "/me/calendar/events/";
		if($calendarId != 'primary'){
			$url = "/me/calendars/".$calendarId."/events/";
		}
		$url = $url.$eventId;
		$result = $this->service->createRequest('GET', $url)
						->addHeaders(array ('X-AnchorMailbox' => $this->email))
                  		->setReturnType(Event::class)
                  		->execute();
        return new OutlookEvent($result->jsonSerialize());
	}

	function createEvent($event, $calendarId = 'primary'){
		$url = "/me/calendar/events";
		if($calendarId != 'primary'){
			$url = "/me/calendars/".$calendarId."/events";
		}
		$result = $this->service->createRequest('POST', $url)
						->addHeaders(array ('X-AnchorMailbox' => $this->email))
						->attachBody($event->toProviderArray())
                  		->setReturnType(Event::class)
                  		->execute();
        return new OutlookEvent($result->jsonSerialize());
	}

	function updateEvent($event, $calendarId = 'primary'){
		$url = "/me/calendar/events/";
		if($calendarId != 'primary'){
			$url = "/me/calendars/".$calendarId."/events/";
		}
		$url = $url.$event->id;

		$result = $this->service->createRequest('PATCH', $url)
						->addHeaders(array ('X-AnchorMailbox' => $this->email))
						->attachBody($event->toProviderArray())
                  		->setReturnType(Event::class)
                  		->execute();
        return new OutlookEvent($result->jsonSerialize());
	}

	function deleteEvent($eventId, $calendarId = 'primary', $options = array()){
		$url = "/me/calendar/events/";
		if($calendarId != 'primary'){
			$url = "/me/calendars/".$calendarId."/events/";
		}
		$url = $url.$eventId;
		
		$result = $this->service->createRequest('DELETE', $url)
						->addHeaders(array ('X-AnchorMailbox' => $this->email))
						->execute();
		return $result;
	}
}
?>