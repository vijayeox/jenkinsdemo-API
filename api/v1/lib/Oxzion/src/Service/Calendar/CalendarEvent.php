<?php
namespace Calendar;
use RRule\RRule;
abstract class CalendarEvent {
	const RESPONSE_STATUS_NONE = 0;
	const RESPONSE_STATUS_NEEDS_ACTION = 1;
	const RESPONSE_STATUS_TENTATIVE = 2;
	const RESPONSE_STATUS_DECLINED = 3;
	const RESPONSE_STATUS_ACCEPTED = 4;
	const RESPONSE_STATUS_CONFIRMED = 5;
	const RESPONSE_STATUS_CANCELLED = 6;
	const RESPONSE_STATUS_ORGANIZER = 7;

	public $id;
	public $status;//accepted, tentative, declined
	public $created;
	public $summary;
	public $description;
	public $descriptionHtml;
	public $location;
	public $htmlLink;
	public $organizerEmail;
	public $organizerName;
	public $isOrganizer;
	public $startDate;//only for all day event
	public $startDateTime;
	public $startTimeZone;
	public $endDate;//only for all day event
	public $endDateTime;
	public $endTimeZone;
	public $iCalId;
	public $rrule;//array of rules
	public $recurringEventId;
	public $originalStartTime;
	public $originalStartTimeZone;
	public $attendees;//{"email": string, "name" : string, "responseStatus" : int, "organizer" : boolean}
	public $reminderUseDefault;
	public $reminderMinutesBeforeStart;

	protected $provider;//outlook, google
	protected $raw;

	protected abstract function parse();
	public abstract function toProviderArray();
}
?>