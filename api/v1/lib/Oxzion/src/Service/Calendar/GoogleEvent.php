<?php
namespace Calendar;
use RRule\RRule;
use Google_Service_Calendar_EventReminder;

class GoogleEvent extends CalendarEvent{
	private static $statusInMap = array("tentative" => CalendarEvent::RESPONSE_STATUS_TENTATIVE, 
											"confirmed" => CalendarEvent::RESPONSE_STATUS_CONFIRMED,
											"cancelled" => CalendarEvent::RESPONSE_STATUS_CANCELLED,
											"needsAction" => CalendarEvent::RESPONSE_STATUS_NEEDS_ACTION,
											"declines" => CalendarEvent::RESPONSE_STATUS_DECLINED,
											"accepted" => CalendarEvent::RESPONSE_STATUS_ACCEPTED);
	private static $statusOutMap = array(CalendarEvent::RESPONSE_STATUS_TENTATIVE => "										tentative", 
											   CalendarEvent::RESPONSE_STATUS_CONFIRMED => "confirmed",
												CalendarEvent::RESPONSE_STATUS_CANCELLED => "cancelled",
												CalendarEvent::RESPONSE_STATUS_NEEDS_ACTION => "needsAction",
												CalendarEvent::RESPONSE_STATUS_DECLINED => "declines",
												CalendarEvent::RESPONSE_STATUS_ACCEPTED => "accepted");
	
	private $originalStartDate;
	private $reminderOverrides;

	public function __construct(array $raw = NULL){
		$this->provider = CalendarClient::GOOGLE;
		$this->raw = $raw;
		if(isset($raw)){
			$this->parse();
		}
	}

	protected function parse(){
		$this->id = $this->raw['id'];
		$this->status = GoogleEvent::$statusInMap[$this->raw['status']];
		$this->htmlLink = $this->raw['htmlLink'];
		$this->created = $this->raw['created'];
		$this->summary = $this->raw['summary'];

		if(isset($this->raw['description'])){
			$this->description = $this->raw['description'];
		}
		if(isset($this->raw['location'])){
			$this->location = $this->raw['location'];
		}
		$tmp = NULL;
		if(isset($this->raw['organizer'])){
			$tmp = $this->raw['organizer'];
		}else if(isset($this->raw['modelData'])){
			$tmp = $this->raw['modelData']['organizer'];
		}
		if(isset($this->raw['creator'])){
			$attendee['creator'] = $this->raw['creator']['email'];
		}
		$this->organizerEmail = $tmp['email'];
		if(isset($tmp['displayName'])){
			$this->organizerName = $tmp['displayName'];
		}
		$this->isOrganizer = $tmp['self'];
		$tmp = NULL;
		if(isset($this->raw['start'])){
			$tmp = $this->raw['start'];
		}else if(isset($this->raw['modelData'])){
			$tmp = $this->raw['modelData']['start'];
		}
		if(isset($tmp['date'])){
			$this->startDate = $tmp['date'];
		}else{
			$this->startDateTime = $tmp['dateTime'];
		}
		if(isset($tmp['timeZone'])){
			$this->startTimeZone = $tmp['timeZone'];
		}
		
		$tmp = NULL;
		if(isset($this->raw['end'])){
			$tmp = $this->raw['end'];
		}else if(isset($this->raw['modelData'])){
			$tmp = $this->raw['modelData']['end'];
		}

		if(isset($tmp['date'])){
			$this->endDate = $tmp['date'];
		}else{
			$this->endDateTime = $tmp['dateTime'];
		}
		if(isset($tmp['timeZone'])){
			$this->endTimeZone = $tmp['timeZone'];
		}

		if(isset($this->raw['recurringEventId'])){
			$this->recurringEventId = $this->raw['recurringEventId'];
			$tmp = NULL;
			if(isset($this->raw['originalStartTime'])){
				$tmp = $this->raw['originalStartTime'];
			}else if(isset($this->raw['modelData']) ){
				$tmp = $this->raw['modelData']['originalStartTime'];
			}
			if(isset($tmp['date'])){
				$this->originalStartTime = $tmp['date'];
				$this->originalStartDate = $tmp['date'];
			}else{
				$this->originalStartTime = $tmp['dateTime'];
			}
			if(isset($tmp['timeZone'])){
				$this->originalStartTimeZone = $tmp['timeZone'];
			}
		}
		$this->iCalId = $this->raw['iCalUID'];
		$tmp = NULL;
		if(isset($this->raw['attendees'])){
			$tmp = $this->raw['attendees'];
		}else if(isset($this->raw['modelData']['attendees'])){
			$tmp = $this->raw['modelData']['attendees'];
		}
		if(isset($tmp)){
			$this->attendees = array();
			foreach ($tmp as $value) {
				$attendee = array();
				$attendee['email'] = $value['email'];
				if(isset($value['displayName'])){
					$attendee['name'] = $value['displayName'];
				}
				if(isset($value['responseStatus'])){
					$attendee['responseStatus'] = self::$statusInMap[$value['responseStatus']];
				}
				if(isset($value['organizer'])){
					$attendee['organizer'] = $value['organizer'];
				}
				$this->attendees[] = $attendee;
			}
		}
		if(isset($this->raw['recurrence'])){
			$this->rrule = array();
			foreach ($this->raw['recurrence'] as $value) {
				try {
					$this->rrule[] = RRule::createFromRfcString($value);
				} catch (Exception $e){
					echo $e;
				}
			}
		}
		$tmp = NULL;
		if(isset($this->raw['reminders'])) {
			$tmp = $this->raw['reminders'];
		}else if(isset($this->raw['modelData'])){
			$tmp = $this->raw['modelData']['reminders'];
		}

		$this->reminderUseDefault = $tmp['useDefault'];
		if(isset($tmp['overrides'])){
			$this->reminderOverrides = $tmp['overrides'];
			$this->reminderMinutesBeforeStart = $tmp['overrides'][0]['minutes'];
		}

	}

	public function toProviderArray(){
		$event = array("start" => array(),
						"end" => array());

		if(isset($this->id)){
			$event['id'] = $this->id;
		}
		if(isset($this->status)){
			$event["status"] = GoogleEvent::$statusOutMap[$this->status];
		}
		if(isset($this->htmlLink)){
			$event["htmlLink"] = $this->htmlLink;
		}
		if(isset($this->summary)){
			$event["summary"] = $this->summary;
		}
		if(isset($this->location)){
			$event["location"] = $this->location;
		}
		if(isset($this->description)){
			$event["description"] = $this->description;
		}
		if(isset($this->iCalId)){
			$event["iCalUID"] = $this->iCalId;
		}
		if(isset($this->organizerEmail)){
			$event['organizer'] = array("email" => $this->organizerEmail);
		}
		if(isset($this->organizerName)){
			$event['organizer']['displayName'] = $this->organizerName;
		}
		if(isset($this->startDate)){
			$event['start']['date'] = $this->startDate;
		}else if(isset($this->startDateTime)){
			$event['start']['dateTime'] = $this->startDateTime;
		}

		if(isset($this->startTimeZone)){
			$event['start']['timeZone'] = $this->startTimeZone;
		}

		if(isset($this->endDate)){
			$event["end"] = array("date" => $this->endDate);
		}else if(isset($this->endDateTime)){
			$event["end"] = array("dateTime" => $this->endDateTime);
		}
		if(isset($this->endTimeZone)){
			$event['end']['timeZone'] = $this->endTimeZone;
		}

		if(isset($this->rrule)){
			$event['recurrence'] = $this->rrule;
			foreach($this->rrule as $value){
				$event['recurrence'][] = $value->rfcString();
			}
		}

		if(isset($this->recurringEventId)){
			$event['recurringEventId'] = $this->recurringEventId;
		}
		
		if(isset($this->originalStartDate)){
			$event['originalStartTime'] = array("date" => $this->originalStartDate);
		}else if(isset($this->originalStartTime)){
			$event['originalStartTime'] = array("dateTime" => $this->originalStartTime);
		}
		if(isset($this->originalStartTimeZone)){
			$event['originalStartTime']['timeZone'] = $this->originalStartTimeZone;
		}

		if(isset($this->attendees)){
			$event['attendees'] = array();
			foreach ($this->attendees as $value) {
				$tmp = array("email" => $value['email']);
				if(isset($value['name'])){
					$tmp["displayName"] = $value['name'];
				}
				if(isset($value['responseStatus'])){
					$tmp["responseStatus"] = self::$statusOutMap[$value['responseStatus']];
				}
				$event['attendees'][] = $tmp;
			}
		}

		if(isset($this->reminderUseDefault)){
			$event['reminders'] = array("useDefault" => $this->reminderUseDefault);
			if(isset($this->reminderOverrides)){
				$event['reminders']['overrides'] = array();
				foreach ($this->reminderOverrides as $value) {
					$reminder = new Google_Service_Calendar_EventReminder();
					$reminder->setMethod($value['method']);
					$reminder->setMinutes($value['minutes']);	
					$event['reminders']['overrides'][] = $reminder;
				}
				$event['reminders']['overrides'][0]->setMinutes($this->reminderMinutesBeforeStart);
			}else if(isset($this->reminderMinutesBeforeStart)){
				$reminder = new Google_Service_Calendar_EventReminder();
				$reminder->setMethod('email');
				$reminder->setMinutes($this->reminderMinutesBeforeStart);
				$event['reminders']['overrides'] = array($reminder);
			}
		}

		return $event;
	}
}
?>