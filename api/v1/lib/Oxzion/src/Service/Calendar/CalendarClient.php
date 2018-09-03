<?php
namespace Calendar;

abstract class CalendarClient{
	const GOOGLE = "GOOGLE";
	const OUTLOOK = "OUTLOOK";

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
	 *		items (CalendarEvent)	- List if calendarEvent objects 
	 */
	abstract function getEvents($calendarId, array $options);
	/**
	 *	$calendarId (string) - Calendar identifier. To retrieve calendar IDs use the 
	 *							getCalendarList method. If you want to access the primary 
	 *							calendar of the currently logged in user, use the "primary" 
	 *							keyword. Default primary
	 *	#eventID (string) - Event identifier
	 */
	abstract function getEvent($eventId, $calendarId);
	/**
	 *	$event (CalendarEvent) - CalendarEvent object
	 *	$calendarId (string) - The calendar id to add the event to
	 *
	 */
	abstract function createEvent($event, $calendarId = 'primary');

	/**
	 *	$event (CalendarEvent) - CalendarEvent object
	 *	$calendarId (string) - The calendar id to add the event to
	 *
	 */
	abstract function updateEvent($event, $calendarId = 'primary');

	/**
	 *	$eventIs (string) - event id of the event to be deleted
	 *	$calendarId (string) - The calendar id to add the event to
	 *	$options (array)	-	Optional parameter
	 *			sendNotifications (boolean)	- Whether to send notifications about the 
	 *										  deletion of the event. Optional. The default is 
	 *										  False.
	 *
	 */
	abstract function deleteEvent($eventId, $calendarId = 'primary', $options);

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
	abstract function getCalendarList(array $options);
	public static function getCalendarClient($email, $provider, $accessToken){
		$client;
		switch ($provider) {
			case self::GOOGLE:
				$client = new GoogleCalendarClient($email, $accessToken);
				break;
			case self::OUTLOOK:
				$client = new OutlookCalendarClient($email, $accessToken);
				break;
			
			
		}

		return $client;
	}
}
?>