<?php
error_reporting(E_ALL);
// error_reporting(E_STRICT);
ini_set('display_errors', 1);

// fix missing DOCUMENT_ROOT in IIS
if(!isset($_SERVER['DOCUMENT_ROOT'])){ if(isset($_SERVER['SCRIPT_FILENAME'])){
    $_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0-strlen($_SERVER['PHP_SELF'])));
}; };
if(!isset($_SERVER['DOCUMENT_ROOT'])){ if(isset($_SERVER['PATH_TRANSLATED'])){
    $_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0-strlen($_SERVER['PHP_SELF'])));
}; };

require_once(dirname(__FILE__) .'/conf.php');
require_once(dirname(__FILE__) .'/server/adodb5/adodb.inc.php');
require_once(dirname(__FILE__) .'/server/classes/cls_properties.php');
require_once(dirname(__FILE__) .'/server/classes/cls_core.php');
require_once(dirname(__FILE__) .'/server/classes/cls_db.php');
require_once(dirname(__FILE__) .'/server/classes/cls_user.php');
require_once(dirname(__FILE__) .'/server/classes/cls_security.php');
require_once(dirname(__FILE__) .'/server/classes/cls_phpeventcal.php');
require_once(dirname(__FILE__) .'/server/classes/cls_util.php');
require_once(dirname(__FILE__) .'/server/classes/cls_calendar_settings.php');
require_once(dirname(__FILE__) .'/server/classes/cls_calendars.php');
require_once(dirname(__FILE__) .'/server/classes/cls_events.php');
require_once(dirname(__FILE__) .'/server/classes/cls_reminder.php');
require_once(dirname(__FILE__) .'/server/classes/cls_organizer.php');
require_once(dirname(__FILE__) .'/server/classes/cls_resource.php');
require_once(dirname(__FILE__) .'/server/classes/cls_venue.php');
require_once(dirname(__FILE__) .'/server/classes/sendicsdata.php');

define('PEC_SESSION_KEY', '_oPEC');
define('FULL_CALENDAR_VERSION','/fullcalendar-2.0.0'); //===preceding / is required
$host = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:"localhost/";
// echo 'http://'.$host.PEC_PATH.'/';exit;
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' )? "https://" : "http://";
define('BASE_URL',$protocol.$host.PEC_PATH.'/');
define('BASE_DIR',dirname(__FILE__).'/');
define('SERVER_HTML_DIR',BASE_DIR.'server/html/');
define('SERVER_HTML_INCLUDE_DIR',SERVER_HTML_DIR.'includes/');
define('OXZION_BASE_URL','http://localhost:8080');
?>
