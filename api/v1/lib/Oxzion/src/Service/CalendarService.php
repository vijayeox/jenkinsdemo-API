<?php
require __DIR__ .'/autoload.php';
require_once __DIR__.'/Common/Config.php';

use Calendar\CalendarClient;
use Calendar\GoogleEvent;
use Calendar\OutlookEvent;
use Exception;
use Oxzion\Dao;
use Oxzion\OAuthService;
use Messaging\MessageProducer;
use Auth\GoogleOAuth;
use Auth\OutlookOAuth;
use RRule\RRule;
use RRule\RSet;
use Calendar\CalendarConstants;
use Eluceo\iCal\Component\Calendar;
use Eluceo\iCal\Component\Event;
use Eluceo\iCal\Property\Event\Attendees;
use Eluceo\iCal\Property\Event\Organizer;

class VA_ExternalLogic_CalendarService {

	const PROVIDER_GOOGLE = CalendarClient::GOOGLE;
	const PROVIDER_OUTLOOK = CalendarClient::OUTLOOK;
	private $dao;
	private $client;
	private $oAuthService;

	public function __construct(){
		$this->dao = new Dao();
		$this->oAuthService = new OAuthService();
	}
	public function getAccountByUserId($userid){
        $calendar_model = new VA_Model_OAuth();
        return $calendar_model->enlistByUserId($userid);
	}
	private function getClient($userid, $email, $provider){
		if(!isset($this->client)){
		try{
			$accessToken = $this->oAuthService->getCredentials($userid, $email, $provider);
			$this->client = CalendarClient::getCalendarClient($email, $provider, $accessToken);
			if($this->client->isAccessTokenExpired()&&$this->client){
				$accessToken = $this->client->fetchAccessTokenWithRefreshToken();
				$this->oAuthService->saveCredentials($userid, $email, $provider, $accessToken);
			}
			if($this->client){
				return $this->client;
			} else {
				return;
			}
		} catch (Exception $e){
			error_log($e);
		}
		return;
		}
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
	function getEvents($userid, $email, $provider, $calendarId = 'primary', $options = array()){
		$options['timeMax'] = date("c",strtotime($options['timeMax']." +1day"));
		$client = $this->getClient($userid, $email, $provider);
		if($client){
			$events = $client->getEvents($calendarId, $options);
			$eventlist = array();
			$i=0;
			foreach ($events['items'] as $key => $value) {
				$eventlist[$i] = array();
				$eventlist[$i] = $value->toProviderArray();
				$eventlist[$i]['eventid'] = $eventlist[$i]['id'];
				if($eventlist[$i]['start']['dateTime']){
					$eventlist[$i]['start'] = VA_Service_Utils::getFormatedDateWithFormat($eventlist[$i]['start']['dateTime'],"Y-m-d H:i:s");
					$eventlist[$i]['end'] = VA_Service_Utils::getFormatedDateWithFormat($eventlist[$i]['end']['dateTime'],"Y-m-d H:i:s");
				} else {
					if($eventlist[$i]['start']['date']){
						$eventlist[$i]['start'] = VA_Service_Utils::getFormatedDateWithFormat($eventlist[$i]['start']['date'],"Y-m-d H:i:s");
						$eventlist[$i]['end'] = VA_Service_Utils::getFormatedDateWithFormat($eventlist[$i]['end']['date'],"Y-m-d H:i:s");
					} else {
						if($eventlist[$i]['originalStartTime']['dateTime']){
							$eventlist[$i]['start'] = VA_Service_Utils::getFormatedDateWithFormat($eventlist[$i]['originalStartTime']['dateTime'],"Y-m-d H:i:s");
							$eventlist[$i]['end'] = VA_Service_Utils::getFormatedDateWithFormat($eventlist[$i]['originalStartTime']['dateTime'],"Y-m-d H:i:s");
						} else {
							$eventlist[$i]['start'] = VA_Service_Utils::getFormatedDateWithFormat($eventlist[$i]['originalStartTime']['date'],"Y-m-d H:i:s");
							$eventlist[$i]['end'] = VA_Service_Utils::getFormatedDateWithFormat($eventlist[$i]['originalStartTime']['date'],"Y-m-d H:i:s");
						}
					}
				}
				if($eventlist[$i]['summary']){
					$eventlist[$i]['name'] = $eventlist[$i]['summary'];
				} else {
					$eventlist[$i]['name'] = "Busy";
				}
				if($eventlist[$i]['attendees']){
					$eventlist[$i]['attendeesemaillist'] = array_column($eventlist[$i]['attendees'], 'email');
				}
				unset($eventlist[$i]['attendees']);
				$eventlist[$i]['organizer'] = $eventlist[$i]['organizer']['email'];
				if($eventlist[$i]['subject']){
					$eventlist[$i]['name'] = $eventlist[$i]['subject'];
				}
				if($eventlist[$i]['description']){
					$eventlist[$i]['summary'] = $eventlist[$i]['description'];
				} else {
					$eventlist[$i]['summary'] = "No description";
				}
				if($eventlist[$i]['body']){
					$eventlist[$i]['summary'] = $eventlist[$i]['body']['content'];
				}
				if($provider == 'OUTLOOK'){
					$event_obj = new OutlookEvent($value->toProviderArray());
					if($eventlist[$i]['recurrence']){
						$eventlist[$i]['rrule'] = $event_obj->createRRuleString($eventlist[$i]['recurrence']);
					} else {
						$eventlist[$i]['rrule'] = "";
					}
					if($eventlist[$i]['location']){
						$eventlist[$i]['location'] = $eventlist[$i]['location']['displayName'];
					}
				} else {
					if($eventlist[$i]['recurrence']){
						foreach ($eventlist[$i]['recurrence'] as $k => $v) {
							if(isset($v)){
								$eventlist[$i]['rrule'] = $v;
							}
						}
					} else {
						$eventlist[$i]['rrule'] = "";
					}
				}
				if($eventlist[$i]['creator']==$email||$eventlist[$i]['creator']==$calendarId||$eventlist[$i]['organizer']==$email){
					$eventlist[$i]['canedit'] = true;
				} else {
					$eventlist[$i]['canedit'] = false;
				}
				$eventlist[$i]['calendarId'] = $calendarId;
				$eventlist[$i]['email'] = $email;
				$i++;
			}
			return $eventlist;
		} else {
			return;
		}
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
		if($client){
			return $client->getCalendarList($options);	
		} else {
			return;
		}
	}

	function getEvent($userid, $email, $provider, $eventId, $calendarId = 'primary'){
		$client = $this->getClient($userid, $email, $provider);
		if($client){
			$event = $client->getEvent($eventId, $calendarId);
			return $event;
		} else {
			return;
		}
	}

	function createEvent($userid, $email, $provider, $event, $calendarId = 'primary'){
		$client = $this->getClient($userid, $email, $provider);
		return $client->createEvent($event, $calendarId);
	}

	function updateEvent($userid, $email, $provider, $event, $calendarId = 'primary'){
		$client = $this->getClient($userid, $email, $provider);
		if($client){
		return $client->updateEvent($event, $calendarId);
		} else {
			return;
		}
	}

	/**
	 *	$options (array)	-	Optional parameter
	 *			sendNotifications (boolean)	- Whether to send notifications about the 
	 *										  deletion of the event. Optional. The default is 
	 *										  False.
	 *
	 */
	function deleteEvent($userid, $email, $provider, $eventId, $calendarId = 'primary', $options = array()){
		$client = $this->getClient($userid, $email, $provider);
		if($client){
		return $client->deleteEvent($eventId, $calendarId, $options);
		} else {
			return;
		}
	}
	function constructEvent($avatarid,$box,$provider,$data){
		$userid = $avatarid;
        $provider = $provider;
        $email = $box;
        if($provider == 'GOOGLE'){
        	$event = new GoogleEvent();
        }
        if($provider == 'OUTLOOK'){
        	$event = new OutlookEvent();
        }
        if($data['id']){
        	$event->id = $data['id'];
        }
        $event->summary = $data['name'];
        $event->location = $data['location'];
        $event->description = $data['summary'];
        $gmtTimezone = new DateTimeZone('GMT');
        $start = new DateTime($data['start']);
        $end = new DateTime($data['end']);
        $event->startDateTime = $start->format(DateTime::ATOM);
        $event->startTimeZone = "UTC";
        $event->endDateTime = $end->format(DateTime::ATOM);
        $event->endTimeZone = "UTC";
        // if($data['recurrenceRule'] && $provider=='OUTLOOK'){
        // 	$event->rrule = array(RRule::createFromRfcString($data['rrule']));
        // }
        $event->attendees = $data['emaillist'];
        $event->reminderUseDefault = False;
        $event->reminderMinutesBeforeStart = 15;
        return $event;
	}
	function constructGoogleEvent($avatarid,$box,$data){
		$userid = $avatarid;
        $provider = CalendarClient::GOOGLE;
        $email = $box;
        $event = new GoogleEvent();
        $event->summary = $data['name'];
        $event->location = $data['location'];
        $event->description = $data['summary'];
        $gmtTimezone = new DateTimeZone('GMT');
        $start = new DateTime($data['start'], $gmtTimezone);
        $end = new DateTime($data['end'], $gmtTimezone);
        $event->startDateTime = $start->format(DateTime::ATOM);
        $event->startTimeZone = "Europe/London";
        $event->endDateTime = $end->format(DateTime::ATOM);
        $event->endTimeZone = "Europe/London";
        if($data['recurrenceRule']){
        	$event->rrule = array(RRule::createFromRfcString($data['recurrenceRule']));
        }
        // $event->attendees = array(array('email'=>'bharatgoku@gmail.com'),array('email'=>'stephen.prabhu02@gmail.com'));
        $event->reminderUseDefault = False;
        $event->reminderMinutesBeforeStart = 15;
        return $event;
	}
	function getRecurrenceContent($rule){
		$rrule = new RRule($rule);
		return $rrule->humanReadable(['locale' => 'en_US']);
	}
	function addCalendarSyncJob($userid, $email = NULL){
		$job = Job::getInstance();
		$producer = MessageProducer::getInstance();	
		$jobParams = array('userid' => $userid);
		if(!is_null($email)){
			$jobParams['email'] = $email;
		}
		$job->addJob($userid, CALENDAR_SYNC_JOB, "Oxzion\CalendarSyncTask", $jobParams, EMAIL_SYNC_PERIOD, -1, True);
	}
	function createICSFile($data,$eventid,$organizer,$url,$attendees,$organizeremail,$status =null){
		$vCalendar = new Calendar("//oxzion/OX Zion Calendar//NONSGML v1.0//EN");
		$vCalendar->setMethod(Calendar::METHOD_REQUEST);
		$vCalendar->setCalendarScale(Calendar::CALSCALE_GREGORIAN);
		$vEvent = new Event();
		$vEvent->setDtStart(new DateTime($data->startdate));
		$vEvent->setDtEnd(new DateTime($data->enddate));
		$vEvent->setSummary($data->name);
		$vEvent->setDescriptionHTML($data->summary);
		if($status){
			$vEvent->setCancelled(true);
		}
		$recurrenceRule = new \Eluceo\iCal\Property\Event\RecurrenceRule();
		if($data->rrule){
			$vEvent->setRecurrenceRule($this->setRecurrenceRule($data->rrule,$recurrenceRule));
		}
		$vEvent->setUniqueId($eventid."@uat.oxzion.com");
		$vEvent->setCategories(['OX Zion']);
		$organizerparam = new Organizer("MAILTO:".$organizeremail, array('CN' => $organizer));
		$vEvent->setOrganizer($organizerparam);
		if($attendees['email']){
			foreach ($attendees['email'] as $key => $attendee) {
				$attendeelist[] = $vEvent->addAttendee($attendee, array('ROLE' => 'REQ-PARTICIPANT','CUTYPE'=>'INDIVIDUAL','PARTSTAT'=>'NEEDS-ACTION','CN'=>$attendees['names'][$key]));
			}
		}
		$vCalendar->addComponent($vEvent);
		file_put_contents($url."calendar.ics", $vCalendar->render());
	}
	function setRecurrenceRule($rrule,$event){
		$rulelist = explode(";",$rrule);
		if(!is_array($rulelist)){
			$pair[] = explode("=", $value);
			$keyvalue[$pair[$key][0]] = $pair[$key][1];
		} else {
			foreach ($rulelist as $key => $value) {
				$pair[] = explode("=", $value);
				$keyvalue[$pair[$key][0]] = $pair[$key][1];
			}
		}
		if($keyvalue['UNTIL']){
			if(strtolower($keyvalue['UNTIL'])!="never"){
				$date = new DateTime(VA_Service_Utils::getFormatedDateWithFormat(str_replace("T", " ", $keyvalue['UNTIL']), 'Y-m-d H:i:s'));
				if($date){
					$event->setUntil($date);
				}
			}
		}
		if($keyvalue['FREQ']){
			$event->setFreq($keyvalue['FREQ']);
		}
		if($keyvalue['INTERVAL']){
			$event->setInterval($keyvalue['INTERVAL']);
		}
		if($keyvalue['COUNT']){
			$event->setCount($keyvalue['COUNT']);
		}
		if($keyvalue['BYDAY']){
			$event->setByDay($keyvalue['BYDAY']);
		}
		if($keyvalue['BYWEEKNO']){
			$event->setByWeekNo($keyvalue['BYWEEKNO']);
		}
		if($keyvalue['BYMONTH']){
			$event->setByMonth((int)$keyvalue['BYMONTH']);
		}
		if($keyvalue['BYMONTHDAY']){
			$event->setByMonthDay($keyvalue['BYMONTHDAY']);
		}
		if($keyvalue['BYYEARDAY']){
			$event->setByYearDay($keyvalue['BYYEARDAY']);
		}
		if($keyvalue['BYHOUR']){
			$event->setByHour($keyvalue['BYHOUR']);
		}
		if($keyvalue['BYMINUTE']){
			$event->setByMinute($keyvalue['BYMINUTE']);
		}
		if($keyvalue['BYSECOND']){
			$event->setBySecond($keyvalue['BYSECOND']);
		}
		return $event;
	}
	public function getrrule($rrule,$datestart,$eventid,$timezone){
		$rulelist = explode(";",$rrule);
		if(!is_array($rulelist)){
			$pair[] = explode("=", $value);
			$keyvalue[$pair[$key][0]] = $pair[$key][1];
		} else {
			foreach ($rulelist as $key => $value) {
				$pair[] = explode("=", $value);
				$keyvalue[$pair[$key][0]] = $pair[$key][1];
			}
		}
		if($keyvalue['UNTIL']){
			$date = new DateTime(date('Y-m-d H:i:s', strtotime($keyvalue['UNTIL'])),new DateTimeZone($timezone));
			$date->setTimezone(new DateTimeZone('UTC'));
			$keyvalue['UNTIL'] = $date->format('Y-m-d H:i:s');
			$repeat['repeat_end'] = $keyvalue['UNTIL'];
		}
		$repeat['eventid'] = $eventid;
		$startdate = new DateTime(date('Y-m-d H:i:s', strtotime($datestart),new DateTimeZone($timezone)));
		$startdate->setTimezone(new DateTimeZone('UTC'));
		$repeat['repeat_start'] = $startdate->format('Y-m-d H:i:s');
		$reflectionclass = new ReflectionClass('Calendar\CalendarConstants');
		if($keyvalue['INTERVAL']){
			$repeat['repeat_interval'] = $keyvalue['INTERVAL']*$reflectionclass->getConstant($keyvalue['FREQ']);
		} else {
			$repeat['repeat_interval'] = $reflectionclass->getConstant($keyvalue['FREQ']);
		}
		if($keyvalue['COUNT']){
			$repeat['repeat_end'] = date("c",strtotime($datestart)+$repeat['repeat_interval']*$keyvalue['COUNT']);
		}
		if($keyvalue['END']){
			$repeat['repeat_end'] = $keyvalue['END'];
		}
		if($keyvalue['BYDAY']){
			if(is_numeric(substr($keyvalue['BYDAY'], 0, 1))||is_numeric(substr($keyvalue['BYDAY'], 0, 2))){
				if(substr($keyvalue['BYDAY'], 0, 1)=="-"){
					$repeat['repeat_week'] = "*";
				} else {
					$repeat['repeat_week'] =substr($keyvalue['BYDAY'], 0, 1);
					$repeat['repeat_weekday'] = RRule::$week_days[substr($keyvalue['BYDAY'], 1)];
				}
			} else {
				$days = explode(",", $keyvalue['BYDAY']);
				$days =array();
				if(!is_array($days)){
					foreach ($days as $key => $value) {
						$days[] = RRule::$week_days[$value];
					}
				} else {
					$days[0] = RRule::$week_days[$keyvalue['BYDAY']];
				}
				$repeat['repeat_weekday'] = implode(",", $days);
			}
		}
		if($keyvalue['BYMONTH']){
			$repeat['repeat_month'] = $keyvalue['BYMONTH'];
		}
		if($keyvalue['BYMONTHDAY']){
			$repeat['repeat_day'] =$keyvalue['BYMONTHDAY'];
		}
		if($repeat['repeat_interval']){
			return $repeat;
		} else {
			return null;
		}
	}
}
?>