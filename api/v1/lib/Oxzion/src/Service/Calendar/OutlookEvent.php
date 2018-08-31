<?php
namespace Calendar;
use RRule\RRule;
use Model\Event;
use DateTime;

class OutlookEvent extends CalendarEvent{
	const DAILY = 'daily';
	const WEEKLY = 'weekly';
	const MONTHLY = 'absoluteMonthly';
	const MONTHLY_RELATIVE = 'relativeMonthly';
	const YEARLY= 'absoluteYearly';
	const YEARLY_RELATIVE= 'relativeYearly';

	const SUNDAY = 'sunday';
	const MONDAY = 'monday';
	const TUESDAY = 'tuesday';
	const WEDNESDAY = 'wednesday';
	const THURSDAY = 'thursday';
	const FRIDAY = 'friday';
	const SATURDAY = 'saturday';

	const FIRST_WEEK = 'first';
	const SECOND_WEEK = 'second';
	const THIRD_WEEK = 'third';
	const FOURTH_WEEK = 'fourth';
	const LAST_WEEK = 'last';

	const RECUURENCE_RANGE_NOEND = "noEnd";
	const RECUURENCE_RANGE_ENDDATE = "endDate";
	const RECUURENCE_RANGE_NUMBERED = "numbered";


	private static $statusInMap = array("tentativelyAccepted" => CalendarEvent::RESPONSE_STATUS_TENTATIVE, 
											"notResponded" => CalendarEvent::RESPONSE_STATUS_NEEDS_ACTION,
											"organizer" => CalendarEvent::RESPONSE_STATUS_ORGANIZER,
											"none" => CalendarEvent::RESPONSE_STATUS_NONE,
											"declined" => CalendarEvent::RESPONSE_STATUS_DECLINED,
											"accepted" => CalendarEvent::RESPONSE_STATUS_ACCEPTED);
	private static $statusOutMap = array(CalendarEvent::RESPONSE_STATUS_TENTATIVE => "										tentativelyAccepted", 
												CalendarEvent::RESPONSE_STATUS_NEEDS_ACTION => "notResponded",
												CalendarEvent::RESPONSE_STATUS_NONE => "none",
												CalendarEvent::RESPONSE_STATUS_ORGANIZER => "organizer",
												CalendarEvent::RESPONSE_STATUS_DECLINED => "declined",
												CalendarEvent::RESPONSE_STATUS_ACCEPTED => "accepted");
	
	
	private static $weekRruleMap = array(self::SUNDAY => 'SU', self::MONDAY => 'MO', self::TUESDAY => 'TU',
										 self::WEDNESDAY => 'WE', self::THURSDAY => 'TH', self::FRIDAY => 'FR',
										 self::SATURDAY => 'SA');
	private static $weekOutlookMap = array('SU' => self::SUNDAY, 'MO' => self::MONDAY, 'TU' => self::TUESDAY,
										 'WE' => self::WEDNESDAY, 'TH' => self::THURSDAY, 'FR' => self::FRIDAY,
										 'SA' => self::SATURDAY);

	private static $weekMap = array(self::FIRST_WEEK => 1, self::SECOND_WEEK => 2, self::THIRD_WEEK => 3, self::FOURTH_WEEK => 4);
	
	public function __construct(array $raw = NULL){
		$this->provider = CalendarClient::OUTLOOK;
		$this->raw = $raw;
		if(isset($raw)){
			$this->parse();
		}
	}

	protected function parse(){
		$this->id = $this->raw['id'];
		if($this->raw['isCancelled']){
			$this->status = CalendarEvent::RESPONSE_STATUS_CANCELLED;
		}else{
			$this->status = self::$statusInMap[$this->raw['responseStatus']['response']];
		}
		$this->htmlLink = $this->raw['webLink'];
		$this->created = $this->raw['createdDateTime'];
		$this->summary = $this->raw['subject'];
		
		if(isset($this->raw['bodyPreview'])){
			$this->description = $this->raw['bodyPreview'];
		}
		if(isset($this->raw['body']) && $this->raw['body']['contentType'] == 'html'){
			$this->descriptionHtml = $this->raw['body']['content'];
		}
		if(isset($this->raw['location']['displayName'])){
			$this->location = $this->raw['location']['displayName'];
		}
		$this->isOrganizer = $this->raw['isOrganizer'];
		
		if(isset($this->raw['organizer'])) {
			$this->organizerEmail = $this->raw['organizer']['emailAddress']['address'];
			if(isset($this->raw['organizer']['emailAddress']['name'])){
				$this->organizerName = $this->raw['organizer']['emailAddress']['name'];
			}
		}
		$tmp = NULL;
		if(isset($this->raw['start'])){
			$tmp = $this->raw['start'];
			if(isset($tmp['dateTime'])){
				if($this->raw['isAllDay']){
					$this->startDate = $tmp['dateTime'];
				}else{
					$this->startDateTime = $tmp['dateTime'];
				}
			}
			if(isset($tmp['timeZone'])){
				$this->startTimeZone = $tmp['timeZone'];
			}
		}
		
		
		$tmp = NULL;
		if(isset($this->raw['end'])){
			$tmp = $this->raw['end'];
			if(isset($tmp['dateTime'])){
				if($this->raw['isAllDay']){
					$this->endDate = $tmp['dateTime'];
				}else{
					$this->endDateTime = $tmp['dateTime'];
				}
			}
			if(isset($tmp['timeZone'])){
				$this->endTimeZone = $tmp['timeZone'];
			}
		}

		

		if(isset($this->raw['seriesMasterId'])){
			$this->recurringEventId = $this->raw['seriesMasterId'];
		}
		if(isset($this->raw['originalStart'])){
			$this->originalStartTime = $this->raw['originalStart'];
		}
		if(isset($this->raw['originalStartTimeZone'])){
			$this->originalStartTimeZone = $this->raw['originalStartTimeZone'];
		}
		$this->iCalId = $this->raw['iCalUId'];
		$tmp = NULL;
		if(isset($this->raw['attendees'])){
			$tmp = $this->raw['attendees'];
			if(isset($tmp)){
				$this->attendees = array();
				foreach ($tmp as $value) {
					$attendee = array();
					$attendee['email'] = $value['emailAddress']['address'];
					if(isset($value['emailAddress']['name'])){
						$attendee['name'] = $value['emailAddress']['name'];
					}
					if(isset($value['status']['response'])){
						$attendee['responseStatus'] = self::$statusInMap[$value['status']['response']];
					}
					
					$this->attendees[] = $attendee;
				}
			}
		}
		
		$tmp = NULL;
		if(isset($this->raw['recurrence'])){
			$tmp = $this->raw['recurrence'];
			$this->rrule = array();
			$value = $this->createRRuleString($tmp);
			$this->rrule[] = RRule::createFromRfcString($value);
		}
		if($this->raw['isReminderOn']) {
			$this->reminderMinutesBeforeStart = $this->raw['reminderMinutesBeforeStart'];
		}

	}

	public function createRRuleString(array $recurrence){
		$pattern = $recurrence['pattern'];
		$range = $recurrence['range'];
		$rule = "RRULE:";
		if($pattern['type'] == self::DAILY){
			$rule = $rule."FREQ=DAILY";
			if($range['type'] == self::RECUURENCE_RANGE_ENDDATE){
				$rule = $rule.";UNTIL=".$this->formatICalDateTime($range['endDate']);
				//The end date/time is marked as 12:00am on the last day.  When this is
        		//parsed by php-ical, the last day of the sequence is missed. The MS Outlook
        		//code has the same bug/issue.  To fix this, change the end time from
        		//12:00 am to 11:59:59 pm.
        		$rule = str_replace('T000000', 'T235959', $rule);
			}else if($range['type'] == self::RECUURENCE_RANGE_NUMBERED){
				$rule = $rule.";COUNT=".$range['numberOfOccurrences'];
			}
			$rule = $rule.";INTERVAL=".$pattern['interval'];
		}else if($pattern['type'] == self::MONTHLY){
			$rule = $rule."FREQ=MONTHLY";
			if($range['type'] == self::RECUURENCE_RANGE_ENDDATE){
				$rule = $rule.";UNTIL=".$this->formatICalDateTime($range['endDate']);
			}else if($range['type'] == self::RECUURENCE_RANGE_NUMBERED){
				$rule = $rule.";COUNT=".$range['numberOfOccurrences'];
			}
			$rule = $rule.";INTERVAL=".$pattern['interval'];
			$rule = $rule.";BYMONTHDAY=".$pattern['dayOfMonth'];
		}else if($pattern['type'] == self::MONTHLY_RELATIVE){
			$rule = $rule."FREQ=MONTHLY";
			if($range['type'] == self::RECUURENCE_RANGE_ENDDATE){
				$rule = $rule.";UNTIL=".$this->formatICalDateTime($range['endDate']);
			}else if($range['type'] == self::RECUURENCE_RANGE_NUMBERED){
				$rule = $rule.";COUNT=".$range['numberOfOccurrences'];
			}
			$rule = $rule.";INTERVAL=".$pattern['interval'];
			if($pattern['index'] == self::LAST_WEEK){
				$rule = $rule.";BYWEEKNO=-1";
				$rule = $rule.";BYDAY=".$this->getDaysOfWeek('', $pattern['daysOfWeek']);
			}else{
				$rule = $rule.";BYDAY=".$this->getDaysOfWeek(self::$weekMap[$pattern['index']], $pattern['daysOfWeek']);
			}
		}else if($pattern['type'] == self::WEEKLY){
			$rule = $rule."FREQ=WEEKLY";
			if($range['type'] == self::RECUURENCE_RANGE_ENDDATE){
				$rule = $rule.";UNTIL=".$this->formatICalDateTime($range['endDate']);
			}else if($range['type'] == self::RECUURENCE_RANGE_NUMBERED){
				$rule = $rule.";COUNT=".$range['numberOfOccurrences'];
			}
			$rule = $rule.";INTERVAL=".$pattern['interval'];
			$rule = $rule.";BYDAY=".$this->getDaysOfWeek('', $pattern['daysOfWeek']);
		}else if($pattern['type'] == self::YEARLY){
			$rule = $rule."FREQ=YEARLY";
			if($range['type'] == self::RECUURENCE_RANGE_ENDDATE){
				$rule = $rule.";UNTIL=".$this->formatICalDateTime($range['endDate']);
			}else if($range['type'] == self::RECUURENCE_RANGE_NUMBERED){
				$rule = $rule.";COUNT=".$range['numberOfOccurrences'];
			}
			$rule = $rule.";INTERVAL=".$pattern['interval'];
			$rule = $rule.";BYDAY=".$this->getDaysOfWeek('', $pattern['daysOfWeek']);
		}else if($pattern['type'] == self::YEARLY_RELATIVE){
			$rule = $rule."FREQ=YEARLY";
			if($range['type'] == self::RECUURENCE_RANGE_ENDDATE){
				$rule = $rule.";UNTIL=".$this->formatICalDateTime($range['endDate']);
			}else if($range['type'] == self::RECUURENCE_RANGE_NUMBERED){
				$rule = $rule.";COUNT=".$range['numberOfOccurrences'];
			}
			$rule = $rule.";BYMONTH=".$pattern['month'];
			$rule = $rule.";BYDAY=".$this->getDaysOfWeek(self::$weekMap[$pattern['index']], $pattern['daysOfWeek']);
		}

		if(isset($pattern['firstDayOfWeek'])){
			$rule=$rule.";WKST=".self::$weekRruleMap[$pattern['firstDayOfWeek']];
		}

		return $rule;
	}
	public function getRecurrencePattern(array $rrule, $recurrenceTimeZone = NULL){
		$pattern = array();
		$range = array();
		switch ($rrule['FREQ']) {
			case 'DAILY':
				$pattern['type'] = self::DAILY;
				break;
			case 'WEEKLY':
				$pattern['type'] = self::WEEKLY;
				$tmp = $this->getOlDaysOfWeek($rrule['BYDAY']);
				$pattern['daysOfWeek'] = $tmp['daysOfWeek'];
				$pattern['index'] = self::FIRST_WEEK;
				break;
			case 'MONTHLY':
				if(isset($rrule['BYMONTHDAY'])){
					$pattern['type'] = self::MONTHLY;
					$pattern['dayOfMonth'] = $rrule['BYMONTHDAY'];
				}else{
					$pattern['type'] = self::MONTHLY_RELATIVE;
					if(isset($rrule['BYWEEKNO'])){
						$pattern['index'] = self::LAST_WEEK;
						$tmp = $this->getOlDaysOfWeek($rrule['BYDAY']);
						$pattern['daysOfWeek'] = $tmp['daysOfWeek'];
					}else{
						$tmp = $this->getOlDaysOfWeek($rrule['BYDAY'], true);
						$pattern['index'] = self::$weekOutlookMap[$tmp['index']];
						$pattern['daysOfWeek'] = $tmp['daysOfWeek'];
					}
				}
				break;	
			case 'YEARLY':
				if(isset($rrule['BYMONTH'])){
					$pattern['type'] = self::YEARLY_RELATIVE;
					$pattern['month'] = $rrule['BYMONTH'];
					$tmp = $this->getOlDaysOfWeek($rrule['BYDAY'], true);
					$pattern['index'] = self::$weekOutlookMap[$tmp['index']];
					$pattern['daysOfWeek'] = $tmp['daysOfWeek'];
				}else{
					$pattern['type'] = self::YEARLY;
					$tmp = $this->getOlDaysOfWeek($rrule['BYDAY']);
					$pattern['daysOfWeek'] = $tmp['daysOfWeek'];
				}
				break;	
			default:
				# code...
				break;
		}
		
		$range['startDate'] = $this->raw['recurrence']['range']['startDate'];
		$range['endDate'] = '0001-01-01';
		$range['recurrenceTimeZone'] = isset($this->raw) && isset($this->raw['recurrence']['range']['recurrenceTimeZone']) ? $this->raw['recurrence']['range']['recurrenceTimeZone'] : $recurrenceTimeZone;
		if(isset($rrule['UNTIL'])){
			$range['type'] = self::RECUURENCE_RANGE_ENDDATE;
			$range['endDate'] = $this->formatIsoDateTime($rrule['UNTIL']);
		}else if(isset($rrule['COUNT'])){
			$range['type'] = self::RECUURENCE_RANGE_NUMBERED;
			$range['numberOfOccurrences'] = $rrule['COUNT'];
		}else{
			$range['type'] = self::RECUURENCE_RANGE_NOEND;
		}
		if(isset($rrule['INTERVAL'])){
			$pattern['interval'] = $rrule['INTERVAL'];
		}
		if(isset($rrule['WKST'])){
			$pattern['firstDayOfWeek'] = self::$weekOutlookMap[$rrule['WKST']];
			
		}

		return array('pattern' => $pattern, 'range' => $range);
		
	}

	private function getOlDaysOfWeek($daysOfWeek, $parseIndex = False){
		$olDaysOfWeek = array('daysOfWeek' => array());
		if(is_array($daysOfWeek)){
			foreach ($daysOfWeek as $value) {
				if($parseIndex){
					$valid = preg_match('/^([+-]?[0-9]+)?([A-Z]{2})$/', $value, $matches);
					$olDaysOfWeek['index'] = (int)$matches[1];
					$tmp = $matches[2];
				}else{
					$tmp = $value;
				}
				$olDaysOfWeek['daysOfWeek'][] = self::$weekOutlookMap[$tmp];
			}
		}else{
			$olDaysOfWeek['daysOfWeek'][] = self::$weekOutlookMap[$daysOfWeek];
		}

		return $olDaysOfWeek;
	}
	private function getDaysOfWeek($week, $daysOfWeek){
		$weekDays = '';
		foreach ($daysOfWeek as $value) {
			$weekDays = $weekDays == '' ? $weekDays : $weekDays.',';
			$weekDays = $weekDays.$week.self::$weekRruleMap[$value];
		}
		return $weekDays;
	}
	private function formatICalDateTime($date){
		$dateTime = DateTime::createFromFormat('Y-m-d', $date)->format('YmdTHis');
		return $dateTime;
	}

	private function formatIsoDateTime($date){
		$dateTime = $date->format('Y-m-d');
		return $dateTime;
	}

	public function toProviderArray(){
		$event = array("start" => array(),
						"end" => array());

		if(isset($this->id)){
			$event['id'] = $this->id;
		}
		
		if(isset($this->status)){

			if($this->status == CalendarEvent::RESPONSE_STATUS_CANCELLED){
				$event['isCancelled'] = True;
			}else{
				$event['responseStatus'] = array('response' => self::$statusOutMap[$this->status]);
			}
		}
		if(isset($this->htmlLink)){
			$event["webLink"] = $this->htmlLink;
		}
		if(isset($this->summary)){
			$event["subject"] = $this->summary;
		}
		if(isset($this->location)){
			$event["location"] = array('displayName' => $this->location);
			$event["location"]['address'] = isset($this->raw) && isset($this->raw['location']['address']) ? $this->raw['location']['address'] : NULL;
		}
		if(isset($this->description)){
			$event["bodyPreview"] = $this->description;
		}
		if(isset($this->descriptionHtml)){
			$event['body'] = array('contentType' => 'html', 'content' => $this->descriptionHtml);
		}else{
			$event['body'] = array('contentType' => 'html', 'content' => "<html><head></head><body><p>".$this->description."</p></body></html>");
		}
		if(isset($this->iCalId)){
			$event["iCalUID"] = $this->iCalId;
		}
		if(isset($this->organizerEmail)){
			$event['organizer'] = array("emailAddress" => array("address" => $this->organizerEmail));
		}
		if(isset($this->organizerName)){
			$event['organizer']['emailAddress']['name'] = $this->organizerName;
		}
		if(isset($this->startDate)){
			$event['isAllDay'] = True;
			$event['start']['dateTime'] = $this->startDate;
		}else if(isset($this->startDateTime)){
			$event['start']['dateTime'] = $this->startDateTime;
		}

		if(isset($this->startTimeZone)){
			$event['start']['timeZone'] = $this->startTimeZone;
		}

		if(isset($this->endDate)){
			$event["end"] = array("dateTime" => $this->endDate);
		}else if(isset($this->endDateTime)){
			$event["end"] = array("dateTime" => $this->endDateTime);
		}
		if(isset($this->endTimeZone)){
			$event['end']['timeZone'] = $this->endTimeZone;
		}

		if(isset($this->rrule)){
			$event['recurrence'] = $this->getRecurrencePattern($this->rrule[0]->getRule(), $this->startTimeZone);
			if(!isset($event['recurrence']['range']['startDate'])){
				if(isset($this->startDateTime)){
					$event['recurrence']['range']['startDate'] = substr($this->startDateTime, 0, 10);
				}else{
					$event['recurrence']['range']['startDate'] = substr($this->startDate, 0, 10);
				}
			}
		}

		if(isset($this->recurringEventId)){
			$event['seriesMasterId'] = $this->recurringEventId;
		}
		
		if(isset($this->originalStartTime)){
			$event['originalStart'] = $this->originalStartDate;
		}
		if(isset($this->originalStartTimeZone)){
			$event['originalStartTimeZone'] = $this->originalStartTimeZone;
		}

		if(isset($this->attendees)){
			$event['attendees'] = array();
			foreach ($this->attendees as $value) {
				$tmp = array("emailAddress" => array('address' => $value['email']));
				if(isset($value['name'])){
					$tmp['emailAddress']["name"] = $value['name'];
				}
				if(isset($value['responseStatus'])){
					$tmp["status"] = array('response' => self::$statusOutMap[$value['responseStatus']]);
				}

				$event['attendees'][] = $tmp;
			}
		}

		if(isset($this->reminderMinutesBeforeStart)){
			$event['reminderMinutesBeforeStart'] = $this->reminderMinutesBeforeStart;
			$event['isReminderOn'] = True;
		}

		return $event;
	}
}
?>