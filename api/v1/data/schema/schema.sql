-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 12, 2018 at 06:30 AM
-- Server version: 5.6.40
-- PHP Version: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ox_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `active_brokers`
--

CREATE TABLE IF NOT EXISTS `active_brokers` (
  `id` int(11) NOT NULL,
  `accountname` varchar(100) NOT NULL,
  `lookupcode` varchar(20) NOT NULL,
  `phonenumber` varchar(12) NOT NULL,
  `primarycontact` varchar(30) NOT NULL,
  `address1` varchar(200) NOT NULL,
  `address2` varchar(200) DEFAULT NULL,
  `city` varchar(30) NOT NULL,
  `statecode` varchar(20) NOT NULL,
  `zipcode` int(10) NOT NULL,
  `faxnumber` varchar(12) DEFAULT NULL,
  `primaryemail` varchar(100) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL COMMENT 'broker / prospect',
  `modified_date` date DEFAULT NULL,
  `longitude` decimal(18,15) DEFAULT NULL,
  `latitude` decimal(18,15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(13) unsigned NOT NULL,
  `avatarid` int(13) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `admin_menus`
--

CREATE TABLE IF NOT EXISTS `admin_menus` (
  `id` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `modulename` varchar(100) DEFAULT NULL,
  `parentid` int(11) NOT NULL DEFAULT '0',
  `link` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `admin_menu_access`
--

CREATE TABLE IF NOT EXISTS `admin_menu_access` (
  `id` int(11) NOT NULL,
  `adminid` int(11) NOT NULL,
  `menuid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `admission_primary_physician`
--

CREATE TABLE IF NOT EXISTS `admission_primary_physician` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `admission_referring_physician`
--

CREATE TABLE IF NOT EXISTS `admission_referring_physician` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `admission_values`
--

CREATE TABLE IF NOT EXISTS `admission_values` (
  `id` int(11) NOT NULL,
  `ssn` varchar(50) DEFAULT NULL,
  `instanceformid` int(11) NOT NULL,
  `formposition` varchar(15) NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `dateofbirth` varchar(50) DEFAULT NULL,
  `referraldate` varchar(50) DEFAULT NULL,
  `referralsource` varchar(50) DEFAULT NULL,
  `referothers` varchar(100) DEFAULT NULL,
  `otherdetails` varchar(100) DEFAULT NULL,
  `primarydoctor` varchar(100) DEFAULT NULL,
  `otherdoc` varchar(100) DEFAULT NULL,
  `hospitalchoice` int(11) DEFAULT NULL,
  `referringphysicianid` int(11) DEFAULT NULL,
  `dxcode` varchar(50) DEFAULT NULL,
  `primepayor` tinyint(4) DEFAULT NULL,
  `clinicallyapproved` tinyint(1) DEFAULT NULL,
  `watchlistapproval` tinyint(4) DEFAULT NULL,
  `watchlistapprovalname` varchar(200) DEFAULT NULL,
  `watchlistapprovaldate` varchar(50) DEFAULT NULL,
  `streetone` varchar(255) DEFAULT NULL,
  `streettwo` varchar(255) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `isstatementaddress` tinyint(1) DEFAULT NULL,
  `statementstate` varchar(100) DEFAULT NULL,
  `healthaddress` varchar(255) DEFAULT NULL,
  `financeaddress` varchar(255) DEFAULT NULL,
  `statementcity` varchar(100) DEFAULT NULL,
  `statementaddress` varchar(500) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `personhealth` int(11) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `personfinance` int(11) DEFAULT NULL,
  `signindate` varchar(50) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `levelofcare` tinyint(4) DEFAULT NULL,
  `notskilled` int(11) DEFAULT NULL,
  `verifymedicare` tinyint(4) DEFAULT NULL,
  `needform7000` tinyint(4) DEFAULT NULL,
  `appliedpasrr` tinyint(4) DEFAULT NULL,
  `selectterm` tinyint(4) DEFAULT NULL,
  `mcr3` varchar(100) DEFAULT NULL,
  `hmo3` varchar(100) DEFAULT NULL,
  `verificationdate3` varchar(100) DEFAULT NULL,
  `mcr4` varchar(100) DEFAULT NULL,
  `hmo4` varchar(100) DEFAULT NULL,
  `verificationdate4` varchar(100) DEFAULT NULL,
  `hospitaladmitdate` varchar(50) DEFAULT NULL,
  `hospitaldischargedate` varchar(50) DEFAULT NULL,
  `primarypayer` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `admission_watchlist`
--

CREATE TABLE IF NOT EXISTS `admission_watchlist` (
  `id` int(11) NOT NULL,
  `ssn` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `dateofbirth` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `admit_dispatched_new`
--

CREATE TABLE IF NOT EXISTS `admit_dispatched_new` (
  `RiskNumber` varchar(200) DEFAULT NULL,
  `branch` varchar(110) DEFAULT NULL,
  `Dispatcheddate` date DEFAULT NULL,
  `Admitted` varchar(45) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `admit_dispatched_renewal`
--

CREATE TABLE IF NOT EXISTS `admit_dispatched_renewal` (
  `RiskNumber` varchar(200) DEFAULT NULL,
  `branch` varchar(110) DEFAULT NULL,
  `Dispatcheddate` date DEFAULT NULL,
  `Admitted` varchar(45) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `admit_received_new`
--

CREATE TABLE IF NOT EXISTS `admit_received_new` (
  `RiskNumber` varchar(200) DEFAULT NULL,
  `branch` varchar(110) DEFAULT NULL,
  `Receiveddate` date DEFAULT NULL,
  `Admitted` varchar(45) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `admit_received_renewal`
--

CREATE TABLE IF NOT EXISTS `admit_received_renewal` (
  `RiskNumber` varchar(200) DEFAULT NULL,
  `branch` varchar(110) DEFAULT NULL,
  `Receiveddate` date DEFAULT NULL,
  `Admitted` varchar(45) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `admneedbydate`
--

CREATE TABLE IF NOT EXISTS `admneedbydate` (
  `DaysOut` int(8) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `admneedbydatebybranch`
--

CREATE TABLE IF NOT EXISTS `admneedbydatebybranch` (
  `branch` varchar(45) DEFAULT NULL,
  `CurrentDate` date DEFAULT NULL,
  `DaysOut` int(8) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `agora_times`
--

CREATE TABLE IF NOT EXISTS `agora_times` (
  `Sl_No` int(100) NOT NULL,
  `Policy_Number` int(200) NOT NULL,
  `Process` varchar(1000) NOT NULL,
  `Line_of_Business` varchar(1000) NOT NULL,
  `Received_Date` date NOT NULL,
  `Processed_Date` date NOT NULL,
  `KRA` varchar(1000) NOT NULL,
  `Client_Id` varchar(1000) NOT NULL,
  `Status` varchar(200) NOT NULL,
  `tat` int(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE IF NOT EXISTS `alerts` (
  `id` int(11) NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `text` mediumtext CHARACTER SET utf8 NOT NULL,
  `type` varchar(15) CHARACTER SET utf8 DEFAULT 'system',
  `orgid` int(11) NOT NULL,
  `disabled` tinyint(4) DEFAULT '0',
  `enddate` datetime DEFAULT NULL,
  `creatorid` int(11) DEFAULT NULL,
  `startdate` datetime DEFAULT NULL,
  `socialstatus` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE IF NOT EXISTS `announcements` (
  `id` int(11) NOT NULL,
  `avatarid` int(32) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `startdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `enddate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `text` text NOT NULL,
  `name` varchar(259) NOT NULL,
  `orgid` int(32) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `media_location` text NOT NULL,
  `media_type` int(5) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `api_data`
--

CREATE TABLE IF NOT EXISTS `api_data` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `api` varchar(250) NOT NULL,
  `apiid` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `values` varchar(250) DEFAULT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='avatar id mapped from avatar_api table';

-- --------------------------------------------------------

--
-- Table structure for table `app_usage`
--

CREATE TABLE IF NOT EXISTS `app_usage` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `moduleid` int(11) DEFAULT NULL,
  `formid` int(11) DEFAULT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `count` int(11) NOT NULL,
  `date_used` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `systeminfo` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archives`
--

CREATE TABLE IF NOT EXISTS `archives` (
  `id` int(11) NOT NULL,
  `amid` int(11) NOT NULL COMMENT 'primary key of archives_master table',
  `table_name` varchar(255) DEFAULT NULL,
  `column_name` text,
  `records` longtext,
  `addedon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `archives_master`
--

CREATE TABLE IF NOT EXISTS `archives_master` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL COMMENT 'the logged in person''s id',
  `type` varchar(255) NOT NULL,
  `orgid` int(11) NOT NULL DEFAULT '0',
  `groupid` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='this table has a child table which is archives';

-- --------------------------------------------------------

--
-- Table structure for table `assesment_avatarscore`
--

CREATE TABLE IF NOT EXISTS `assesment_avatarscore` (
  `id` int(11) NOT NULL,
  `questionid` int(11) NOT NULL,
  `assesmentid` int(4) NOT NULL,
  `answer` varchar(1000) NOT NULL,
  `score` float NOT NULL,
  `avatarid` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `attempt` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assesment_category`
--

CREATE TABLE IF NOT EXISTS `assesment_category` (
  `id` int(11) NOT NULL,
  `name` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `orgid` int(11) NOT NULL,
  `instanceformid` int(11) DEFAULT NULL,
  `assigntonewhire` tinyint(4) DEFAULT '0',
  `project` varchar(100) DEFAULT NULL,
  `lob` varchar(100) DEFAULT NULL,
  `parentgroupid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assesment_category_groups`
--

CREATE TABLE IF NOT EXISTS `assesment_category_groups` (
  `id` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `groupid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `assesment_list`
--

CREATE TABLE IF NOT EXISTS `assesment_list` (
  `id` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `wizardid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `Type` int(11) NOT NULL,
  `duedate` datetime NOT NULL,
  `duration` time NOT NULL,
  `createdid` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `retake` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assesment_questions`
--

CREATE TABLE IF NOT EXISTS `assesment_questions` (
  `id` int(11) NOT NULL,
  `sequenceid` int(3) NOT NULL,
  `assesmentid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `question` varchar(5000) CHARACTER SET utf8 NOT NULL,
  `weightage` float NOT NULL,
  `answertype` varchar(20) NOT NULL,
  `options` varchar(2500) NOT NULL,
  `answer` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assesment_summary`
--

CREATE TABLE IF NOT EXISTS `assesment_summary` (
  `id` int(11) NOT NULL,
  `assesmentid` int(4) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `star_points` int(11) DEFAULT NULL,
  `duration` int(11) NOT NULL,
  `total_questions` int(11) DEFAULT NULL,
  `total_weightage` int(11) NOT NULL,
  `correct_answers` int(4) NOT NULL,
  `status` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `avatarid` int(11) NOT NULL,
  `completed_questions` int(4) NOT NULL,
  `completion_status` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `attachmentlogger`
--

CREATE TABLE IF NOT EXISTS `attachmentlogger` (
  `id` int(255) NOT NULL,
  `fileid` int(255) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `metalog` text,
  `filekey` text,
  `avatarid` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `auditlog`
--

CREATE TABLE IF NOT EXISTS `auditlog` (
  `id` int(11) NOT NULL,
  `moduleid` int(11) DEFAULT NULL,
  `formid` int(11) DEFAULT NULL,
  `avatarid` int(11) DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  `instanceformid` int(11) DEFAULT NULL,
  `fieldid` int(11) DEFAULT NULL,
  `oldvalue` text,
  `newvalue` text,
  `description` varchar(250) NOT NULL,
  `changetype` varchar(15) DEFAULT NULL,
  `modifieddate` datetime DEFAULT CURRENT_TIMESTAMP,
  `systeminfo` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auditlog_club`
--

CREATE TABLE IF NOT EXISTS `auditlog_club` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `typeid` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `actionon` int(11) DEFAULT NULL,
  `modifieddate` datetime NOT NULL,
  `groupid` int(11) DEFAULT NULL,
  `systeminfo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `avatars`
--

CREATE TABLE IF NOT EXISTS `avatars` (
  `id` int(10) unsigned NOT NULL,
  `gamelevel` varchar(111) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(32) NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `role` varchar(64) NOT NULL DEFAULT '',
  `last_login` datetime DEFAULT NULL,
  `orgid` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `emailnotify` varchar(100) NOT NULL DEFAULT 'Active',
  `sentinel` varchar(50) NOT NULL DEFAULT 'On',
  `icon` varchar(100) DEFAULT NULL,
  `gamemodeIcon` varchar(100) DEFAULT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'Active',
  `ipaddress` varchar(15) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `designation` varchar(45) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `address` varchar(300) DEFAULT NULL,
  `sex` varchar(20) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `about` varchar(2000) CHARACTER SET latin1 DEFAULT NULL,
  `interest` varchar(100) DEFAULT NULL,
  `hobbies` varchar(100) DEFAULT NULL,
  `managerid` int(11) DEFAULT NULL,
  `alertsacknowledged` tinyint(4) DEFAULT '1',
  `pollsacknowledged` tinyint(4) NOT NULL DEFAULT '1',
  `selfcontribute` tinyint(4) DEFAULT NULL,
  `contribute_percent` int(11) DEFAULT NULL,
  `statusbox` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'Matrix|Leaderboard|Alerts',
  `eid` varchar(20) DEFAULT NULL,
  `defaultgroupid` varchar(150) DEFAULT NULL,
  `cluster` varchar(500) DEFAULT '0',
  `level` varchar(50) DEFAULT NULL,
  `open_new_tab` tinyint(4) NOT NULL DEFAULT '0',
  `listtoggle` tinyint(4) NOT NULL,
  `defaultmatrixid` int(12) DEFAULT '0',
  `lastactivity` int(11) DEFAULT '0',
  `locked` tinyint(4) DEFAULT '0',
  `signature` varchar(5000) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `org_role_id` int(11) NOT NULL DEFAULT '1',
  `in_game` int(11) NOT NULL DEFAULT '0',
  `mission_link` varchar(100) NOT NULL,
  `instanceform_link` int(10) DEFAULT NULL,
  `timezone` varchar(1000) DEFAULT 'Asia/Kolkata',
  `inmail_label` varchar(10000) NOT NULL DEFAULT '2=>Comment|3=>Observer|4=>Personal',
  `avatar_date_created` datetime DEFAULT NULL,
  `doj` date DEFAULT NULL,
  `password_reset_date` date DEFAULT NULL,
  `otp` int(6) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `avatars_alerts`
--

CREATE TABLE IF NOT EXISTS `avatars_alerts` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `alertid` int(11) NOT NULL,
  `acknowledged` tinyint(4) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `avatars_app`
--

CREATE TABLE IF NOT EXISTS `avatars_app` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `type` varchar(10) DEFAULT NULL,
  `typeid` varchar(11) DEFAULT NULL,
  `moduleid` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `avatars_menus`
--

CREATE TABLE IF NOT EXISTS `avatars_menus` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `groupid` int(11) DEFAULT NULL,
  `menuid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `avatars_modules`
--

CREATE TABLE IF NOT EXISTS `avatars_modules` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `moduleid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `avatars_tiles`
--

CREATE TABLE IF NOT EXISTS `avatars_tiles` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `tileid` int(11) NOT NULL,
  `sequence` int(4) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL COMMENT 'admin: tiles assigned from admin->list avatars;group: assigned through group and manage tiles, avatar: assigned through profile prefrences'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `avatar_alert_verfication`
--

CREATE TABLE IF NOT EXISTS `avatar_alert_verfication` (
  `id` int(11) NOT NULL,
  `avatar_id` int(11) NOT NULL,
  `alert_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `avatar_api`
--

CREATE TABLE IF NOT EXISTS `avatar_api` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) DEFAULT NULL,
  `api` varchar(1000) DEFAULT NULL,
  `name` varchar(1000) DEFAULT NULL,
  `value` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `avatar_flags`
--

CREATE TABLE IF NOT EXISTS `avatar_flags` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `flag` varchar(50) NOT NULL,
  `value` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `avatar_instanceform_mapper`
--

CREATE TABLE IF NOT EXISTS `avatar_instanceform_mapper` (
  `avatarid` int(10) NOT NULL,
  `instanceformid` int(20) NOT NULL,
  `type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL,
  `cat_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `cat_icon` varchar(255) DEFAULT NULL,
  `cat_parent_id` int(11) DEFAULT NULL,
  `cat_free_flag` int(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `clearance_view`
--

CREATE TABLE IF NOT EXISTS `clearance_view` (
  `underwriter` varchar(100) DEFAULT NULL,
  `status` varchar(200) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `premium` decimal(42,2) DEFAULT NULL,
  `files` bigint(21) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `clientmap_address`
--

CREATE TABLE IF NOT EXISTS `clientmap_address` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `street` varchar(50) DEFAULT NULL,
  `city` varchar(25) DEFAULT NULL,
  `state` varchar(2) DEFAULT NULL,
  `zip` int(5) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `modifiedby` int(11) DEFAULT NULL,
  `modifieddate` date DEFAULT NULL,
  `website` varchar(100) DEFAULT '',
  `producer` varchar(50) DEFAULT 'Name',
  `policy_start_date` date DEFAULT NULL,
  `longitude` decimal(18,15) DEFAULT NULL,
  `latitude` decimal(18,15) DEFAULT NULL,
  `contactname` varchar(30) DEFAULT NULL,
  `primaryemail` varchar(300) DEFAULT NULL,
  `otherinformation` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `client_avatar`
--

CREATE TABLE IF NOT EXISTS `client_avatar` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `formid` int(11) NOT NULL,
  `clientid` int(11) NOT NULL,
  `customername` varchar(100) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `avatarname` varchar(100) NOT NULL,
  `groupid` int(11) NOT NULL,
  `groupname` varchar(100) NOT NULL,
  `managerid` int(11) NOT NULL,
  `primary` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `clubinvoice`
--

CREATE TABLE IF NOT EXISTS `clubinvoice` (
  `id` int(11) NOT NULL,
  `customername` varchar(100) DEFAULT NULL,
  `owner` varchar(100) DEFAULT NULL,
  `msa` varchar(100) DEFAULT NULL,
  `msa_instanceid` int(11) DEFAULT NULL,
  `invoicenumber` varchar(100) DEFAULT NULL,
  `address` varchar(1000) DEFAULT NULL,
  `workorder` varchar(1000) DEFAULT NULL,
  `rate` varchar(100) DEFAULT NULL,
  `transaction` varchar(100) DEFAULT NULL,
  `tsprocess` varchar(100) DEFAULT NULL,
  `currentinvoice` int(11) DEFAULT NULL,
  `dategenerated` date NOT NULL,
  `datemodified` datetime DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `currency` int(11) NOT NULL,
  `clientid` int(11) DEFAULT NULL,
  `projectid` int(11) NOT NULL,
  `pastdue` varchar(11) DEFAULT NULL,
  `totalamount` varchar(20) DEFAULT NULL,
  `vat` varchar(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `transactiontype` varchar(1000) DEFAULT NULL,
  `notetocustomers` varchar(1000) DEFAULT NULL,
  `invoicehtml` varchar(20000) DEFAULT NULL,
  `days` int(10) DEFAULT NULL,
  `servicetax` varchar(11) DEFAULT NULL,
  `summary` tinyint(1) DEFAULT '0',
  `swachhbharath` float NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `club_client_target`
--

CREATE TABLE IF NOT EXISTS `club_client_target` (
  `id` int(100) NOT NULL,
  `client_id` varchar(10) NOT NULL,
  `target` int(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `club_dashboard_menu`
--

CREATE TABLE IF NOT EXISTS `club_dashboard_menu` (
  `id` int(30) NOT NULL,
  `clientid` varchar(50) CHARACTER SET ucs2 DEFAULT NULL,
  `client_role` varchar(70) CHARACTER SET ucs2 DEFAULT NULL,
  `menu_name` varchar(80) CHARACTER SET ucs2 DEFAULT NULL,
  `link` varchar(100) CHARACTER SET ucs2 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `club_kra`
--

CREATE TABLE IF NOT EXISTS `club_kra` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `groupid` int(11) DEFAULT NULL,
  `level` varchar(100) DEFAULT NULL,
  `org_role` int(11) DEFAULT NULL,
  `avatarid` int(11) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `creatorid` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `filter` varchar(4000) DEFAULT NULL,
  `fieldset` varchar(250) DEFAULT NULL,
  `aggregate` varchar(250) DEFAULT NULL,
  `groupby` varchar(1000) DEFAULT NULL,
  `targettype` int(11) DEFAULT NULL,
  `src_table` varchar(200) DEFAULT NULL,
  `formid` int(11) DEFAULT NULL,
  `datetype` varchar(100) NOT NULL,
  `rygtype` int(11) NOT NULL,
  `srctype` varchar(200) DEFAULT NULL COMMENT 'setting it to 2 will add the goal value instead of incrementing it',
  `enddate` date DEFAULT NULL,
  `startdate` date DEFAULT NULL,
  `avatar_field` varchar(200) DEFAULT NULL,
  `goal_field` varchar(200) DEFAULT NULL,
  `default_point` double DEFAULT NULL,
  `calc_type` varchar(100) DEFAULT NULL COMMENT 'sum|times|diff|div|avg',
  `field_color` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `club_kra_sla`
--

CREATE TABLE IF NOT EXISTS `club_kra_sla` (
  `id` int(11) NOT NULL,
  `kraid` int(11) DEFAULT NULL COMMENT 'primary key of query_config table',
  `groupid` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `month` int(11) DEFAULT NULL,
  `sla_type` int(11) DEFAULT NULL,
  `redlowlimit` float DEFAULT NULL,
  `redlowworkflow` int(11) DEFAULT NULL,
  `redhighlimit` float DEFAULT NULL,
  `redhighworkflow` int(11) DEFAULT NULL,
  `yellowlowlimit` float DEFAULT NULL,
  `yellowlowworkflow` int(11) DEFAULT NULL,
  `yellowhighlimit` float DEFAULT NULL,
  `yellowhighworkflow` int(11) DEFAULT NULL,
  `greenlowlimit` float DEFAULT NULL,
  `greenlowworkflow` int(11) DEFAULT NULL,
  `greenhighlimit` float DEFAULT NULL,
  `greenhighworkflow` int(11) DEFAULT NULL,
  `goal_label_id` int(11) DEFAULT NULL,
  `triggertype` int(11) DEFAULT NULL COMMENT 'type of OR',
  `after` int(11) DEFAULT NULL COMMENT 'trigger after'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `club_matrix_admin`
--

CREATE TABLE IF NOT EXISTS `club_matrix_admin` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL DEFAULT '1',
  `name` varchar(100) NOT NULL,
  `formid` int(11) NOT NULL,
  `matrixlink` int(11) DEFAULT NULL,
  `sel_status` tinyint(1) NOT NULL DEFAULT '0',
  `sel_assigned` tinyint(1) NOT NULL DEFAULT '0',
  `srctype` int(11) DEFAULT NULL,
  `client` int(11) DEFAULT NULL,
  `rows` int(11) NOT NULL DEFAULT '1',
  `datetype` varchar(100) DEFAULT NULL,
  `defaultrange` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `club_matrix_filter`
--

CREATE TABLE IF NOT EXISTS `club_matrix_filter` (
  `id` int(11) NOT NULL,
  `matrixid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `label` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `club_matrix_graph_type`
--

CREATE TABLE IF NOT EXISTS `club_matrix_graph_type` (
  `id` int(11) NOT NULL,
  `matrixid` int(10) DEFAULT NULL,
  `matrixtypeid` int(10) DEFAULT NULL,
  `graphtype` varchar(100) DEFAULT NULL,
  `category` varchar(10000) DEFAULT NULL,
  `filtercount` int(11) DEFAULT NULL,
  `aggregate` varchar(10000) DEFAULT NULL,
  `filter` varchar(10000) DEFAULT NULL,
  `invert` int(1) DEFAULT '0',
  `percentage` int(11) DEFAULT NULL,
  `fieldset` varchar(10000) DEFAULT NULL,
  `metaformid` int(11) DEFAULT NULL,
  `instanceformid` varchar(100) DEFAULT NULL,
  `instanceformfield` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `club_matrix_types`
--

CREATE TABLE IF NOT EXISTS `club_matrix_types` (
  `id` int(11) NOT NULL,
  `matrixid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `creatorid` int(11) NOT NULL,
  `filter` varchar(200) DEFAULT NULL,
  `fieldset` varchar(250) DEFAULT NULL,
  `aggregate` varchar(250) DEFAULT NULL,
  `sub_aggregate` varchar(1000) DEFAULT NULL,
  `groupby` varchar(1000) DEFAULT NULL,
  `sortfield` varchar(100) DEFAULT NULL,
  `sortorder` varchar(100) DEFAULT NULL,
  `charttype` varchar(100) DEFAULT NULL,
  `chartrow` int(11) DEFAULT NULL,
  `chartpos` varchar(100) DEFAULT NULL,
  `legend` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `x_label` varchar(100) DEFAULT NULL,
  `y_label` varchar(100) DEFAULT NULL,
  `matrixlink` int(11) DEFAULT NULL,
  `enable_drilldown` int(5) NOT NULL DEFAULT '0',
  `drilldownvalue` varchar(100) DEFAULT NULL,
  `drilldown_fields` varchar(1000) DEFAULT NULL,
  `link` varchar(1000) DEFAULT NULL,
  `link_tooltip` varchar(1000) DEFAULT NULL,
  `url_title` varchar(100) DEFAULT NULL,
  `matrixexport` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `club_task`
--

CREATE TABLE IF NOT EXISTS `club_task` (
  `id` int(11) NOT NULL,
  `task_name` varchar(145) DEFAULT NULL,
  `avatar_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `task_duration` varchar(20) NOT NULL DEFAULT '00:00:00',
  `process` int(11) DEFAULT NULL,
  `project` int(11) DEFAULT NULL,
  `billable` int(11) DEFAULT NULL,
  `lob` int(11) DEFAULT NULL,
  `client` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `received_date` datetime DEFAULT NULL,
  `effective_date` datetime DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `tat` int(11) DEFAULT NULL,
  `days_out` int(11) DEFAULT NULL,
  `comments` varchar(1000) DEFAULT NULL,
  `error` int(11) NOT NULL DEFAULT '0',
  `file_share` varchar(100) DEFAULT NULL,
  `skip_counting` int(11) DEFAULT '0' COMMENT 'Skip counting entry in matrix',
  `field1` varchar(1000) DEFAULT NULL,
  `field2` varchar(1000) DEFAULT NULL,
  `field3` varchar(1000) DEFAULT NULL,
  `field4` varchar(1000) DEFAULT NULL,
  `field5` varchar(1000) DEFAULT NULL,
  `field6` varchar(1000) DEFAULT NULL,
  `field7` varchar(1000) DEFAULT NULL,
  `field8` varchar(1000) DEFAULT NULL,
  `field9` varchar(1000) DEFAULT NULL,
  `field10` varchar(1000) DEFAULT NULL,
  `dropdown1` varchar(100) DEFAULT NULL,
  `dropdown2` varchar(100) DEFAULT NULL,
  `dropdown3` varchar(100) DEFAULT NULL,
  `dropdown4` varchar(100) DEFAULT NULL,
  `dropdown5` varchar(100) DEFAULT NULL,
  `datefield1` datetime DEFAULT NULL,
  `datefield2` datetime DEFAULT NULL,
  `datefield3` datetime DEFAULT NULL,
  `datefield4` datetime DEFAULT NULL,
  `datefield5` datetime DEFAULT NULL,
  `cost` float(11,2) DEFAULT NULL,
  `cost_quality` int(11) DEFAULT NULL,
  `error_date` date DEFAULT NULL,
  `session` int(11) NOT NULL DEFAULT '0',
  `last_modified` datetime DEFAULT NULL,
  `instanceforms` varchar(100) DEFAULT NULL,
  `matrixid` int(11) DEFAULT NULL,
  `file_upload` varchar(1000) DEFAULT NULL,
  `file_download` varchar(1000) DEFAULT NULL,
  `points_flag` int(11) NOT NULL DEFAULT '0' COMMENT '0=>Points not awarded 1=>Points awarded'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `club_task_pause`
--

CREATE TABLE IF NOT EXISTS `club_task_pause` (
  `id` int(11) NOT NULL,
  `task_id` int(11) DEFAULT NULL,
  `avatar_id` int(11) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `pause_duration` time DEFAULT NULL,
  `comment` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `club_timesheet_comments`
--

CREATE TABLE IF NOT EXISTS `club_timesheet_comments` (
  `id` int(11) NOT NULL,
  `client` int(11) NOT NULL,
  `role` varchar(100) DEFAULT NULL,
  `comment_date` datetime NOT NULL,
  `process` int(11) NOT NULL,
  `comments` varchar(500) NOT NULL,
  `status` varchar(100) NOT NULL,
  `field1` varchar(30) DEFAULT NULL,
  `field2` varchar(30) DEFAULT NULL,
  `field3` varchar(30) DEFAULT NULL,
  `field4` varchar(30) DEFAULT NULL,
  `field5` varchar(30) DEFAULT NULL,
  `field6` varchar(30) DEFAULT NULL,
  `value1` varchar(30) DEFAULT NULL,
  `value2` varchar(30) DEFAULT NULL,
  `value3` varchar(30) DEFAULT NULL,
  `value4` varchar(30) DEFAULT NULL,
  `value5` varchar(30) DEFAULT NULL,
  `value6` varchar(30) DEFAULT NULL,
  `status1` varchar(20) DEFAULT NULL,
  `status2` varchar(20) DEFAULT NULL,
  `status3` varchar(20) DEFAULT NULL,
  `status4` varchar(20) DEFAULT NULL,
  `status5` varchar(20) DEFAULT NULL,
  `status6` varchar(20) DEFAULT NULL,
  `quality_score` varchar(100) DEFAULT NULL,
  `client_quality_score` varchar(100) DEFAULT NULL,
  `qc_per` varchar(100) DEFAULT NULL,
  `qc_comment` varchar(500) DEFAULT NULL,
  `qc_status` varchar(100) DEFAULT NULL,
  `positive_feedback` int(10) NOT NULL DEFAULT '0',
  `negative_feedback` int(10) NOT NULL DEFAULT '0',
  `utilization` int(10) DEFAULT NULL,
  `billable_count` int(10) DEFAULT NULL,
  `actual_count` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `club_timesheet_daysout`
--

CREATE TABLE IF NOT EXISTS `club_timesheet_daysout` (
  `id` int(11) NOT NULL,
  `client` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL,
  `field` varchar(100) DEFAULT NULL,
  `value` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `club_timesheet_in_out`
--

CREATE TABLE IF NOT EXISTS `club_timesheet_in_out` (
  `id` int(10) NOT NULL,
  `avatar_id` int(100) NOT NULL,
  `avatar_name` varchar(1000) NOT NULL,
  `in_time` datetime NOT NULL,
  `out_time` datetime DEFAULT NULL,
  `status` varchar(10) NOT NULL,
  `client_id` varchar(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `club_timesheet_in_out_cron`
--

CREATE TABLE IF NOT EXISTS `club_timesheet_in_out_cron` (
  `id` int(100) NOT NULL,
  `client_id` varchar(20) DEFAULT NULL,
  `avatar_id` int(100) DEFAULT NULL,
  `avatar_name` varchar(1000) DEFAULT NULL,
  `date` date NOT NULL,
  `break_duration` float DEFAULT NULL,
  `total_breaks` int(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `club_timesheet_mapper`
--

CREATE TABLE IF NOT EXISTS `club_timesheet_mapper` (
  `id` int(13) NOT NULL,
  `club_id` int(11) NOT NULL,
  `timesheet_fieldid` int(11) NOT NULL,
  `club_field` varchar(255) NOT NULL,
  `timesheet_field` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cometchat`
--

CREATE TABLE IF NOT EXISTS `cometchat` (
  `id` int(10) unsigned NOT NULL,
  `from` int(10) unsigned NOT NULL,
  `to` int(10) unsigned NOT NULL,
  `message` text NOT NULL,
  `sent` int(10) unsigned NOT NULL DEFAULT '0',
  `read` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `direction` tinyint(1) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cometchat_announcements`
--

CREATE TABLE IF NOT EXISTS `cometchat_announcements` (
  `id` int(10) unsigned NOT NULL,
  `announcement` text NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `to` int(10) NOT NULL,
  `recd` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cometchat_block`
--

CREATE TABLE IF NOT EXISTS `cometchat_block` (
  `id` int(10) unsigned NOT NULL,
  `fromid` int(10) unsigned NOT NULL,
  `toid` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cometchat_chatroommessages`
--

CREATE TABLE IF NOT EXISTS `cometchat_chatroommessages` (
  `id` int(10) unsigned NOT NULL,
  `userid` int(10) unsigned NOT NULL,
  `chatroomid` int(10) unsigned NOT NULL,
  `message` text NOT NULL,
  `sent` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cometchat_chatrooms`
--

CREATE TABLE IF NOT EXISTS `cometchat_chatrooms` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `lastactivity` int(10) unsigned NOT NULL,
  `createdby` int(10) unsigned NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `vidsession` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cometchat_chatrooms_users`
--

CREATE TABLE IF NOT EXISTS `cometchat_chatrooms_users` (
  `userid` int(10) unsigned NOT NULL,
  `chatroomid` int(10) unsigned NOT NULL,
  `lastactivity` int(10) unsigned NOT NULL,
  `isbanned` int(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cometchat_comethistory`
--

CREATE TABLE IF NOT EXISTS `cometchat_comethistory` (
  `id` int(10) unsigned NOT NULL,
  `channel` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `sent` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cometchat_games`
--

CREATE TABLE IF NOT EXISTS `cometchat_games` (
  `userid` int(10) unsigned NOT NULL,
  `score` int(10) unsigned DEFAULT NULL,
  `games` int(10) unsigned DEFAULT NULL,
  `recentlist` text,
  `highscorelist` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cometchat_guests`
--

CREATE TABLE IF NOT EXISTS `cometchat_guests` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `lastactivity` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cometchat_messages_old`
--

CREATE TABLE IF NOT EXISTS `cometchat_messages_old` (
  `id` int(10) unsigned NOT NULL,
  `from` int(10) unsigned NOT NULL,
  `to` int(10) unsigned NOT NULL,
  `message` text NOT NULL,
  `sent` int(10) unsigned NOT NULL DEFAULT '0',
  `read` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `direction` tinyint(1) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cometchat_status`
--

CREATE TABLE IF NOT EXISTS `cometchat_status` (
  `userid` int(10) unsigned NOT NULL,
  `message` text,
  `status` enum('available','away','busy','invisible','offline') DEFAULT NULL,
  `typingto` int(10) unsigned DEFAULT NULL,
  `typingtime` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cometchat_videochatsessions`
--

CREATE TABLE IF NOT EXISTS `cometchat_videochatsessions` (
  `username` varchar(255) NOT NULL,
  `identity` varchar(255) NOT NULL,
  `timestamp` int(10) unsigned DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cominvoice`
--

CREATE TABLE IF NOT EXISTS `cominvoice` (
  `id` int(150) NOT NULL,
  `company_name` varchar(150) NOT NULL,
  `va_code` varchar(150) NOT NULL,
  `contract_renewal_date` varchar(500) NOT NULL,
  `msa_number` varchar(150) NOT NULL,
  `work_order_number` varchar(150) NOT NULL,
  `type` varchar(150) NOT NULL,
  `fte` float DEFAULT NULL,
  `rate` double DEFAULT NULL,
  `customer_contact_person` varchar(150) NOT NULL,
  `title` varchar(150) NOT NULL,
  `telephone` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `address` varchar(150) NOT NULL,
  `sales_person` varchar(150) NOT NULL,
  `account_manager` varchar(150) NOT NULL,
  `contract_start_date` varchar(150) NOT NULL,
  `project_status` varchar(150) NOT NULL,
  `commission_payable` varchar(150) NOT NULL,
  `commission_percentage` varchar(150) NOT NULL,
  `work_order_detail` varchar(500) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip` varchar(100) DEFAULT NULL,
  `dashboard` varchar(150) DEFAULT NULL,
  `work_order` varchar(500) DEFAULT NULL,
  `cc` varchar(250) DEFAULT NULL,
  `process` varchar(150) DEFAULT NULL,
  `mr` varchar(150) DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL,
  `group` varchar(200) DEFAULT NULL,
  `in_no` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `comment` varchar(5000) CHARACTER SET utf8 NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `replyid` int(11) DEFAULT '0',
  `approval_status` int(11) DEFAULT '0' COMMENT '0=>pending,1=>approved,2=>rejected'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `comphotos`
--

CREATE TABLE IF NOT EXISTS `comphotos` (
  `id` int(11) NOT NULL,
  `name` varchar(25) DEFAULT NULL,
  `file` varchar(500) NOT NULL,
  `instanceformid` int(11) NOT NULL,
  `link` varchar(500) DEFAULT NULL,
  `tag` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `configurations`
--

CREATE TABLE IF NOT EXISTS `configurations` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `parameter` varchar(100) NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `countdetails`
--

CREATE TABLE IF NOT EXISTS `countdetails` (
  `avatarid` int(11) NOT NULL,
  `messagecount` int(11) NOT NULL,
  `assignedtocount` int(11) NOT NULL,
  `followupscount` int(11) NOT NULL,
  `starpointscount` int(11) NOT NULL,
  `rank` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE IF NOT EXISTS `country` (
  `id` int(11) NOT NULL,
  `iso` char(2) NOT NULL,
  `name` varchar(80) DEFAULT NULL,
  `iso3` char(3) DEFAULT NULL,
  `numcode` smallint(6) DEFAULT NULL,
  `phonecode` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `css`
--

CREATE TABLE IF NOT EXISTS `css` (
  `id` int(9) NOT NULL,
  `handle` text NOT NULL,
  `settings` longtext,
  `hover` longtext,
  `params` longtext NOT NULL,
  `advanced` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customlistfields`
--

CREATE TABLE IF NOT EXISTS `customlistfields` (
  `id` int(11) NOT NULL,
  `sequence` int(11) DEFAULT NULL,
  `customlistid` int(11) NOT NULL,
  `fieldname` varchar(50) NOT NULL,
  `fieldtext` varchar(50) DEFAULT NULL,
  `sortable` varchar(10) NOT NULL,
  `orgid` int(11) NOT NULL,
  `moduleid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customlistviews`
--

CREATE TABLE IF NOT EXISTS `customlistviews` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `orgid` int(11) NOT NULL,
  `moduleid` int(11) NOT NULL,
  `formid` int(11) NOT NULL,
  `customphtml` varchar(50) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `filter` varchar(500) DEFAULT NULL,
  `parameters` varchar(500) DEFAULT NULL,
  `groupid` int(11) NOT NULL,
  `sortby` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `custom_login`
--

CREATE TABLE IF NOT EXISTS `custom_login` (
  `id` int(11) NOT NULL,
  `url` text NOT NULL,
  `loginpage` varchar(255) DEFAULT NULL,
  `forgotpassword` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `daysout`
--

CREATE TABLE IF NOT EXISTS `daysout` (
  `Date` date NOT NULL,
  `Daysout` int(11) NOT NULL,
  `Notes` varchar(200) DEFAULT NULL,
  `Slno` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `demotracker`
--

CREATE TABLE IF NOT EXISTS `demotracker` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `companyname` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dependencies`
--

CREATE TABLE IF NOT EXISTS `dependencies` (
  `Id` int(11) unsigned NOT NULL,
  `From` int(11) DEFAULT NULL,
  `To` int(11) DEFAULT NULL,
  `Type` int(11) DEFAULT NULL,
  `Cls` varchar(255) DEFAULT NULL,
  `Lag` int(11) DEFAULT NULL,
  `LagUnit` varchar(12) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE IF NOT EXISTS `documents` (
  `id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `emailaccounts`
--

CREATE TABLE IF NOT EXISTS `emailaccounts` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `incomingserver` varchar(100) NOT NULL,
  `incomingport` int(11) NOT NULL,
  `protocol` varchar(5) NOT NULL,
  `incomingencryption` varchar(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `emailconfig`
--

CREATE TABLE IF NOT EXISTS `emailconfig` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `host` varchar(50) NOT NULL,
  `emailid` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password` varchar(50) NOT NULL,
  `assignedid` int(11) NOT NULL,
  `groupid` int(11) DEFAULT NULL,
  `moduleid` varchar(100) NOT NULL,
  `orgid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `emailheaders`
--

CREATE TABLE IF NOT EXISTS `emailheaders` (
  `id` int(11) NOT NULL,
  `messageid` int(11) NOT NULL,
  `accountid` int(11) NOT NULL,
  `receivedfrom` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `email_cache`
--

CREATE TABLE IF NOT EXISTS `email_cache` (
  `id` int(11) unsigned NOT NULL,
  `userid` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `folder` varchar(100) NOT NULL,
  `uid` int(11) NOT NULL,
  `cc` varchar(1000) DEFAULT NULL,
  `_from` varchar(255) DEFAULT NULL,
  `_subject` varchar(500) DEFAULT NULL,
  `_to` varchar(1000) DEFAULT NULL,
  `envelope` text NOT NULL,
  `unseen` tinyint(1) NOT NULL DEFAULT '0',
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `email_setting`
--

CREATE TABLE IF NOT EXISTS `email_setting` (
  `id` int(11) unsigned NOT NULL,
  `userid` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `host` varchar(255) NOT NULL,
  `port` varchar(45) NOT NULL,
  `secure` varchar(45) NOT NULL,
  `folders` text,
  `last_sync_time` datetime DEFAULT NULL,
  `last_sync_duration` int(11) NOT NULL DEFAULT '0',
  `last_sync_status` text,
  `smtp_host` varchar(255) NOT NULL,
  `smtp_port` varchar(45) NOT NULL,
  `smtp_username` varchar(100) NOT NULL,
  `smtp_password` varchar(255) NOT NULL,
  `smtp_secure` varchar(45) NOT NULL,
  `month_since` int(11) NOT NULL DEFAULT '0',
  `oauth_provider` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE IF NOT EXISTS `email_templates` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employeeformseq`
--

CREATE TABLE IF NOT EXISTS `employeeformseq` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `sequenceid` int(11) NOT NULL,
  `nextsequenceid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE IF NOT EXISTS `employees` (
  `id` int(11) unsigned NOT NULL,
  `avatarid` int(11) NOT NULL,
  `step` int(3) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `addedtime` datetime DEFAULT NULL,
  `modifiedtime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `errorlog`
--

CREATE TABLE IF NOT EXISTS `errorlog` (
  `policynumber` varchar(30) NOT NULL,
  `branch` varchar(200) DEFAULT NULL,
  `Issuedfor` varchar(30) DEFAULT NULL,
  `Issuedby` varchar(30) DEFAULT NULL,
  `LOB` varchar(30) DEFAULT NULL,
  `FQC` varchar(30) DEFAULT NULL,
  `uwsrater` varchar(20) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `ErrorFixedOn` date DEFAULT NULL,
  `Error` varchar(10000) DEFAULT NULL,
  `UWSaction` varchar(2000) DEFAULT NULL,
  `replysent` varchar(10000) DEFAULT NULL,
  `Client_ID` varchar(45) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `escalations`
--

CREATE TABLE IF NOT EXISTS `escalations` (
  `id` int(11) NOT NULL,
  `instanceformid` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `level` varchar(5) DEFAULT NULL,
  `primary` varchar(5) DEFAULT NULL,
  `orgid` int(11) DEFAULT NULL,
  `creatorid` int(11) DEFAULT NULL,
  `moduleid` int(10) DEFAULT NULL,
  `formid` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `evolve_avatarformseq`
--

CREATE TABLE IF NOT EXISTS `evolve_avatarformseq` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `formid` int(11) NOT NULL,
  `index` int(11) DEFAULT '0',
  `nextid` int(11) DEFAULT NULL,
  `previd` int(11) DEFAULT NULL,
  `positionflag` int(11) DEFAULT NULL,
  `visible` int(11) DEFAULT NULL,
  `count` int(11) DEFAULT '0',
  `instanceformid` int(11) DEFAULT NULL,
  `wizardid` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `evolve_avatars`
--

CREATE TABLE IF NOT EXISTS `evolve_avatars` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `avatarformid` int(11) DEFAULT NULL,
  `ssn` int(11) DEFAULT NULL,
  `premium` decimal(10,0) DEFAULT NULL,
  `completedatetime` datetime DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `locationid` int(11) DEFAULT NULL,
  `createddate` datetime NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `wizardid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `evolve_declined`
--

CREATE TABLE IF NOT EXISTS `evolve_declined` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `value` varchar(100) DEFAULT NULL,
  `wizardid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `evolve_export`
--

CREATE TABLE IF NOT EXISTS `evolve_export` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `orgid` int(11) NOT NULL,
  `wizardid` int(12) DEFAULT NULL,
  `controllername` varchar(100) NOT NULL,
  `actionname` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `evolve_exportfields`
--

CREATE TABLE IF NOT EXISTS `evolve_exportfields` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `evolveexportid` int(11) NOT NULL,
  `evolveformid` int(11) NOT NULL,
  `evolvefieldid` int(11) NOT NULL,
  `condition` varchar(100) DEFAULT NULL,
  `expression` varchar(100) DEFAULT NULL,
  `evolvefieldname` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `evolve_instanceseq`
--

CREATE TABLE IF NOT EXISTS `evolve_instanceseq` (
  `id` int(11) NOT NULL,
  `instanceformid` int(11) DEFAULT NULL,
  `avatarformseqid` int(11) NOT NULL,
  `wizardid` int(11) DEFAULT NULL,
  `avatarid` int(11) DEFAULT NULL,
  `firstformseqid` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `ssn` varchar(20) DEFAULT NULL,
  `premium` decimal(10,2) DEFAULT NULL,
  `employercost` decimal(10,2) NOT NULL,
  `completedatetime` datetime DEFAULT NULL,
  `language` varchar(20) DEFAULT NULL,
  `locationid` int(11) DEFAULT NULL,
  `terminationdate` varchar(50) DEFAULT NULL,
  `currentstatusofavatar` varchar(4) NOT NULL DEFAULT 'A',
  `changeeffectivedate` varchar(50) DEFAULT NULL,
  `lifechangeflag` tinyint(4) NOT NULL DEFAULT '0',
  `createddate` datetime NOT NULL,
  `effectivedate` varchar(50) DEFAULT NULL,
  `exportstatus` tinyint(4) NOT NULL DEFAULT '1',
  `exportstatusdate` varchar(50) NOT NULL,
  `assementscore` int(11) NOT NULL DEFAULT '0',
  `enrollment_startdate` varchar(20) DEFAULT NULL,
  `enrollment_enddate` varchar(20) DEFAULT NULL,
  `rewindflag` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `evolve_instanceseq_history`
--

CREATE TABLE IF NOT EXISTS `evolve_instanceseq_history` (
  `id` int(11) NOT NULL,
  `old_id` int(11) DEFAULT NULL,
  `instanceformid` int(11) DEFAULT NULL,
  `avatarformseqid` int(11) NOT NULL,
  `wizardid` int(11) DEFAULT NULL,
  `avatarid` int(11) DEFAULT NULL,
  `firstformseqid` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `ssn` varchar(20) DEFAULT NULL,
  `premium` decimal(10,2) DEFAULT NULL,
  `employercost` decimal(10,2) NOT NULL,
  `completedatetime` datetime DEFAULT NULL,
  `language` varchar(20) DEFAULT NULL,
  `locationid` int(11) DEFAULT NULL,
  `terminationdate` varchar(50) DEFAULT NULL,
  `currentstatusofavatar` varchar(4) NOT NULL DEFAULT 'A',
  `changeeffectivedate` varchar(50) DEFAULT NULL,
  `lifechangeflag` tinyint(4) NOT NULL DEFAULT '0',
  `createddate` datetime NOT NULL,
  `effectivedate` varchar(50) DEFAULT NULL,
  `exportstatus` tinyint(4) NOT NULL DEFAULT '1',
  `assementscore` int(4) NOT NULL DEFAULT '0',
  `exportstatusdate` varchar(50) NOT NULL,
  `enrollment_startdate` varchar(20) DEFAULT NULL,
  `enrollment_enddate` varchar(20) DEFAULT NULL,
  `rewindflag` tinyint(4) NOT NULL DEFAULT '0',
  `history_datetime` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `evolve_metafields`
--

CREATE TABLE IF NOT EXISTS `evolve_metafields` (
  `id` int(11) NOT NULL,
  `sequence` int(11) NOT NULL,
  `evolveformid` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `text` varchar(10000) CHARACTER SET ucs2 NOT NULL,
  `helpertext` varchar(150) DEFAULT NULL,
  `type` varchar(30) CHARACTER SET ucs2 DEFAULT NULL,
  `instancefield` varchar(100) DEFAULT NULL,
  `other_inst` varchar(250) DEFAULT NULL,
  `other_instfield` varchar(250) DEFAULT NULL,
  `options` varchar(10000) CHARACTER SET ucs2 DEFAULT NULL,
  `color` varchar(1000) DEFAULT NULL,
  `dependson` varchar(50) CHARACTER SET ucs2 DEFAULT NULL,
  `disablejavascript` tinyint(4) DEFAULT '0',
  `required` tinyint(1) DEFAULT '0',
  `regexpvalidator` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `validationtext` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `specialvalidator` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `readonly` tinyint(1) DEFAULT '0',
  `expression` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `canbehidden` tinyint(4) NOT NULL DEFAULT '0',
  `dateordependson` varchar(30) DEFAULT NULL,
  `onlyownrcanchng` tinyint(1) DEFAULT '0',
  `condition` varchar(500) DEFAULT NULL,
  `premiumname` varchar(100) DEFAULT NULL,
  `textspanish` varchar(1000) DEFAULT NULL,
  `dontshowpremiumbutton` int(4) DEFAULT NULL,
  `scoreablequestion` tinyint(1) DEFAULT '0',
  `answer` varchar(200) DEFAULT NULL,
  `weightage` int(4) DEFAULT NULL,
  `questiontype` varchar(40) DEFAULT NULL,
  `dependentrequired` tinyint(1) NOT NULL DEFAULT '0',
  `encrypted` tinyint(1) NOT NULL DEFAULT '0',
  `multiplefield` tinyint(4) DEFAULT '0',
  `tableproperty` varchar(200) DEFAULT NULL,
  `classname` varchar(100) DEFAULT NULL,
  `dontshowinfirstform` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `evolve_metaforms`
--

CREATE TABLE IF NOT EXISTS `evolve_metaforms` (
  `id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `htmltext` longtext,
  `modulename` varchar(30) DEFAULT NULL,
  `video` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `videotype` varchar(20) DEFAULT NULL,
  `multiple` int(11) DEFAULT NULL,
  `dynamic` int(11) DEFAULT NULL,
  `customform` varchar(50) DEFAULT NULL,
  `condition` varchar(250) DEFAULT NULL,
  `customcontroller` varchar(250) DEFAULT NULL,
  `customaction` varchar(30) DEFAULT NULL,
  `validation` varchar(300) DEFAULT NULL,
  `validationmessage` varchar(300) DEFAULT NULL,
  `formid` int(11) NOT NULL,
  `wizard_id` int(11) NOT NULL,
  `workflowid` int(11) DEFAULT NULL,
  `orgid` int(11) NOT NULL,
  `classvideos` varchar(100) NOT NULL,
  `hookfunction` varchar(100) DEFAULT NULL,
  `pauseduration` tinyint(1) NOT NULL DEFAULT '0',
  `multiplecondition` varchar(100) DEFAULT NULL,
  `calculatepremium` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `evolve_organizations`
--

CREATE TABLE IF NOT EXISTS `evolve_organizations` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `videofolder` varchar(100) DEFAULT NULL,
  `pdfenable` tinyint(1) NOT NULL DEFAULT '0',
  `malepercentage` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `evolve_premiumcalculations`
--

CREATE TABLE IF NOT EXISTS `evolve_premiumcalculations` (
  `id` int(11) NOT NULL,
  `sequence` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `text` varchar(100) DEFAULT NULL,
  `dependentfield` varchar(100) DEFAULT NULL,
  `expression` varchar(100) DEFAULT NULL,
  `condition` varchar(500) DEFAULT NULL,
  `showcondition` varchar(200) DEFAULT NULL,
  `customclass` varchar(100) DEFAULT NULL,
  `wizardid` int(11) DEFAULT NULL,
  `otherajaxfields` varchar(100) DEFAULT NULL,
  `hidecoverage` tinyint(4) NOT NULL DEFAULT '0',
  `brochurename` varchar(500) DEFAULT NULL,
  `brochurelink` varchar(500) DEFAULT NULL,
  `conditiontohidincompletepage` varchar(500) DEFAULT NULL,
  `multipleform` tinyint(4) NOT NULL DEFAULT '0',
  `multipleformfieldname` varchar(200) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `evolve_premiumlookup`
--

CREATE TABLE IF NOT EXISTS `evolve_premiumlookup` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `minage` int(11) DEFAULT NULL,
  `maxage` int(11) DEFAULT NULL,
  `smoking` varchar(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `custom1` varchar(100) DEFAULT NULL,
  `custom2` varchar(100) DEFAULT NULL,
  `custom3` varchar(100) DEFAULT NULL,
  `value` varchar(100) DEFAULT NULL,
  `coverage` int(11) DEFAULT NULL,
  `totalcost` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `evolve_question_values`
--

CREATE TABLE IF NOT EXISTS `evolve_question_values` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `wizardid` int(11) NOT NULL,
  `instanceformid` int(11) DEFAULT NULL,
  `avatarid` int(11) NOT NULL,
  `empformid` int(11) DEFAULT NULL,
  `formid` int(11) NOT NULL,
  `formname` varchar(30) DEFAULT NULL,
  `questionname` varchar(30) DEFAULT NULL,
  `questionid` int(11) NOT NULL,
  `value` text CHARACTER SET utf8 NOT NULL,
  `score` tinyint(4) DEFAULT NULL,
  `encrypted` tinyint(1) NOT NULL DEFAULT '0',
  `oldvalue` text,
  `modified_avatarid` int(11) NOT NULL,
  `modified_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `evolve_question_values_history`
--

CREATE TABLE IF NOT EXISTS `evolve_question_values_history` (
  `id` int(11) NOT NULL,
  `old_id` int(11) DEFAULT NULL,
  `orgid` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `empformid` int(11) DEFAULT NULL,
  `formid` int(11) NOT NULL,
  `formname` varchar(30) DEFAULT NULL,
  `questionname` varchar(30) DEFAULT NULL,
  `questionid` int(11) NOT NULL,
  `value` varchar(100) NOT NULL,
  `wizardid` int(11) DEFAULT NULL,
  `instanceformid` int(11) DEFAULT NULL,
  `score` tinyint(4) DEFAULT NULL,
  `attempt` tinyint(4) DEFAULT NULL,
  `history_datetime` varchar(20) NOT NULL,
  `oldvalue` text,
  `modified_avatarid` int(11) NOT NULL,
  `modified_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `evolve_signatures`
--

CREATE TABLE IF NOT EXISTS `evolve_signatures` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `signed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `evolve_wizards`
--

CREATE TABLE IF NOT EXISTS `evolve_wizards` (
  `id` int(12) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `showpremium` tinyint(1) NOT NULL DEFAULT '0',
  `orgid` int(11) NOT NULL,
  `metaformid` int(11) NOT NULL,
  `customreview` varchar(100) DEFAULT NULL,
  `customcomplete` varchar(100) DEFAULT NULL,
  `disablevideo` tinyint(1) NOT NULL DEFAULT '0',
  `disableoptionsinvideo` tinyint(1) NOT NULL DEFAULT '0',
  `hideprevious` tinyint(4) NOT NULL DEFAULT '0',
  `hidenavigation` tinyint(4) NOT NULL DEFAULT '0',
  `allowforexport` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `createid` int(11) NOT NULL,
  `startdate` date NOT NULL,
  `duedate` datetime NOT NULL,
  `onlineormanual` tinyint(4) NOT NULL,
  `retake` tinyint(4) NOT NULL DEFAULT '0',
  `duration` time NOT NULL,
  `parent` int(11) NOT NULL,
  `passingper` int(11) DEFAULT NULL,
  `rules` longtext,
  `disableeditfromreview` tinyint(4) NOT NULL DEFAULT '0',
  `headingtitle` varchar(200) DEFAULT 'Total Paycheck Contribution',
  `uniquecount` int(11) DEFAULT NULL,
  `autogeneratedno` int(11) NOT NULL,
  `pauseduration` tinyint(1) NOT NULL DEFAULT '0',
  `retaketrainingperiod` tinyint(4) NOT NULL DEFAULT '0',
  `themecolor` varchar(11) DEFAULT NULL,
  `workflowid` int(11) DEFAULT NULL,
  `email_status` tinyint(4) NOT NULL DEFAULT '0',
  `email_from` varchar(250) DEFAULT NULL,
  `email_to` varchar(500) DEFAULT NULL,
  `email_temp_id` int(11) DEFAULT NULL,
  `w1` varchar(1000) DEFAULT NULL,
  `w2` varchar(1000) DEFAULT NULL,
  `w3` varchar(1000) DEFAULT NULL,
  `w4` varchar(1000) DEFAULT NULL,
  `w5` varchar(1000) DEFAULT NULL,
  `w6` varchar(1000) DEFAULT NULL,
  `w7` varchar(1000) DEFAULT NULL,
  `w8` varchar(1000) DEFAULT NULL,
  `w9` varchar(1000) DEFAULT NULL,
  `w10` varchar(1000) DEFAULT NULL,
  `w11` varchar(1000) DEFAULT NULL,
  `w12` varchar(1000) DEFAULT NULL,
  `wbig1` varchar(10000) DEFAULT NULL,
  `wbig2` varchar(10000) DEFAULT NULL,
  `showrewindbutton` tinyint(4) DEFAULT NULL,
  `wizard_access` tinyint(4) DEFAULT NULL,
  `remark1` varchar(50) DEFAULT NULL,
  `remark1lowerlimit` int(11) DEFAULT NULL,
  `remark1upperlimit` int(11) DEFAULT NULL,
  `remark2` varchar(50) DEFAULT NULL,
  `remark2lowerlimit` int(11) DEFAULT NULL,
  `remark2upperlimit` int(11) DEFAULT NULL,
  `remark3` varchar(50) DEFAULT NULL,
  `remark3lowerlimit` int(11) DEFAULT NULL,
  `remark3upperlimit` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `executive_desktop`
--

CREATE TABLE IF NOT EXISTS `executive_desktop` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `avatarid` int(11) DEFAULT NULL,
  `instanceform` int(11) DEFAULT NULL,
  `target` varchar(200) DEFAULT NULL,
  `acheived` varchar(200) DEFAULT NULL,
  `status` varchar(200) DEFAULT NULL,
  `color` varchar(200) DEFAULT NULL,
  `parentid` int(11) NOT NULL,
  `contract_link` varchar(255) DEFAULT NULL,
  `contract_id` int(11) DEFAULT NULL,
  `matrix_link` varchar(255) DEFAULT NULL,
  `matrix_id` int(11) DEFAULT NULL,
  `type` tinyint(4) NOT NULL,
  `orgid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

CREATE TABLE IF NOT EXISTS `expense` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `employeeid` varchar(50) DEFAULT NULL,
  `approvedby` varchar(50) DEFAULT NULL,
  `date` varchar(20) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `moneytype` varchar(20) NOT NULL,
  `cost` double DEFAULT NULL,
  `avatarid` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `createdid` int(11) DEFAULT NULL,
  `attachment` varchar(100) DEFAULT NULL,
  `orgid` int(11) NOT NULL,
  `instanceformid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `export_fields`
--

CREATE TABLE IF NOT EXISTS `export_fields` (
  `id` int(11) NOT NULL,
  `formid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `fields` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `facilities_equipments`
--

CREATE TABLE IF NOT EXISTS `facilities_equipments` (
  `id` int(11) NOT NULL,
  `type` mediumtext NOT NULL,
  `test_name` mediumtext NOT NULL,
  `astm` mediumtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fields`
--

CREATE TABLE IF NOT EXISTS `fields` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `text` varchar(400) NOT NULL,
  `columnname` varchar(1000) DEFAULT NULL,
  `helpertext` varchar(150) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `options` varchar(10000) DEFAULT NULL,
  `color` varchar(1000) DEFAULT NULL,
  `regexpvalidator` varchar(100) DEFAULT NULL,
  `validationtext` varchar(250) DEFAULT NULL,
  `specialvalidator` varchar(50) DEFAULT NULL,
  `expression` varchar(1000) DEFAULT NULL,
  `condition` varchar(250) DEFAULT NULL,
  `premiumname` varchar(50) DEFAULT NULL,
  `xflat_parameter` int(2) NOT NULL DEFAULT '0',
  `esign_parameter` int(11) NOT NULL DEFAULT '0' COMMENT 'this field will be used in esign api',
  `field_type` varchar(100) NOT NULL DEFAULT 'config',
  `category` varchar(1000) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fieldstodeleteinmultipleform`
--

CREATE TABLE IF NOT EXISTS `fieldstodeleteinmultipleform` (
  `id` int(11) NOT NULL,
  `formid` int(11) NOT NULL,
  `questionid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `formcomments`
--

CREATE TABLE IF NOT EXISTS `formcomments` (
  `id` int(11) NOT NULL,
  `instanceformid` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `comment` varchar(5000) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `replyid` int(11) NOT NULL DEFAULT '0',
  `observers` varchar(1000) DEFAULT NULL,
  `moduleid` int(11) NOT NULL,
  `approval_status` int(11) DEFAULT '0' COMMENT '0=>pending,1=>approved,2=>rejected',
  `nextactiondate` varchar(30) DEFAULT NULL,
  `email_ids` varchar(1000) DEFAULT NULL,
  `status` int(3) DEFAULT NULL,
  `ownerid` int(11) DEFAULT NULL,
  `assignedto` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `form_menu`
--

CREATE TABLE IF NOT EXISTS `form_menu` (
  `id` int(11) NOT NULL,
  `formid` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `workflowid` int(11) DEFAULT NULL,
  `customaction` varchar(200) DEFAULT NULL,
  `icon` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `friendid` int(11) NOT NULL,
  `relation_direction` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `game_points`
--

CREATE TABLE IF NOT EXISTS `game_points` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `parentinstanceformid` int(11) NOT NULL,
  `childinstanceformid` int(11) DEFAULT NULL,
  `score` int(11) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `orgid` int(11) NOT NULL,
  `game_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gmail`
--

CREATE TABLE IF NOT EXISTS `gmail` (
  `avatarid` int(11) NOT NULL,
  `id` varchar(32) NOT NULL,
  `subject` varchar(250) NOT NULL,
  `date` varchar(32) NOT NULL,
  `sender` varchar(250) NOT NULL,
  `status` int(2) NOT NULL,
  `attachments_flag` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `goal_label`
--

CREATE TABLE IF NOT EXISTS `goal_label` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  `org_role_id` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `goal_id` varchar(10) NOT NULL,
  `goal_label` varchar(100) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `kra_id` int(11) DEFAULT NULL,
  `orgid` int(11) NOT NULL,
  `CALC_TYPE` varchar(10) DEFAULT 'sum',
  `REVERSE_FLAG` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `golfmap_avatarcourses`
--

CREATE TABLE IF NOT EXISTS `golfmap_avatarcourses` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `courseid` int(11) NOT NULL,
  `score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `golfmap_courses`
--

CREATE TABLE IF NOT EXISTS `golfmap_courses` (
  `id` int(5) NOT NULL,
  `biz_name` varchar(117) DEFAULT NULL,
  `e_address` varchar(83) DEFAULT NULL,
  `e_city` varchar(38) DEFAULT NULL,
  `e_state` varchar(22) DEFAULT NULL,
  `e_postal` varchar(7) DEFAULT NULL,
  `e_zip_full` varchar(10) DEFAULT NULL,
  `e_country` varchar(14) DEFAULT NULL,
  `loc_county` varchar(20) DEFAULT NULL,
  `loc_area_code` varchar(3) DEFAULT NULL,
  `loc_FIPS` varchar(5) DEFAULT NULL,
  `loc_MSA` varchar(4) DEFAULT NULL,
  `loc_PMSA` varchar(4) DEFAULT NULL,
  `loc_TZ` varchar(5) DEFAULT NULL,
  `loc_DST` varchar(1) DEFAULT NULL,
  `loc_LAT_centroid` varchar(6) DEFAULT NULL,
  `loc_LAT_poly` varchar(9) DEFAULT NULL,
  `loc_LONG_centroid` varchar(8) DEFAULT NULL,
  `loc_LONG_poly` varchar(11) DEFAULT NULL,
  `biz_phone` varchar(14) DEFAULT NULL,
  `metal_spikes` varchar(1) DEFAULT NULL,
  `play_five` varchar(1) DEFAULT NULL,
  `c_holes` varchar(4) DEFAULT NULL,
  `c_type` varchar(12) DEFAULT NULL,
  `year_built` varchar(4) DEFAULT NULL,
  `c_designer` varchar(57) DEFAULT NULL,
  `c_season` varchar(52) DEFAULT NULL,
  `guest_policy` varchar(80) DEFAULT NULL,
  `dress_code` varchar(76) DEFAULT NULL,
  `green_fees` varchar(37) DEFAULT NULL,
  `weekend_rates` varchar(12) DEFAULT NULL,
  `adv_tee` varchar(8) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `googlelogin`
--

CREATE TABLE IF NOT EXISTS `googlelogin` (
  `avatarid` int(11) NOT NULL,
  `emailid` varchar(64) NOT NULL,
  `name` varchar(64) NOT NULL,
  `refreshtoken` varchar(128) NOT NULL,
  `accesstoken` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `google_gcm`
--

CREATE TABLE IF NOT EXISTS `google_gcm` (
  `avatarid` int(11) NOT NULL,
  `gcm_registration_id` varchar(512) NOT NULL,
  `device` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

CREATE TABLE IF NOT EXISTS `grade` (
  `id` int(13) NOT NULL,
  `name` varchar(50) NOT NULL,
  `orgid` int(11) NOT NULL,
  `moduleid` varchar(255) DEFAULT '',
  `cancreate` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(400) CHARACTER SET utf8 NOT NULL,
  `parentid` int(11) DEFAULT NULL,
  `orgid` int(11) NOT NULL,
  `managerid` int(11) DEFAULT NULL,
  `moduleid` int(11) DEFAULT NULL,
  `disablechat` tinyint(4) DEFAULT NULL,
  `assigntomanager` tinyint(4) NOT NULL DEFAULT '0',
  `description` mediumtext,
  `logo` varchar(20) DEFAULT NULL,
  `coverphoto` varchar(111) DEFAULT NULL,
  `power_users` tinyint(4) NOT NULL DEFAULT '0',
  `type` tinyint(4) DEFAULT '0',
  `hiddentopicons` tinyint(1) NOT NULL,
  `hidetiles` tinyint(1) NOT NULL,
  `hidewall` tinyint(1) NOT NULL,
  `hideannouncement` tinyint(1) NOT NULL,
  `hideleaderboard` tinyint(1) NOT NULL,
  `hiddenmessage` tinyint(1) DEFAULT NULL,
  `hiddenassignment` tinyint(1) DEFAULT NULL,
  `hiddenfollowup` tinyint(1) DEFAULT NULL,
  `hiddencreate` tinyint(1) DEFAULT NULL,
  `hiddensearch` tinyint(1) DEFAULT NULL,
  `hiddengroup` tinyint(1) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `groups_alerts`
--

CREATE TABLE IF NOT EXISTS `groups_alerts` (
  `id` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `alertid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `groups_avatars`
--

CREATE TABLE IF NOT EXISTS `groups_avatars` (
  `id` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `groups_brokers`
--

CREATE TABLE IF NOT EXISTS `groups_brokers` (
  `id` int(11) NOT NULL,
  `groupid` int(11) NOT NULL COMMENT 'active_brokers',
  `brokerid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `groups_managers`
--

CREATE TABLE IF NOT EXISTS `groups_managers` (
  `id` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `managerid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `groups_modules`
--

CREATE TABLE IF NOT EXISTS `groups_modules` (
  `id` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `moduleid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `groups_tiles`
--

CREATE TABLE IF NOT EXISTS `groups_tiles` (
  `id` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `tileid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `group_timesheet_clients`
--

CREATE TABLE IF NOT EXISTS `group_timesheet_clients` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `help_tour`
--

CREATE TABLE IF NOT EXISTS `help_tour` (
  `id` int(11) NOT NULL,
  `sequence` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `element` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `content` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `placement` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ikra`
--

CREATE TABLE IF NOT EXISTS `ikra` (
  `avatarid` int(11) NOT NULL,
  `month` varchar(45) DEFAULT NULL,
  `year` int(20) DEFAULT NULL,
  `ikradate` date DEFAULT NULL,
  `k1` varchar(45) DEFAULT NULL,
  `k2` varchar(45) DEFAULT NULL,
  `k3` varchar(45) DEFAULT NULL,
  `k4` varchar(45) DEFAULT NULL,
  `k5` varchar(45) DEFAULT NULL,
  `average` varchar(45) DEFAULT NULL,
  `starpoints` varchar(45) DEFAULT NULL,
  `weightage` float(11,2) DEFAULT NULL,
  `quality` float(11,2) NOT NULL,
  `ikraid` int(11) NOT NULL,
  `k6` varchar(45) DEFAULT NULL,
  `k7` varchar(45) DEFAULT NULL,
  `k8` varchar(45) DEFAULT NULL,
  `k9` varchar(45) DEFAULT NULL,
  `k10` varchar(45) DEFAULT NULL,
  `comments` varchar(500) DEFAULT NULL,
  `sentinalupdate` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ikradaily`
--

CREATE TABLE IF NOT EXISTS `ikradaily` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `month` varchar(20) DEFAULT NULL,
  `ikradate` datetime DEFAULT NULL,
  `average` varchar(45) DEFAULT NULL,
  `starpoints` varchar(45) DEFAULT NULL,
  `weightage` float(11,2) DEFAULT '0.00',
  `quality` float(11,2) DEFAULT '0.00',
  `k1` varchar(45) DEFAULT NULL,
  `k2` varchar(45) DEFAULT NULL,
  `k3` varchar(45) DEFAULT NULL,
  `k4` varchar(45) DEFAULT NULL,
  `k5` varchar(45) DEFAULT NULL,
  `k6` varchar(45) DEFAULT NULL,
  `k7` varchar(45) DEFAULT NULL,
  `k8` varchar(45) DEFAULT NULL,
  `k9` varchar(45) DEFAULT NULL,
  `k10` varchar(45) DEFAULT NULL,
  `comments` varchar(500) DEFAULT NULL,
  `sentinalupdate` int(10) NOT NULL DEFAULT '0',
  `mygoal` float(11,2) DEFAULT '0.00',
  `teamgoal` float(11,2) DEFAULT '0.00',
  `update_log` varchar(10000) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL,
  `url` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `image_tags`
--

CREATE TABLE IF NOT EXISTS `image_tags` (
  `id` int(12) NOT NULL,
  `imgid` int(12) NOT NULL,
  `tag_name` varchar(255) NOT NULL,
  `instanceformid` int(12) NOT NULL,
  `pos_x` varchar(255) DEFAULT NULL,
  `pos_y` varchar(255) DEFAULT NULL,
  `pos_width` varchar(255) DEFAULT NULL,
  `pos_height` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `import_logs`
--

CREATE TABLE IF NOT EXISTS `import_logs` (
  `id` int(11) NOT NULL,
  `title` varchar(500) DEFAULT NULL,
  `description` tinytext,
  `mime_type` varchar(500) DEFAULT NULL,
  `filesize` varchar(50) DEFAULT NULL,
  `filename` varchar(500) NOT NULL,
  `original_filename` varchar(500) NOT NULL,
  `errors` text,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL COMMENT '1=success, 9=failed'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instancefieldbigtext`
--

CREATE TABLE IF NOT EXISTS `instancefieldbigtext` (
  `id` int(11) NOT NULL,
  `field` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instancefields`
--

CREATE TABLE IF NOT EXISTS `instancefields` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `instanceformid` int(11) NOT NULL,
  `fieldid` int(11) NOT NULL,
  `fieldname` varchar(200) DEFAULT NULL,
  `value` varchar(5000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `instanceforms`
--

CREATE TABLE IF NOT EXISTS `instanceforms` (
  `id` int(11) NOT NULL,
  `assessid` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` mediumtext,
  `htmltext` varchar(1000) DEFAULT NULL,
  `leaf` int(11) DEFAULT NULL,
  `color` int(11) DEFAULT NULL,
  `durationunit` int(11) DEFAULT NULL,
  `percentdone` int(11) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `orgid` int(11) NOT NULL,
  `formid` int(11) NOT NULL,
  `createdid` int(11) DEFAULT NULL,
  `original_createdid` int(11) NOT NULL,
  `modifiedid` int(11) DEFAULT NULL,
  `assignedto` int(11) DEFAULT NULL,
  `assignedgroup` int(11) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `ownergroupid` int(11) DEFAULT NULL,
  `parentinstformid` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `duplicate` varchar(20) DEFAULT NULL,
  `startdate` datetime DEFAULT NULL,
  `nextactiondate` datetime DEFAULT NULL,
  `emailaddress1` varchar(123) DEFAULT NULL,
  `enddate` datetime DEFAULT NULL,
  `cost` float DEFAULT NULL,
  `starpoints` int(11) DEFAULT NULL,
  `testerid` int(11) DEFAULT NULL,
  `testercode` varchar(10) DEFAULT NULL,
  `field3` int(10) DEFAULT NULL,
  `tags` varchar(250) DEFAULT NULL,
  `category` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `goals` varchar(200) DEFAULT NULL,
  `kracategory` int(11) DEFAULT NULL,
  `krasubcategory` int(11) DEFAULT NULL,
  `observer` varchar(2000) DEFAULT NULL,
  `location` int(11) NOT NULL,
  `pod` tinyint(1) NOT NULL DEFAULT '0',
  `observeravatardel` varchar(2000) DEFAULT NULL,
  `observergroupdel` varchar(2000) DEFAULT NULL,
  `comment_moderator` tinyint(4) DEFAULT '1',
  `reffield1` varchar(2000) DEFAULT NULL,
  `reffield2` varchar(2000) DEFAULT NULL,
  `reffield3` varchar(2000) DEFAULT NULL,
  `reffield4` varchar(2000) DEFAULT NULL,
  `reffield5` varchar(2000) DEFAULT NULL,
  `f1` varchar(500) DEFAULT NULL,
  `f2` varchar(500) DEFAULT NULL,
  `f3` varchar(500) DEFAULT NULL,
  `f4` varchar(500) DEFAULT NULL,
  `f5` varchar(500) DEFAULT NULL,
  `f6` varchar(500) DEFAULT NULL,
  `f7` varchar(500) DEFAULT NULL,
  `f8` varchar(500) DEFAULT NULL,
  `f9` varchar(500) DEFAULT NULL,
  `f10` varchar(500) DEFAULT NULL,
  `f11` varchar(500) DEFAULT NULL,
  `f12` varchar(500) DEFAULT NULL,
  `f13` varchar(500) DEFAULT NULL,
  `f14` varchar(500) DEFAULT NULL,
  `f15` varchar(500) DEFAULT NULL,
  `f16` varchar(500) DEFAULT NULL,
  `f17` varchar(500) DEFAULT NULL,
  `f18` varchar(500) DEFAULT NULL,
  `f19` varchar(500) DEFAULT NULL,
  `f20` varchar(500) DEFAULT NULL,
  `f21` varchar(500) DEFAULT NULL,
  `f22` varchar(500) DEFAULT NULL,
  `f23` varchar(500) DEFAULT NULL,
  `f24` varchar(500) DEFAULT NULL,
  `f25` varchar(500) DEFAULT NULL,
  `f26` varchar(500) DEFAULT NULL,
  `f27` varchar(500) DEFAULT NULL,
  `f28` varchar(500) DEFAULT NULL,
  `f29` varchar(500) DEFAULT NULL,
  `f30` varchar(500) DEFAULT NULL,
  `fbig1` mediumtext,
  `fbig2` mediumtext,
  `fbig3` mediumtext,
  `fbig4` mediumtext,
  `fbig5` mediumtext,
  `fbig6` mediumtext,
  `fbig7` mediumtext,
  `fbig8` mediumtext,
  `fbig9` mediumtext,
  `fbig10` mediumtext,
  `fbig11` mediumtext,
  `fbig12` mediumtext,
  `fbig13` mediumtext,
  `fbig14` mediumtext,
  `fbig15` mediumtext,
  `fbig16` mediumtext,
  `fbig17` mediumtext,
  `fbig18` mediumtext,
  `fbig19` mediumtext,
  `fbig20` mediumtext,
  `locked` int(10) DEFAULT '0',
  `points_flag` int(11) NOT NULL DEFAULT '0' COMMENT '0=>Points not awarded 1=>Points awarded'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instanceforms_join`
--

CREATE TABLE IF NOT EXISTS `instanceforms_join` (
  `id` int(11) NOT NULL,
  `instanceformid` int(11) NOT NULL,
  `g1` varchar(500) DEFAULT NULL,
  `g2` varchar(500) DEFAULT NULL,
  `g3` varchar(500) DEFAULT NULL,
  `g4` varchar(500) DEFAULT NULL,
  `g5` varchar(500) DEFAULT NULL,
  `g6` varchar(500) DEFAULT NULL,
  `g7` varchar(500) DEFAULT NULL,
  `g8` varchar(500) DEFAULT NULL,
  `g9` varchar(500) DEFAULT NULL,
  `g10` varchar(500) DEFAULT NULL,
  `g11` varchar(500) DEFAULT NULL,
  `g12` varchar(500) DEFAULT NULL,
  `g13` varchar(500) DEFAULT NULL,
  `g14` varchar(500) DEFAULT NULL,
  `g15` varchar(500) DEFAULT NULL,
  `g16` varchar(500) DEFAULT NULL,
  `g17` varchar(500) DEFAULT NULL,
  `g18` varchar(500) DEFAULT NULL,
  `g19` varchar(500) DEFAULT NULL,
  `g20` varchar(500) DEFAULT NULL,
  `g21` varchar(500) DEFAULT NULL,
  `g22` varchar(500) DEFAULT NULL,
  `g23` varchar(500) DEFAULT NULL,
  `g24` varchar(500) DEFAULT NULL,
  `g25` varchar(500) DEFAULT NULL,
  `g26` varchar(500) DEFAULT NULL,
  `g27` varchar(500) DEFAULT NULL,
  `g28` varchar(500) DEFAULT NULL,
  `g29` varchar(500) DEFAULT NULL,
  `g30` varchar(500) DEFAULT NULL,
  `g31` varchar(500) DEFAULT NULL,
  `g32` varchar(500) DEFAULT NULL,
  `g33` varchar(500) DEFAULT NULL,
  `g34` varchar(500) DEFAULT NULL,
  `g35` varchar(500) DEFAULT NULL,
  `g36` varchar(500) DEFAULT NULL,
  `g37` varchar(500) DEFAULT NULL,
  `g38` varchar(500) DEFAULT NULL,
  `g39` varchar(500) DEFAULT NULL,
  `g40` varchar(500) DEFAULT NULL,
  `gbig1` text,
  `gbig2` text,
  `gbig3` text,
  `gbig4` text,
  `gbig5` text,
  `gbig6` text,
  `gbig7` text,
  `gbig8` text,
  `gbig9` text,
  `gbig10` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instforms_files`
--

CREATE TABLE IF NOT EXISTS `instforms_files` (
  `id` int(11) NOT NULL,
  `instanceformid` int(11) DEFAULT NULL,
  `messageid` int(11) DEFAULT NULL,
  `filename` varchar(250) NOT NULL,
  `viewflag` varchar(250) NOT NULL DEFAULT '0',
  `created` int(255) NOT NULL,
  `date_created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `instforms_files_tmp`
--

CREATE TABLE IF NOT EXISTS `instforms_files_tmp` (
  `id` int(11) NOT NULL,
  `formid` int(11) DEFAULT NULL,
  `avatarid` int(11) DEFAULT NULL,
  `filename` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `instforms_groups`
--

CREATE TABLE IF NOT EXISTS `instforms_groups` (
  `id` int(11) NOT NULL,
  `instformid` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `access` varchar(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instforms_links`
--

CREATE TABLE IF NOT EXISTS `instforms_links` (
  `id` int(11) NOT NULL,
  `instanceformid` int(11) NOT NULL,
  `instanceformid_two` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

CREATE TABLE IF NOT EXISTS `job` (
  `id` int(11) unsigned NOT NULL,
  `job_tracker` varchar(100) NOT NULL,
  `job_type` varchar(1000) NOT NULL,
  `job_executor` varchar(255) NOT NULL,
  `job_params` text,
  `job_frequency_minutes` int(11) NOT NULL DEFAULT '0',
  `max_runs` int(3) NOT NULL DEFAULT '1',
  `num_of_runs` int(3) NOT NULL DEFAULT '0',
  `job_status` varchar(255) NOT NULL DEFAULT 'RUNNING',
  `is_job_in_progress` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_completed` timestamp NULL DEFAULT NULL,
  `last_exec_start_time` timestamp NULL DEFAULT NULL,
  `last_exec_end_time` timestamp NULL DEFAULT NULL,
  `last_exec_status` varchar(255) DEFAULT NULL,
  `last_exec_details` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kra_instanceform`
--

CREATE TABLE IF NOT EXISTS `kra_instanceform` (
  `id` int(11) NOT NULL,
  `queryid` int(11) NOT NULL,
  `instanceformid` int(11) NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `layer_animations`
--

CREATE TABLE IF NOT EXISTS `layer_animations` (
  `id` int(9) NOT NULL,
  `handle` text NOT NULL,
  `params` text NOT NULL,
  `settings` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `leaderboard`
--

CREATE TABLE IF NOT EXISTS `leaderboard` (
  `avatarid` int(20) NOT NULL,
  `goals` double NOT NULL,
  `starpoints` int(20) NOT NULL,
  `teamgoal` double DEFAULT NULL,
  `total` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `leaderboard_log`
--

CREATE TABLE IF NOT EXISTS `leaderboard_log` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `goals` double NOT NULL,
  `source_id` int(11) NOT NULL,
  `source` varchar(100) NOT NULL COMMENT 'T=>Timesheet|I=>Instanceform|A=>Subordinate Avatarid',
  `update_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE IF NOT EXISTS `likes` (
  `id` int(11) NOT NULL,
  `commentid` int(11) NOT NULL,
  `instanceformid` int(11) DEFAULT NULL,
  `type` varchar(20) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `groupid` int(11) DEFAULT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  `orgid` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `type` varchar(15) DEFAULT NULL,
  `text` varchar(250) NOT NULL,
  `url` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(11) NOT NULL,
  `code` varchar(250) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `avatarid` int(11) NOT NULL,
  `orgid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `loss_run_client`
--

CREATE TABLE IF NOT EXISTS `loss_run_client` (
  `id` int(11) NOT NULL,
  `loss_date` date DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `create_user` varchar(1000) DEFAULT NULL,
  `loss_id` varchar(1000) DEFAULT NULL,
  `pol_seq` int(11) DEFAULT NULL,
  `policy_number` varchar(1000) DEFAULT NULL,
  `ins` varchar(1000) DEFAULT NULL,
  `cov` varchar(1000) DEFAULT NULL,
  `client_code` varchar(1000) DEFAULT NULL,
  `client_name` varchar(1000) DEFAULT NULL,
  `eff_date` date DEFAULT NULL,
  `exp_date` date DEFAULT NULL,
  `insured_name` varchar(1000) DEFAULT NULL,
  `typ` varchar(1000) DEFAULT NULL,
  `loss_type` varchar(1000) DEFAULT NULL,
  `rep_date` date DEFAULT NULL,
  `loss_status` varchar(1000) DEFAULT NULL,
  `adjuster_assigned` varchar(1000) DEFAULT NULL,
  `loc` varchar(1000) DEFAULT NULL,
  `date_closed` date DEFAULT NULL,
  `written_premium` float(11,2) DEFAULT NULL,
  `annual_premium` float(11,2) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `last_entry_date` date DEFAULT NULL,
  `org_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `loss_run_overall`
--

CREATE TABLE IF NOT EXISTS `loss_run_overall` (
  `id` int(11) NOT NULL,
  `claim_number` varchar(100) NOT NULL,
  `claimant_name` varchar(100) DEFAULT NULL,
  `lob` varchar(500) DEFAULT NULL,
  `loss_date` date DEFAULT NULL,
  `carrier_report_date` date DEFAULT NULL,
  `incurred_indemnity` float(10,2) DEFAULT NULL,
  `incurred_medical` float(10,2) DEFAULT NULL,
  `incurred_expense` float(10,2) DEFAULT NULL,
  `total_incurred` float(10,2) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `paid_indemnity` float(10,2) DEFAULT NULL,
  `paid_medical` float(10,2) DEFAULT NULL,
  `pain_expense` float(10,2) DEFAULT NULL,
  `total_paid` float(10,2) DEFAULT NULL,
  `cause` varchar(1000) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `jurisdiction_state` varchar(100) DEFAULT NULL,
  `indemnity_or` float(10,2) DEFAULT NULL,
  `medical_or` float(10,2) DEFAULT NULL,
  `expense_or` float(10,2) DEFAULT NULL,
  `outstanding_reserve` float(10,2) DEFAULT NULL,
  `accident_state` varchar(100) DEFAULT NULL,
  `catalyst` varchar(100) DEFAULT NULL,
  `date_closed` date DEFAULT NULL,
  `date_of_hire` date DEFAULT NULL,
  `date_reopened` date DEFAULT NULL,
  `litigation_status` varchar(100) DEFAULT NULL,
  `lost_time_days` int(11) DEFAULT NULL,
  `nature_of_injury` varchar(1000) DEFAULT NULL,
  `part_of_body` varchar(100) DEFAULT NULL,
  `policy_number` varchar(100) DEFAULT NULL,
  `org_id` varchar(50) DEFAULT NULL,
  `premium` float(10,2) DEFAULT NULL,
  `last_rundate` date DEFAULT NULL,
  `loss_type` varchar(1000) DEFAULT NULL,
  `client_code` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mapping_table`
--

CREATE TABLE IF NOT EXISTS `mapping_table` (
  `id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `keyvalue` varchar(30) NOT NULL,
  `value` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `master_flags`
--

CREATE TABLE IF NOT EXISTS `master_flags` (
  `id` int(11) NOT NULL,
  `flag` varchar(100) NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `matrix_config`
--

CREATE TABLE IF NOT EXISTS `matrix_config` (
  `id` int(11) NOT NULL,
  `instanceformid` int(11) DEFAULT NULL,
  `type` varchar(1000) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `name_prefix` varchar(100) DEFAULT NULL,
  `formid` int(11) DEFAULT NULL,
  `fieldid` varchar(1000) DEFAULT NULL,
  `fieldname` varchar(1000) DEFAULT NULL,
  `source` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `matrix_days`
--

CREATE TABLE IF NOT EXISTS `matrix_days` (
  `id` int(11) NOT NULL,
  `matrix_date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `matrix_months`
--

CREATE TABLE IF NOT EXISTS `matrix_months` (
  `id` int(11) NOT NULL,
  `matrix_month` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `matrix_quarter`
--

CREATE TABLE IF NOT EXISTS `matrix_quarter` (
  `id` int(11) NOT NULL,
  `quarter` int(11) NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `matrix_years`
--

CREATE TABLE IF NOT EXISTS `matrix_years` (
  `id` int(11) NOT NULL,
  `matrix_years` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `menus_avatars`
--

CREATE TABLE IF NOT EXISTS `menus_avatars` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `canAvatars` tinyint(1) NOT NULL,
  `avatarsgroupid` varchar(1000) DEFAULT NULL,
  `canGroups` tinyint(1) NOT NULL,
  `groupsgroupid` varchar(1000) DEFAULT NULL,
  `canAlert` tinyint(1) NOT NULL,
  `alertgroupid` varchar(1000) DEFAULT NULL,
  `canAnnouncement` tinyint(1) NOT NULL,
  `announcementgroupid` varchar(1000) DEFAULT NULL,
  `canMenus` tinyint(1) NOT NULL,
  `menusgroupid` varchar(1000) DEFAULT NULL,
  `canLinks` tinyint(1) NOT NULL,
  `linksgroupid` varchar(1000) DEFAULT NULL,
  `canTiles` tinyint(1) NOT NULL,
  `tilesgroupid` varchar(1000) DEFAULT NULL,
  `canAssesments` tinyint(1) NOT NULL,
  `assesmentsgroupid` varchar(1000) DEFAULT NULL,
  `canFeature` tinyint(1) NOT NULL,
  `featuregroupid` varchar(1000) DEFAULT NULL,
  `canExport` tinyint(1) NOT NULL,
  `exportgroupid` varchar(1000) DEFAULT NULL,
  `canImport` tinyint(1) NOT NULL,
  `importgroupid` varchar(10000) DEFAULT NULL,
  `canEscalations` tinyint(1) NOT NULL,
  `escalationsgroupid` varchar(500) DEFAULT NULL,
  `canFlash` tinyint(4) NOT NULL,
  `flashgroupid` varchar(1000) DEFAULT NULL,
  `canWorkflow` tinyint(4) NOT NULL,
  `workflowgroupid` varchar(1000) DEFAULT NULL,
  `canKra` tinyint(4) NOT NULL,
  `kragroupid` varchar(1000) DEFAULT NULL,
  `canPolls` tinyint(4) NOT NULL,
  `pollsgroupid` varchar(1000) DEFAULT NULL,
  `canGamepoint` tinyint(4) NOT NULL,
  `gamepointgroupid` varchar(1000) DEFAULT NULL,
  `canPodview` tinyint(4) NOT NULL,
  `podviewgroupid` varchar(1000) DEFAULT NULL,
  `canContractrate` tinyint(4) NOT NULL,
  `canCarveoutsCostContract` tinyint(4) NOT NULL,
  `canPrimaryPhysician` tinyint(4) NOT NULL,
  `canReferPhysician` tinyint(4) NOT NULL,
  `canCostGroup` tinyint(4) NOT NULL,
  `canExecdesktop` tinyint(4) NOT NULL,
  `execdesktopgroups` varchar(1000) DEFAULT NULL,
  `canMatrix` tinyint(4) NOT NULL,
  `matrixgroup` varchar(1000) DEFAULT NULL,
  `canTimesheet` tinyint(4) NOT NULL,
  `timesheetgroup` varchar(1000) DEFAULT NULL,
  `canCustomlistview` tinyint(4) NOT NULL,
  `customlistviewgroup` varchar(1000) DEFAULT NULL,
  `canAppmatrix` tinyint(4) NOT NULL,
  `appmartixgroup` varchar(1000) DEFAULT NULL,
  `canManagemodule` tinyint(4) NOT NULL,
  `modulegroup` varchar(1000) DEFAULT NULL,
  `canmonitorusers` tinyint(4) NOT NULL,
  `monitorusersgroup` varchar(1000) DEFAULT NULL,
  `createdemolink` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL,
  `fromid` int(11) NOT NULL,
  `subject` varchar(250) NOT NULL,
  `message` mediumtext NOT NULL,
  `replyid` int(11) DEFAULT '0',
  `date_created` datetime NOT NULL,
  `setflag` tinyint(4) NOT NULL DEFAULT '0',
  `tags` varchar(250) DEFAULT NULL,
  `externalemail` varchar(100) DEFAULT NULL,
  `ccemaillist` varchar(100) DEFAULT NULL,
  `bccemaillist` varchar(100) DEFAULT NULL,
  `old_message` int(1) NOT NULL DEFAULT '0',
  `instanceformid` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `message_attachments`
--

CREATE TABLE IF NOT EXISTS `message_attachments` (
  `id` int(11) NOT NULL,
  `messageid` int(11) NOT NULL,
  `instanceformid` int(11) NOT NULL,
  `friendly_url` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `message_recepients`
--

CREATE TABLE IF NOT EXISTS `message_recepients` (
  `id` int(11) NOT NULL,
  `messageid` int(11) NOT NULL,
  `toid` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `message_status` int(1) DEFAULT '0',
  `label` int(2) NOT NULL DEFAULT '1',
  `date_moved` datetime DEFAULT NULL,
  `cc_bcc_flag` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `metafields`
--

CREATE TABLE IF NOT EXISTS `metafields` (
  `id` int(11) NOT NULL,
  `sequence` int(11) NOT NULL,
  `formid` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `text` varchar(400) CHARACTER SET ucs2 NOT NULL,
  `columnname` varchar(1000) DEFAULT NULL,
  `helpertext` varchar(150) DEFAULT NULL,
  `type` varchar(30) CHARACTER SET ucs2 DEFAULT NULL,
  `options` varchar(10000) CHARACTER SET ucs2 DEFAULT NULL,
  `color` varchar(1000) DEFAULT NULL,
  `dependson` varchar(50) CHARACTER SET ucs2 DEFAULT NULL,
  `disablejavascript` tinyint(4) DEFAULT '0',
  `required` tinyint(1) DEFAULT '0',
  `regexpvalidator` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `validationtext` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `specialvalidator` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `readonly` tinyint(1) DEFAULT '0',
  `expression` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `canbehidden` tinyint(4) NOT NULL DEFAULT '0',
  `dateordependson` varchar(30) DEFAULT NULL,
  `onlyownrcanchng` tinyint(1) DEFAULT '0',
  `condition` varchar(250) DEFAULT NULL,
  `premiumname` varchar(50) DEFAULT NULL,
  `xflat_parameter` int(2) NOT NULL DEFAULT '0',
  `esign_parameter` int(11) NOT NULL DEFAULT '0' COMMENT 'this field will be used in esign api',
  `field_type` varchar(100) NOT NULL DEFAULT 'config',
  `category` varchar(1000) NOT NULL DEFAULT '1',
  `field_value` varchar(200) NOT NULL,
  `display` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `metaforms`
--

CREATE TABLE IF NOT EXISTS `metaforms` (
  `id` int(11) NOT NULL,
  `canhaveemail` tinyint(1) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `description` varchar(500) NOT NULL,
  `canhavedigitalsign` tinyint(1) DEFAULT NULL,
  `canhavecategory` tinyint(1) DEFAULT NULL,
  `sequence` int(11) NOT NULL,
  `nextsequence` varchar(50) DEFAULT NULL,
  `htmltext` longtext,
  `orgid` int(11) NOT NULL,
  `moduleid` int(11) NOT NULL,
  `canhaveparent` tinyint(1) DEFAULT '0',
  `canhavespreadsheet` tinyint(1) NOT NULL DEFAULT '1',
  `makeparentmandatory` tinyint(1) DEFAULT '0',
  `canassign` tinyint(1) DEFAULT NULL,
  `canassigngroup` tinyint(4) DEFAULT NULL,
  `canhidetime` tinyint(1) DEFAULT '0',
  `canmultiassign` tinyint(1) DEFAULT NULL,
  `onlyadmincancreate` tinyint(4) DEFAULT '0',
  `statusfield` varchar(30) DEFAULT NULL,
  `emailfields` varchar(500) DEFAULT NULL,
  `printfields` varchar(500) DEFAULT NULL,
  `defaultassigngroup` int(11) DEFAULT NULL,
  `canhaveattachment` tinyint(4) NOT NULL,
  `canhavewriteaccess` tinyint(4) NOT NULL,
  `canhavereadaccess` tinyint(4) NOT NULL,
  `can_create_duplicate` tinyint(1) NOT NULL COMMENT 'create copy of the form on create (smith featuring)',
  `nodelete` tinyint(1) NOT NULL COMMENT 'remove delete button with a value 1',
  `hidestarpoints` tinyint(1) NOT NULL,
  `hidecost` tinyint(1) NOT NULL,
  `startdatefield` varchar(30) DEFAULT NULL,
  `nextactiondatefield` varchar(30) DEFAULT NULL,
  `enddatefield` varchar(30) DEFAULT NULL,
  `assignedtoview` varchar(20) NOT NULL DEFAULT 'view',
  `assignedtofromdefaultgroup` varchar(50) NOT NULL,
  `statuslist` varchar(250) DEFAULT NULL,
  `statuslistcolor` varchar(250) DEFAULT NULL,
  `hidetags` varchar(250) DEFAULT NULL,
  `hideleveldifference` int(4) DEFAULT NULL,
  `showallassignedgroup` tinyint(1) NOT NULL DEFAULT '0',
  `showallownergroup` tinyint(1) NOT NULL DEFAULT '0',
  `kracategories` varchar(250) DEFAULT NULL,
  `krasubcategories` varchar(250) DEFAULT NULL,
  `wizard_id` int(12) DEFAULT NULL,
  `canhavekra` tinyint(4) DEFAULT '0',
  `goals` varchar(200) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `customcreate` int(11) DEFAULT NULL,
  `customview` int(11) DEFAULT NULL,
  `disable_mupdate` tinyint(4) NOT NULL DEFAULT '0',
  `disable_inlineedit` tinyint(4) NOT NULL DEFAULT '0',
  `discussionStartCount` int(3) DEFAULT NULL,
  `allow_moderator` int(11) DEFAULT '0' COMMENT 'Allow Comments Moderaor',
  `disable_calendar` tinyint(4) DEFAULT '0',
  `can_have_map` tinyint(4) DEFAULT '0',
  `emailaddress` tinyint(11) NOT NULL,
  `reffield1` int(11) DEFAULT NULL,
  `reffield2` int(11) DEFAULT NULL,
  `reffield3` int(11) DEFAULT NULL,
  `reffield4` int(11) DEFAULT NULL,
  `reffield5` int(11) DEFAULT NULL,
  `template` text,
  `canvas` text,
  `formdeleteaccess` varchar(500) DEFAULT NULL,
  `defaultgroupaccess` int(11) DEFAULT NULL,
  `nextactiondatediff` int(11) DEFAULT NULL,
  `enddatediff` int(11) DEFAULT NULL,
  `ownerassignedcanedit` tinyint(4) DEFAULT NULL,
  `cancopywizardvalues` tinyint(1) NOT NULL,
  `category` varchar(1000) NOT NULL DEFAULT '1=>General',
  `can_have_print` tinyint(1) DEFAULT '1',
  `can_have_copyURL` tinyint(1) DEFAULT '1',
  `can_have_pm` tinyint(1) DEFAULT '1',
  `can_have_logofactivities` tinyint(1) DEFAULT '1',
  `can_have_stickynotes` tinyint(1) DEFAULT '1',
  `can_have_lockrecord` tinyint(1) DEFAULT '1',
  `can_have_convert` tinyint(1) DEFAULT '1',
  `can_have_edit` tinyint(1) DEFAULT '1',
  `can_have_message` tinyint(1) DEFAULT '1',
  `can_have_like` tinyint(1) DEFAULT '1',
  `can_have_comments` tinyint(1) DEFAULT '1',
  `can_have_workrelated` tinyint(1) DEFAULT '1',
  `can_have_spreadsheet` tinyint(1) DEFAULT '1',
  `can_have_assignments` tinyint(1) DEFAULT '1',
  `can_have_quick_edit` tinyint(1) DEFAULT '1',
  `can_have_dyn_info_view` tinyint(1) DEFAULT '1',
  `fieldview` varchar(100) DEFAULT 'col-md-3'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `metaform_fieldorder`
--

CREATE TABLE IF NOT EXISTS `metaform_fieldorder` (
  `id` int(11) NOT NULL,
  `fieldname` varchar(100) DEFAULT NULL,
  `formid` int(11) DEFAULT NULL,
  `fieldorder` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `metalist`
--

CREATE TABLE IF NOT EXISTS `metalist` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `metapdffields`
--

CREATE TABLE IF NOT EXISTS `metapdffields` (
  `id` int(11) NOT NULL,
  `pdfformid` int(11) NOT NULL,
  `page` int(11) NOT NULL,
  `xcoord` int(11) NOT NULL,
  `ycoord` int(11) NOT NULL,
  `field` varchar(100) DEFAULT NULL,
  `expression` varchar(500) DEFAULT NULL,
  `fontsize` tinyint(4) DEFAULT NULL,
  `fontcolor` varchar(100) DEFAULT NULL,
  `tablename` varchar(100) DEFAULT NULL,
  `multiple` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `metapdfforms`
--

CREATE TABLE IF NOT EXISTS `metapdfforms` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `description` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `modulename` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `filename` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `condition` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `tables` varchar(200) DEFAULT NULL,
  `type` varchar(30) NOT NULL,
  `wizardid` int(11) DEFAULT NULL,
  `customfunction` varchar(200) DEFAULT NULL,
  `multiple` tinyint(1) NOT NULL DEFAULT '0',
  `multiplefieldname` varchar(200) DEFAULT NULL,
  `sequence` varchar(20) DEFAULT NULL,
  `formid` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `metareportfields`
--

CREATE TABLE IF NOT EXISTS `metareportfields` (
  `id` int(11) NOT NULL,
  `name` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `reportid` int(11) NOT NULL,
  `formid` int(11) NOT NULL,
  `fieldid` varchar(100) CHARACTER SET utf8 NOT NULL,
  `expression` varchar(100) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `metareports`
--

CREATE TABLE IF NOT EXISTS `metareports` (
  `id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `description` varchar(100) CHARACTER SET utf8 NOT NULL,
  `clientid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `metastatus`
--

CREATE TABLE IF NOT EXISTS `metastatus` (
  `id` int(11) NOT NULL,
  `formid` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `statusvalue` int(11) DEFAULT NULL,
  `statusname` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `meta_import`
--

CREATE TABLE IF NOT EXISTS `meta_import` (
  `id` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `description` varchar(500) NOT NULL,
  `orgid` int(11) NOT NULL,
  `type` varchar(500) NOT NULL,
  `existingkeycolumn` int(11) DEFAULT NULL,
  `terminationdatecolno` tinyint(4) DEFAULT NULL,
  `datatype` int(11) DEFAULT NULL,
  `tablename` text,
  `existingflag` tinyint(4) DEFAULT NULL,
  `existingkeyfield` varchar(30) DEFAULT NULL,
  `wizardids` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `meta_importcolumns`
--

CREATE TABLE IF NOT EXISTS `meta_importcolumns` (
  `id` int(11) NOT NULL,
  `importid` int(11) NOT NULL,
  `columnnumber` int(11) DEFAULT NULL,
  `fieldname` varchar(500) NOT NULL,
  `fieldtype` varchar(500) NOT NULL,
  `expression` varchar(500) DEFAULT NULL,
  `lookup` varchar(200) DEFAULT NULL,
  `wizardid` int(15) DEFAULT NULL,
  `multiple` varchar(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `meta_multiselect`
--

CREATE TABLE IF NOT EXISTS `meta_multiselect` (
  `id` int(11) NOT NULL,
  `instanceformid` int(11) NOT NULL,
  `fieldid` int(11) NOT NULL,
  `selectedid` int(3) NOT NULL,
  `selectedvalue` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mletquestions`
--

CREATE TABLE IF NOT EXISTS `mletquestions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `questiontext` varchar(256) NOT NULL,
  `parameters` varchar(256) NOT NULL,
  `queryconfigid` int(11) DEFAULT NULL,
  `html` longtext,
  `groupid` int(11) DEFAULT NULL,
  `orgid` int(11) NOT NULL,
  `mletlist` varchar(200) DEFAULT NULL,
  `where_used` varchar(200) NOT NULL,
  `description` mediumtext NOT NULL,
  `templateid` int(11) DEFAULT NULL,
  `directsql` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `modulecategories`
--

CREATE TABLE IF NOT EXISTS `modulecategories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `color` varchar(20) NOT NULL,
  `sequence` int(11) NOT NULL,
  `orgid` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `description` varchar(100) NOT NULL,
  `sequence` int(11) NOT NULL,
  `htmltext` longtext,
  `type` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `viewtype` varchar(10) NOT NULL,
  `customname` varchar(20) NOT NULL,
  `logo` varchar(50) DEFAULT NULL,
  `email` varchar(50) NOT NULL DEFAULT 'Active',
  `appcolor` varchar(111) NOT NULL DEFAULT 'blue',
  `helppdf` varchar(11) NOT NULL,
  `matrix_reference_name` varchar(100) DEFAULT NULL,
  `hidepivotgrid0` tinyint(4) DEFAULT NULL,
  `hidepivotgrid1` tinyint(4) DEFAULT NULL,
  `hidepivotgrid2` tinyint(4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `module_map_category`
--

CREATE TABLE IF NOT EXISTS `module_map_category` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `moduleid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mod_training_app_data`
--

CREATE TABLE IF NOT EXISTS `mod_training_app_data` (
  `id` tinyint(4) NOT NULL,
  `assessid` tinyint(4) NOT NULL,
  `name` tinyint(4) NOT NULL,
  `description` tinyint(4) NOT NULL,
  `htmltext` tinyint(4) NOT NULL,
  `orgid` tinyint(4) NOT NULL,
  `formid` tinyint(4) NOT NULL,
  `createdid` tinyint(4) NOT NULL,
  `modifiedid` tinyint(4) NOT NULL,
  `assignedto` tinyint(4) NOT NULL,
  `assignedgroup` tinyint(4) NOT NULL,
  `date_created` tinyint(4) NOT NULL,
  `date_modified` tinyint(4) NOT NULL,
  `ownergroupid` tinyint(4) NOT NULL,
  `parentinstformid` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `duplicate` tinyint(4) NOT NULL,
  `startdate` tinyint(4) NOT NULL,
  `nextactiondate` tinyint(4) NOT NULL,
  `enddate` tinyint(4) NOT NULL,
  `cost` tinyint(4) NOT NULL,
  `starpoints` tinyint(4) NOT NULL,
  `testerid` tinyint(4) NOT NULL,
  `testercode` tinyint(4) NOT NULL,
  `field3` tinyint(4) NOT NULL,
  `tags` tinyint(4) NOT NULL,
  `category` tinyint(4) NOT NULL,
  `goals` tinyint(4) NOT NULL,
  `kracategory` tinyint(4) NOT NULL,
  `krasubcategory` tinyint(4) NOT NULL,
  `observer` tinyint(4) NOT NULL,
  `region` tinyint(4) NOT NULL,
  `centre` tinyint(4) NOT NULL,
  `batchcode` tinyint(4) NOT NULL,
  `rollno` tinyint(4) NOT NULL,
  `Type` tinyint(4) NOT NULL,
  `sprint` tinyint(4) NOT NULL,
  `teachers` tinyint(4) NOT NULL,
  `grade` tinyint(4) NOT NULL,
  `date` tinyint(4) NOT NULL,
  `trackerupdate` tinyint(4) NOT NULL,
  `schedule_conformance` tinyint(4) NOT NULL,
  `attendance` tinyint(4) NOT NULL,
  `assignment` tinyint(4) NOT NULL,
  `dormitory` tinyint(4) NOT NULL,
  `lastsprintscore` tinyint(4) NOT NULL,
  `lastsprintstatus` tinyint(4) NOT NULL,
  `Tollgate` tinyint(4) NOT NULL,
  `tollgatestatus` tinyint(4) NOT NULL,
  `MOP` tinyint(4) NOT NULL,
  `fees` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `navigations`
--

CREATE TABLE IF NOT EXISTS `navigations` (
  `id` int(9) NOT NULL,
  `name` varchar(191) NOT NULL,
  `handle` varchar(191) NOT NULL,
  `css` longtext NOT NULL,
  `markup` longtext NOT NULL,
  `settings` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `non_compliance`
--

CREATE TABLE IF NOT EXISTS `non_compliance` (
  `id` int(11) NOT NULL,
  `assesmentid` int(11) NOT NULL,
  `instanceformid` int(12) NOT NULL,
  `name` varchar(25) NOT NULL,
  `avatar` int(12) NOT NULL,
  `Parent` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='This table will help to make everything non complaint whatever needed ';

-- --------------------------------------------------------

--
-- Table structure for table `oauth2_setting`
--

CREATE TABLE IF NOT EXISTS `oauth2_setting` (
  `id` int(11) unsigned NOT NULL,
  `userid` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `provider` varchar(100) NOT NULL,
  `credentials` text NOT NULL,
  `refresh_token` text NOT NULL,
  `calendarflag` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `observers`
--

CREATE TABLE IF NOT EXISTS `observers` (
  `id` int(11) NOT NULL,
  `avatarids` varchar(255) DEFAULT NULL,
  `groupid` varchar(255) DEFAULT NULL,
  `group_avatars` varchar(255) DEFAULT NULL,
  `external_emails` varchar(255) DEFAULT NULL,
  `instanceformid` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `offboarding_audit`
--

CREATE TABLE IF NOT EXISTS `offboarding_audit` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL DEFAULT '0',
  `avatarid` int(11) NOT NULL DEFAULT '0' COMMENT 'the person who had done the offboarding',
  `status` tinyint(1) NOT NULL COMMENT '1=export completed, 2=deactivate users, 3=deactivate groups, 4=deactivated organization,5=remove from groups, 6=remove as observer',
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Based on offboarding, that particular orgid, status and the avatarid will be recorded in this table.';

-- --------------------------------------------------------

--
-- Table structure for table `operatingrhythm`
--

CREATE TABLE IF NOT EXISTS `operatingrhythm` (
  `id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `summary` text,
  `startdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `enddate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `organizer` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `type` int(255) DEFAULT NULL,
  `instanceformid` int(11) DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  `reid` int(11) DEFAULT NULL,
  `rrule` text,
  `rexception` text,
  `location` text,
  `reminderperiod` int(255) DEFAULT NULL,
  `emails` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `operatingrhythm_avatars`
--

CREATE TABLE IF NOT EXISTS `operatingrhythm_avatars` (
  `id` int(11) NOT NULL,
  `operatingrhythmid` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `operatingrhythm_groups`
--

CREATE TABLE IF NOT EXISTS `operatingrhythm_groups` (
  `id` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `operatingrhythmid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE IF NOT EXISTS `organizations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `address` varchar(200) CHARACTER SET utf8 NOT NULL,
  `city` varchar(100) CHARACTER SET utf8 NOT NULL,
  `state` varchar(2) CHARACTER SET utf8 NOT NULL,
  `zip` varchar(5) CHARACTER SET utf8 NOT NULL,
  `logo` varchar(100) NOT NULL,
  `defaultgroupid` int(11) NOT NULL,
  `statusbox` varchar(250) NOT NULL DEFAULT 'Matrix|MyKRA|StarPoints|Alerts',
  `labelfile` varchar(40) DEFAULT NULL,
  `messagecount` int(12) DEFAULT '200',
  `languagefile` varchar(40) DEFAULT 'en',
  `orgtype` tinyint(1) NOT NULL DEFAULT '0',
  `flash_msg` int(11) DEFAULT '0',
  `email` varchar(100) NOT NULL DEFAULT 'Active',
  `themes` tinyint(4) NOT NULL DEFAULT '0',
  `formview` tinyint(4) NOT NULL DEFAULT '0',
  `assign_followuplimit` int(11) NOT NULL DEFAULT '10',
  `insurelearn` tinyint(4) NOT NULL DEFAULT '0',
  `reset_password` int(11) DEFAULT '0',
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orgclientlist`
--

CREATE TABLE IF NOT EXISTS `orgclientlist` (
  `id` int(11) NOT NULL,
  `clientid` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `clientname` varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orgs_modules`
--

CREATE TABLE IF NOT EXISTS `orgs_modules` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `moduleid` int(11) NOT NULL,
  `email` varchar(50) NOT NULL DEFAULT 'Active',
  `instanceformid` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orgs_partners`
--

CREATE TABLE IF NOT EXISTS `orgs_partners` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `partnerid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `org_role`
--

CREATE TABLE IF NOT EXISTS `org_role` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `org_role` varchar(100) NOT NULL,
  `hiddentopicons` tinyint(1) NOT NULL,
  `hidetiles` tinyint(1) NOT NULL,
  `hidewall` tinyint(1) NOT NULL,
  `hideannouncement` tinyint(1) NOT NULL,
  `hideleaderboard` tinyint(1) NOT NULL,
  `hiddenmessage` tinyint(1) DEFAULT NULL,
  `hiddenassignment` tinyint(1) DEFAULT NULL,
  `hiddenfollowup` tinyint(1) DEFAULT NULL,
  `hiddencreate` tinyint(1) DEFAULT NULL,
  `hiddensearch` tinyint(1) DEFAULT NULL,
  `hiddengroup` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `or_attendees`
--

CREATE TABLE IF NOT EXISTS `or_attendees` (
  `id` int(255) NOT NULL,
  `eventid` int(255) NOT NULL,
  `avatarid` int(255) NOT NULL,
  `status` int(255) NOT NULL,
  `reminder` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `or_meta`
--

CREATE TABLE IF NOT EXISTS `or_meta` (
  `id` int(255) NOT NULL,
  `eventtype` varchar(250) DEFAULT NULL,
  `eventid` varchar(255) NOT NULL,
  `repeat_start` date DEFAULT NULL,
  `repeat_end` date DEFAULT NULL,
  `repeat_interval` varchar(255) DEFAULT NULL,
  `repeat_year` varchar(255) DEFAULT '*',
  `repeat_month` varchar(255) DEFAULT '*',
  `repeat_day` varchar(255) DEFAULT '*',
  `repeat_week` varchar(255) DEFAULT '*',
  `repeat_weekday` varchar(255) DEFAULT '*'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oxmedia_devices`
--

CREATE TABLE IF NOT EXISTS `oxmedia_devices` (
  `id` int(11) NOT NULL,
  `device_name` varchar(400) CHARACTER SET utf8 NOT NULL,
  `device_id` varchar(400) DEFAULT NULL,
  `description` mediumtext
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oxmedia_devices_sliders`
--

CREATE TABLE IF NOT EXISTS `oxmedia_devices_sliders` (
  `id` int(11) NOT NULL,
  `sliderid` int(11) NOT NULL,
  `deviceid` int(11) NOT NULL,
  `enable` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oxmedia_playlist`
--

CREATE TABLE IF NOT EXISTS `oxmedia_playlist` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `socialmedia` int(5) DEFAULT NULL COMMENT '1=>Twitter|2=>Instagram',
  `hashtag` text,
  `watermark` tinyint(1) NOT NULL,
  `medialocation` varchar(1000) NOT NULL,
  `venue_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oxmedia_slides`
--

CREATE TABLE IF NOT EXISTS `oxmedia_slides` (
  `id` int(6) NOT NULL,
  `slider_id` int(6) NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `type` int(5) NOT NULL COMMENT '0=>image,1=>video,2=>mlet',
  `instformid` int(11) DEFAULT NULL,
  `alertid` int(11) DEFAULT NULL,
  `duration` int(10) DEFAULT NULL,
  `enable` int(5) NOT NULL,
  `medialocation` varchar(1000) NOT NULL,
  `options` text,
  `sequence_no` int(11) DEFAULT NULL,
  `socialmediaid` varchar(250) DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  `starttime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ox_alert`
--

CREATE TABLE IF NOT EXISTS `ox_alert` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `org_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `description` text NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `created_date` datetime NOT NULL,
  `created_id` int(11) NOT NULL,
  `media_type` varchar(2000) DEFAULT NULL,
  `media_location` varchar(2000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ox_alert_group_mapper`
--

CREATE TABLE IF NOT EXISTS `ox_alert_group_mapper` (
  `id` int(11) NOT NULL,
  `alert_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ox_announcement`
--

CREATE TABLE IF NOT EXISTS `ox_announcement` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `org_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `description` text,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `created_date` datetime NOT NULL,
  `created_id` int(11) NOT NULL,
  `media_type` varchar(2000) DEFAULT NULL,
  `media_location` varchar(2000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table to store the list of all announcement for the organization';

-- --------------------------------------------------------

--
-- Table structure for table `ox_announcement_group_mapper`
--

CREATE TABLE IF NOT EXISTS `ox_announcement_group_mapper` (
  `id` int(11) NOT NULL,
  `announcement_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `podview`
--

CREATE TABLE IF NOT EXISTS `podview` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `instanceform` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `url` varchar(250) DEFAULT NULL,
  `position` varchar(10) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `poll_answers`
--

CREATE TABLE IF NOT EXISTS `poll_answers` (
  `id` int(11) NOT NULL,
  `pollid` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `answer` tinyint(1) NOT NULL,
  `modifieddate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `poll_questions`
--

CREATE TABLE IF NOT EXISTS `poll_questions` (
  `id` int(11) NOT NULL,
  `pollid` int(11) NOT NULL,
  `question` varchar(1000) NOT NULL,
  `creatorid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `prid` int(11) NOT NULL,
  `realname` varchar(50) DEFAULT NULL,
  `owner` varchar(30) DEFAULT NULL,
  `priority` varchar(20) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `queries`
--

CREATE TABLE IF NOT EXISTS `queries` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `type` int(50) NOT NULL,
  `querytext` varchar(500) NOT NULL,
  `resulttype` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `query_config`
--

CREATE TABLE IF NOT EXISTS `query_config` (
  `id` int(11) NOT NULL,
  `name` varchar(1000) DEFAULT NULL,
  `question_text` varchar(1000) DEFAULT NULL,
  `question_name` varchar(1000) DEFAULT NULL,
  `source` int(11) DEFAULT NULL COMMENT 'the source of data 1=>instanceform|2=>Timesheet',
  `sourceoption` varchar(10000) DEFAULT NULL COMMENT 'main filter parameter like formid for instancerform',
  `type` int(11) NOT NULL COMMENT 'where this query is used',
  `configs` varchar(10000) DEFAULT NULL,
  `link` varchar(10000) DEFAULT NULL,
  `orgid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `questionqueries`
--

CREATE TABLE IF NOT EXISTS `questionqueries` (
  `id` int(11) NOT NULL,
  `resultkey` varchar(50) DEFAULT NULL,
  `questionid` int(11) NOT NULL,
  `queryid` int(11) NOT NULL,
  `parameters` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
  `id` int(11) NOT NULL,
  `questiontext` varchar(500) NOT NULL,
  `parameters` varchar(200) NOT NULL,
  `instanceform` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `relationshipinstance`
--

CREATE TABLE IF NOT EXISTS `relationshipinstance` (
  `id` int(11) NOT NULL,
  `relationshipid` int(11) NOT NULL,
  `instanceformidfrom` int(11) NOT NULL,
  `instanceformidto` int(11) NOT NULL,
  `f1` varchar(500) NOT NULL,
  `f2` varchar(500) NOT NULL,
  `f3` varchar(50) NOT NULL,
  `f4` varchar(100) NOT NULL,
  `f5` varchar(100) NOT NULL,
  `f6` varchar(100) NOT NULL,
  `f7` varchar(100) NOT NULL,
  `f8` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `relationships`
--

CREATE TABLE IF NOT EXISTS `relationships` (
  `id` int(11) NOT NULL,
  `metaformidfrom` int(11) NOT NULL,
  `metaformidto` int(11) NOT NULL,
  `displayfields` varchar(200) DEFAULT NULL,
  `reffieldname` varchar(20) NOT NULL,
  `labelfrom` varchar(50) DEFAULT NULL,
  `labelto` varchar(50) NOT NULL,
  `instanceformfields` varchar(100) NOT NULL,
  `fieldmapping` varchar(100) NOT NULL,
  `options` varchar(500) NOT NULL,
  `relationtype` int(11) NOT NULL,
  `parentrelation` int(11) DEFAULT NULL,
  `mappertype` varchar(100) DEFAULT NULL,
  `fieldmultiplier` varchar(50) DEFAULT NULL,
  `required` varchar(11) NOT NULL DEFAULT 'false'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rpt_cluster4`
--

CREATE TABLE IF NOT EXISTS `rpt_cluster4` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `received_calls` int(11) DEFAULT NULL,
  `abandoned_calls` int(11) DEFAULT NULL,
  `answered_calls` int(11) DEFAULT NULL,
  `average_per` float DEFAULT NULL,
  `answered_sl` int(11) DEFAULT NULL,
  `service_level_per` float DEFAULT NULL,
  `aht` int(11) DEFAULT NULL,
  `hold_time` int(11) DEFAULT NULL,
  `acw` int(11) DEFAULT NULL,
  `att` int(11) DEFAULT NULL,
  `idle_time` int(11) DEFAULT NULL,
  `held_calls_per` float DEFAULT NULL,
  `held_calls` int(11) DEFAULT NULL,
  `abandoned_per` float DEFAULT NULL,
  `asa` float DEFAULT NULL,
  `client` varchar(500) DEFAULT NULL,
  `report` varchar(500) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rpt_data`
--

CREATE TABLE IF NOT EXISTS `rpt_data` (
  `id` int(11) NOT NULL,
  `client` varchar(500) NOT NULL,
  `aht` float NOT NULL,
  `acwt` float NOT NULL,
  `att` float NOT NULL,
  `asa` float NOT NULL,
  `call_date` date DEFAULT NULL,
  `service_level_per` float DEFAULT NULL,
  `idle_time` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rpt_volvo`
--

CREATE TABLE IF NOT EXISTS `rpt_volvo` (
  `id` int(11) NOT NULL,
  `call_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `calls_received` int(11) DEFAULT NULL,
  `calls_answered` int(11) DEFAULT NULL,
  `acc_call_ans` float DEFAULT NULL,
  `ans_per` float DEFAULT NULL,
  `service_level` float DEFAULT NULL,
  `aht` int(11) DEFAULT NULL,
  `aban_call` int(11) DEFAULT NULL,
  `client` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rules`
--

CREATE TABLE IF NOT EXISTS `rules` (
  `idrules` int(11) NOT NULL,
  `rulename` varchar(45) DEFAULT NULL,
  `procedurename` varchar(45) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE IF NOT EXISTS `sales` (
  `Date` date NOT NULL,
  `KeyMetrics` varchar(45) NOT NULL,
  `FinalParameter` decimal(15,2) DEFAULT NULL,
  `Qty` int(11) DEFAULT NULL,
  `ActualRevenue` decimal(15,2) DEFAULT NULL,
  `ActualRevenueChange` decimal(15,2) DEFAULT NULL,
  `ActualMargin` decimal(15,2) DEFAULT NULL,
  `ActualMarginChange` decimal(15,2) DEFAULT NULL,
  `PlanRevenue` decimal(15,2) DEFAULT NULL,
  `PlanRevenueChange` decimal(15,2) DEFAULT NULL,
  `PlanMargin` decimal(15,2) DEFAULT NULL,
  `PlanMarginChange` decimal(15,2) DEFAULT NULL,
  `ClientID` varchar(45) DEFAULT NULL,
  `FYYear` varchar(45) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sales_pipeline_kra`
--

CREATE TABLE IF NOT EXISTS `sales_pipeline_kra` (
  `id` int(11) NOT NULL,
  `orgid` int(11) DEFAULT NULL,
  `avatarid` int(11) NOT NULL,
  `date` date NOT NULL,
  `account_target` int(11) NOT NULL,
  `amount_target` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sla`
--

CREATE TABLE IF NOT EXISTS `sla` (
  `id` int(11) NOT NULL,
  `groupid` int(11) DEFAULT NULL,
  `slacol` varchar(45) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `target` int(11) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `error` int(10) DEFAULT NULL,
  `comment` varchar(500) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE IF NOT EXISTS `sliders` (
  `id` int(9) NOT NULL,
  `title` tinytext NOT NULL,
  `alias` tinytext,
  `params` longtext NOT NULL,
  `settings` text,
  `type` varchar(191) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `slides`
--

CREATE TABLE IF NOT EXISTS `slides` (
  `id` int(9) NOT NULL,
  `slider_id` int(9) NOT NULL,
  `slide_order` int(11) NOT NULL,
  `params` longtext NOT NULL,
  `layers` longtext NOT NULL,
  `settings` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `spreadsheet_mapper`
--

CREATE TABLE IF NOT EXISTS `spreadsheet_mapper` (
  `spreadsheetid` int(50) NOT NULL,
  `instanceformid` int(11) NOT NULL,
  `locked` int(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `srf`
--

CREATE TABLE IF NOT EXISTS `srf` (
  `srfid` int(11) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `prid` int(11) DEFAULT NULL,
  `remarks` varchar(1000) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `sprid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `static_slides`
--

CREATE TABLE IF NOT EXISTS `static_slides` (
  `id` int(9) NOT NULL,
  `slider_id` int(9) NOT NULL,
  `params` longtext NOT NULL,
  `layers` longtext NOT NULL,
  `settings` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stattracker`
--

CREATE TABLE IF NOT EXISTS `stattracker` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `ip` varchar(100) DEFAULT '',
  `thedate_visited` datetime DEFAULT NULL,
  `page` varchar(70) DEFAULT '',
  `logout_date` datetime DEFAULT NULL,
  `systeminfo` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `statusboxes`
--

CREATE TABLE IF NOT EXISTS `statusboxes` (
  `id` int(11) NOT NULL,
  `name` varchar(30) CHARACTER SET utf8 NOT NULL,
  `class` varchar(100) DEFAULT NULL,
  `label` varchar(100) NOT NULL DEFAULT 'Status Box',
  `color` varchar(100) NOT NULL DEFAULT 'green',
  `link` varchar(100) DEFAULT NULL,
  `imageicon` varchar(50) NOT NULL DEFAULT 'icon-bell',
  `linklabel` varchar(100) NOT NULL DEFAULT 'Status Box',
  `class_method` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `popup` longtext,
  `popuptitle` varchar(500) DEFAULT NULL,
  `showinpopup` tinyint(1) DEFAULT '0',
  `style` varchar(100) DEFAULT NULL,
  `linkclass` varchar(100) DEFAULT NULL,
  `subtile` varchar(250) DEFAULT NULL,
  `embed` varchar(250) DEFAULT NULL,
  `props` varchar(500) DEFAULT NULL,
  `sequence_no` int(10) DEFAULT NULL,
  `force_add_avatar` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sticky_notes`
--

CREATE TABLE IF NOT EXISTS `sticky_notes` (
  `id` int(11) NOT NULL,
  `instanceformid` int(11) NOT NULL,
  `message` longtext,
  `color` int(2) DEFAULT NULL,
  `left` varchar(10) DEFAULT NULL,
  `top` varchar(10) DEFAULT NULL,
  `tabid` varchar(55) DEFAULT NULL,
  `avatarid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE IF NOT EXISTS `store` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `available` int(11) NOT NULL,
  `requested` int(11) DEFAULT NULL,
  `vendor` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sub_projects`
--

CREATE TABLE IF NOT EXISTS `sub_projects` (
  `sprid` int(11) NOT NULL,
  `realname` varchar(500) NOT NULL,
  `priority` varchar(500) NOT NULL,
  `remarks` varchar(1000) NOT NULL,
  `status` varchar(500) NOT NULL,
  `assigned_to` varchar(500) NOT NULL,
  `prid` int(11) NOT NULL,
  `spr_owner_guid` int(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `supportservice`
--

CREATE TABLE IF NOT EXISTS `supportservice` (
  `keyID` varchar(110) NOT NULL,
  `Date` date NOT NULL,
  `Incoming` int(11) NOT NULL,
  `Outgoing` int(11) NOT NULL,
  `Process` varchar(110) NOT NULL,
  `OtherFactor` int(11) NOT NULL,
  `AddInfo` int(11) NOT NULL,
  `Pending` int(11) NOT NULL,
  `Error` int(11) NOT NULL,
  `Client_ID` varchar(110) NOT NULL,
  `KRA` varchar(110) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `table_statesave`
--

CREATE TABLE IF NOT EXISTS `table_statesave` (
  `id` int(11) NOT NULL,
  `avatarid` int(11) NOT NULL,
  `groupid` int(11) DEFAULT '0',
  `moduleid` int(11) DEFAULT '0',
  `formid` int(11) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `state` text NOT NULL,
  `colorder` text NOT NULL,
  `hiddencol` text,
  `collocked` varchar(250) DEFAULT NULL,
  `name` varchar(250) DEFAULT 'Default',
  `def` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `testcase`
--

CREATE TABLE IF NOT EXISTS `testcase` (
  `id` int(11) unsigned NOT NULL,
  `srfid` varchar(11) NOT NULL,
  `type` varchar(10000) NOT NULL,
  `testname` varchar(100) DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `cond1` varchar(30) DEFAULT NULL,
  `cond2` varchar(30) DEFAULT NULL,
  `assignedto` varchar(50) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `estimated_date` date DEFAULT NULL,
  `percent_completion` varchar(123) DEFAULT '0',
  `status` varchar(30) DEFAULT NULL,
  `sup_remarks` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `testcaseparm`
--

CREATE TABLE IF NOT EXISTS `testcaseparm` (
  `id` int(11) NOT NULL,
  `parameter` varchar(255) NOT NULL,
  `instanceformid` varchar(255) NOT NULL,
  `value` varchar(1000) NOT NULL,
  `remark` varchar(250) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tester`
--

CREATE TABLE IF NOT EXISTS `tester` (
  `id` int(11) NOT NULL,
  `testerid` int(11) NOT NULL,
  `testercode` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `testname`
--

CREATE TABLE IF NOT EXISTS `testname` (
  `id` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `code` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `testnameparm`
--

CREATE TABLE IF NOT EXISTS `testnameparm` (
  `id` int(11) NOT NULL,
  `parameter` varchar(255) NOT NULL,
  `testnameid` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `testusers`
--

CREATE TABLE IF NOT EXISTS `testusers` (
  `guid` int(11) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `timesheet_clients`
--

CREATE TABLE IF NOT EXISTS `timesheet_clients` (
  `id` int(111) NOT NULL,
  `orgid` int(11) NOT NULL DEFAULT '1',
  `client_name` varchar(100) NOT NULL DEFAULT 'VA',
  `show_sla_process` tinyint(4) DEFAULT NULL,
  `show_days_out` tinyint(4) DEFAULT NULL,
  `show_tat` tinyint(4) DEFAULT NULL,
  `show_error` tinyint(4) DEFAULT NULL,
  `ryg_matrix` tinyint(4) DEFAULT NULL,
  `show_import` int(11) NOT NULL DEFAULT '0',
  `show_startstop` int(11) NOT NULL DEFAULT '0',
  `validation_check` int(11) NOT NULL DEFAULT '1',
  `view_dashboard` int(11) NOT NULL DEFAULT '1',
  `managerdashboard` varchar(1000) DEFAULT NULL,
  `clientdashboard` varchar(1000) DEFAULT NULL,
  `appreciation` int(11) NOT NULL DEFAULT '0',
  `appreciationlink` varchar(1000) DEFAULT NULL,
  `errorlink` varchar(1000) DEFAULT NULL,
  `rcalink` varchar(1000) DEFAULT NULL,
  `eventlink` varchar(1000) DEFAULT NULL,
  `show_daysout_trend` int(11) DEFAULT NULL,
  `view_received` int(11) DEFAULT '1',
  `view_sla_popup` tinyint(1) DEFAULT NULL,
  `daily_trend_sla` int(10) NOT NULL DEFAULT '0',
  `daily_error_sla` int(10) NOT NULL DEFAULT '0',
  `monthly_trend_sla` int(10) NOT NULL DEFAULT '0',
  `monthly_error_sla` int(10) NOT NULL DEFAULT '0',
  `daysout_trend_sla` int(10) NOT NULL DEFAULT '0',
  `matrix_y_label` varchar(100) NOT NULL DEFAULT 'Files',
  `matrix_qc_option` int(1) NOT NULL DEFAULT '0',
  `auditlink` varchar(1000) NOT NULL,
  `show_hours` tinyint(4) DEFAULT '0',
  `skip_adding` int(4) NOT NULL DEFAULT '0',
  `file_upload` tinyint(4) NOT NULL DEFAULT '0',
  `show_trending` tinyint(1) NOT NULL DEFAULT '1',
  `sequence` int(10) DEFAULT '1',
  `disable_edit` int(1) NOT NULL DEFAULT '0',
  `addinfo_trend` int(1) NOT NULL DEFAULT '0',
  `timesheet_type` varchar(100) DEFAULT NULL,
  `show_calendar` tinyint(1) DEFAULT '0',
  `project_status` varchar(30) NOT NULL,
  `comment_shortcut` varchar(1000) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `timesheet_client_sla`
--

CREATE TABLE IF NOT EXISTS `timesheet_client_sla` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `role` varchar(100) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `process` int(11) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `redlowlimit` int(11) NOT NULL,
  `redhighlimit` int(11) NOT NULL,
  `yellowlowlimit` int(11) NOT NULL,
  `yellowhighlimit` int(11) NOT NULL,
  `greenlowlimit` int(11) NOT NULL,
  `greenhighlimit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `timesheet_cost`
--

CREATE TABLE IF NOT EXISTS `timesheet_cost` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `client` int(11) DEFAULT NULL,
  `process` int(11) DEFAULT NULL,
  `cost` float DEFAULT NULL,
  `cost_quality` float DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `timesheet_dropdown1`
--

CREATE TABLE IF NOT EXISTS `timesheet_dropdown1` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `field_name` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `timesheet_dropdown2`
--

CREATE TABLE IF NOT EXISTS `timesheet_dropdown2` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `field_name` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `timesheet_dropdown3`
--

CREATE TABLE IF NOT EXISTS `timesheet_dropdown3` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `field_name` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `timesheet_dropdown4`
--

CREATE TABLE IF NOT EXISTS `timesheet_dropdown4` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `field_name` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `timesheet_dropdown5`
--

CREATE TABLE IF NOT EXISTS `timesheet_dropdown5` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `field_name` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `timesheet_fields`
--

CREATE TABLE IF NOT EXISTS `timesheet_fields` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `field_id` varchar(100) NOT NULL,
  `field_name` varchar(100) NOT NULL,
  `field_type` varchar(100) DEFAULT NULL,
  `field_format` varchar(100) DEFAULT NULL,
  `sequence` int(11) NOT NULL DEFAULT '0',
  `required` tinyint(1) NOT NULL DEFAULT '1',
  `field_formula` varchar(1000) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `timesheet_fields_mapper`
--

CREATE TABLE IF NOT EXISTS `timesheet_fields_mapper` (
  `id` int(11) NOT NULL,
  `timesheet_tablename` varchar(100) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `field_id` int(11) NOT NULL,
  `field_value_id` varchar(1000) DEFAULT NULL,
  `field_value` int(11) DEFAULT NULL,
  `clientid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `timesheet_lob`
--

CREATE TABLE IF NOT EXISTS `timesheet_lob` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `field_name` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `timesheet_process`
--

CREATE TABLE IF NOT EXISTS `timesheet_process` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `field_name` varchar(100) NOT NULL,
  `default_value` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `timesheet_project`
--

CREATE TABLE IF NOT EXISTS `timesheet_project` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `field_name` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `timesheet_status`
--

CREATE TABLE IF NOT EXISTS `timesheet_status` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `field_name` varchar(100) NOT NULL,
  `field_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `timesheet_type`
--

CREATE TABLE IF NOT EXISTS `timesheet_type` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `fieldname` varchar(100) NOT NULL,
  `fieldtext` varchar(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `training_app_data`
--

CREATE TABLE IF NOT EXISTS `training_app_data` (
  `id` int(11) NOT NULL,
  `name` varchar(1000) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `formid` int(11) NOT NULL,
  `assignedto` int(11) DEFAULT NULL,
  `createdid` int(11) DEFAULT NULL,
  `startdate` varchar(1000) DEFAULT NULL,
  `nextactiondate` varchar(1000) DEFAULT NULL,
  `enddate` varchar(1000) DEFAULT NULL,
  `region` varchar(1000) DEFAULT NULL,
  `centre` varchar(1000) DEFAULT NULL,
  `batchcode` varchar(1000) DEFAULT NULL,
  `rollno` varchar(1000) DEFAULT NULL,
  `Type` varchar(1000) DEFAULT NULL,
  `sprint` varchar(1000) DEFAULT NULL,
  `teachers` varchar(1000) DEFAULT NULL,
  `grade` varchar(1000) DEFAULT NULL,
  `date` varchar(1000) DEFAULT NULL,
  `trackerupdate` varchar(1000) DEFAULT NULL,
  `schedule_conformance` varchar(1000) DEFAULT NULL,
  `attendance` varchar(1000) DEFAULT NULL,
  `assignment` varchar(1000) DEFAULT NULL,
  `dormitory` varchar(1000) DEFAULT NULL,
  `lastsprintscore` varchar(1000) DEFAULT NULL,
  `lastsprintstatus` varchar(1000) DEFAULT NULL,
  `Tollgate` varchar(1000) DEFAULT NULL,
  `tollgatestatus` varchar(1000) DEFAULT NULL,
  `MOP` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transients`
--

CREATE TABLE IF NOT EXISTS `transients` (
  `id` int(9) NOT NULL,
  `handle` varchar(200) NOT NULL,
  `expires` timestamp NULL DEFAULT NULL,
  `value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE IF NOT EXISTS `type` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(20) CHARACTER SET utf8 NOT NULL,
  `text` varchar(30) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) unsigned NOT NULL,
  `join_date` timestamp NULL DEFAULT NULL,
  `last_visit` timestamp NULL DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(120) NOT NULL,
  `salt` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `guid` int(2) NOT NULL,
  `name` varchar(300) NOT NULL,
  `lastactivity` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_contact`
--

CREATE TABLE IF NOT EXISTS `user_contact` (
  `id` int(11) unsigned NOT NULL,
  `userid` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `webpivottable`
--

CREATE TABLE IF NOT EXISTS `webpivottable` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `orgid` int(11) NOT NULL,
  `formid` int(11) NOT NULL,
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `workflow`
--

CREATE TABLE IF NOT EXISTS `workflow` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `instanceformid` int(11) DEFAULT NULL,
  `formid` int(11) DEFAULT NULL,
  `orgid` int(11) NOT NULL,
  `moduleid` int(11) DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  `sentinel` tinyint(1) NOT NULL DEFAULT '0',
  `single_stage` tinyint(1) NOT NULL DEFAULT '0',
  `type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `workflow_elements`
--

CREATE TABLE IF NOT EXISTS `workflow_elements` (
  `id` int(11) NOT NULL,
  `workflowstageid` int(11) NOT NULL,
  `flow_action` varchar(250) NOT NULL,
  `field` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL,
  `expression` smallint(6) DEFAULT NULL,
  `condition` varchar(250) DEFAULT NULL,
  `custom_method` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `workflow_steps`
--

CREATE TABLE IF NOT EXISTS `workflow_steps` (
  `id` int(11) NOT NULL,
  `workflowid` int(11) NOT NULL,
  `statusid` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `customclass` varchar(100) DEFAULT NULL,
  `keyid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `xflat_config`
--

CREATE TABLE IF NOT EXISTS `xflat_config` (
  `id` int(11) NOT NULL,
  `name` varchar(10000) NOT NULL,
  `instanceformid` int(11) NOT NULL,
  `basicconfig` varchar(1000) DEFAULT NULL COMMENT 'first box in the congif',
  `dropdownconfig` varchar(10000) DEFAULT NULL COMMENT 'multiple select values',
  `red_avatar_trigger` varchar(10000) DEFAULT NULL COMMENT '_a=>avatars _g=>groups',
  `yellow_avatar_trigger` varchar(10000) DEFAULT NULL COMMENT '_a=>avatars _g=>groups',
  `green_avatar_trigger` varchar(10000) DEFAULT NULL COMMENT '_a=>avatars _g=>groups',
  `targets` text COMMENT 'red,yellow,green,(1=>Daily|2=>Weekly|3=>Monthly|4=>Quarter)',
  `link` varchar(10000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `active_brokers`
--
ALTER TABLE `active_brokers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_menus`
--
ALTER TABLE `admin_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_menu_access`
--
ALTER TABLE `admin_menu_access`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admission_primary_physician`
--
ALTER TABLE `admission_primary_physician`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admission_referring_physician`
--
ALTER TABLE `admission_referring_physician`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admission_values`
--
ALTER TABLE `admission_values`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admission_watchlist`
--
ALTER TABLE `admission_watchlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agora_times`
--
ALTER TABLE `agora_times`
  ADD PRIMARY KEY (`Sl_No`);

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `api_data`
--
ALTER TABLE `api_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_usage`
--
ALTER TABLE `app_usage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `avatarid` (`avatarid`);

--
-- Indexes for table `archives`
--
ALTER TABLE `archives`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `archives_master`
--
ALTER TABLE `archives_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assesment_avatarscore`
--
ALTER TABLE `assesment_avatarscore`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assesment_category`
--
ALTER TABLE `assesment_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assesment_category_groups`
--
ALTER TABLE `assesment_category_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assesment_list`
--
ALTER TABLE `assesment_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assesment_questions`
--
ALTER TABLE `assesment_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assesmentid` (`assesmentid`);

--
-- Indexes for table `assesment_summary`
--
ALTER TABLE `assesment_summary`
  ADD PRIMARY KEY (`id`),
  ADD KEY `avatarid` (`avatarid`);

--
-- Indexes for table `attachmentlogger`
--
ALTER TABLE `attachmentlogger`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auditlog`
--
ALTER TABLE `auditlog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `avatarid` (`avatarid`,`groupid`,`instanceformid`),
  ADD KEY `modifieddate` (`modifieddate`),
  ADD KEY `Instance` (`instanceformid`);

--
-- Indexes for table `auditlog_club`
--
ALTER TABLE `auditlog_club`
  ADD PRIMARY KEY (`id`),
  ADD KEY `avatarid` (`avatarid`);

--
-- Indexes for table `avatars`
--
ALTER TABLE `avatars`
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `users_username_idx` (`username`),
  ADD KEY `email` (`email`),
  ADD KEY `orgid` (`orgid`),
  ADD KEY `status` (`status`),
  ADD FULLTEXT KEY `name` (`name`,`firstname`,`lastname`,`email`,`address`,`phone`);

--
-- Indexes for table `avatars_alerts`
--
ALTER TABLE `avatars_alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `avatarid` (`avatarid`),
  ADD KEY `acknowledged` (`acknowledged`);

--
-- Indexes for table `avatars_app`
--
ALTER TABLE `avatars_app`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `avatars_menus`
--
ALTER TABLE `avatars_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `avatars_modules`
--
ALTER TABLE `avatars_modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `avatars_tiles`
--
ALTER TABLE `avatars_tiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `avatar_alert_verfication`
--
ALTER TABLE `avatar_alert_verfication`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `avatar_api`
--
ALTER TABLE `avatar_api`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `avatar_flags`
--
ALTER TABLE `avatar_flags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `avatarid` (`avatarid`),
  ADD KEY `flag` (`flag`);

--
-- Indexes for table `avatar_instanceform_mapper`
--
ALTER TABLE `avatar_instanceform_mapper`
  ADD PRIMARY KEY (`avatarid`,`instanceformid`,`type`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clientmap_address`
--
ALTER TABLE `clientmap_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_avatar`
--
ALTER TABLE `client_avatar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clubinvoice`
--
ALTER TABLE `clubinvoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `club_client_target`
--
ALTER TABLE `club_client_target`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `club_dashboard_menu`
--
ALTER TABLE `club_dashboard_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `club_kra`
--
ALTER TABLE `club_kra`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `club_kra_sla`
--
ALTER TABLE `club_kra_sla`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `club_matrix_admin`
--
ALTER TABLE `club_matrix_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `club_matrix_filter`
--
ALTER TABLE `club_matrix_filter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `club_matrix_graph_type`
--
ALTER TABLE `club_matrix_graph_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `club_matrix_types`
--
ALTER TABLE `club_matrix_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `club_task`
--
ALTER TABLE `club_task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`,`avatar_id`),
  ADD KEY `avatar_id` (`avatar_id`),
  ADD KEY `client` (`client`);

--
-- Indexes for table `club_task_pause`
--
ALTER TABLE `club_task_pause`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `club_timesheet_comments`
--
ALTER TABLE `club_timesheet_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `club_timesheet_daysout`
--
ALTER TABLE `club_timesheet_daysout`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `club_timesheet_in_out`
--
ALTER TABLE `club_timesheet_in_out`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `club_timesheet_in_out_cron`
--
ALTER TABLE `club_timesheet_in_out_cron`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `club_timesheet_mapper`
--
ALTER TABLE `club_timesheet_mapper`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cometchat`
--
ALTER TABLE `cometchat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `to` (`to`),
  ADD KEY `from` (`from`),
  ADD KEY `direction` (`direction`),
  ADD KEY `read` (`read`),
  ADD KEY `sent` (`sent`);

--
-- Indexes for table `cometchat_announcements`
--
ALTER TABLE `cometchat_announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `to` (`to`),
  ADD KEY `time` (`time`),
  ADD KEY `to_id` (`to`,`id`);

--
-- Indexes for table `cometchat_block`
--
ALTER TABLE `cometchat_block`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fromid` (`fromid`),
  ADD KEY `toid` (`toid`),
  ADD KEY `fromid_toid` (`fromid`,`toid`);

--
-- Indexes for table `cometchat_chatroommessages`
--
ALTER TABLE `cometchat_chatroommessages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`),
  ADD KEY `chatroomid` (`chatroomid`),
  ADD KEY `sent` (`sent`);

--
-- Indexes for table `cometchat_chatrooms`
--
ALTER TABLE `cometchat_chatrooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lastactivity` (`lastactivity`),
  ADD KEY `createdby` (`createdby`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `cometchat_chatrooms_users`
--
ALTER TABLE `cometchat_chatrooms_users`
  ADD PRIMARY KEY (`userid`,`chatroomid`) USING BTREE,
  ADD KEY `chatroomid` (`chatroomid`),
  ADD KEY `lastactivity` (`lastactivity`),
  ADD KEY `userid` (`userid`),
  ADD KEY `userid_chatroomid` (`chatroomid`,`userid`);

--
-- Indexes for table `cometchat_comethistory`
--
ALTER TABLE `cometchat_comethistory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel` (`channel`),
  ADD KEY `sent` (`sent`),
  ADD KEY `channel_sent` (`channel`,`sent`);

--
-- Indexes for table `cometchat_games`
--
ALTER TABLE `cometchat_games`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `cometchat_guests`
--
ALTER TABLE `cometchat_guests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lastactivity` (`lastactivity`);

--
-- Indexes for table `cometchat_messages_old`
--
ALTER TABLE `cometchat_messages_old`
  ADD PRIMARY KEY (`id`),
  ADD KEY `to` (`to`),
  ADD KEY `from` (`from`),
  ADD KEY `direction` (`direction`),
  ADD KEY `read` (`read`),
  ADD KEY `sent` (`sent`);

--
-- Indexes for table `cometchat_status`
--
ALTER TABLE `cometchat_status`
  ADD PRIMARY KEY (`userid`),
  ADD KEY `typingto` (`typingto`),
  ADD KEY `typingtime` (`typingtime`);

--
-- Indexes for table `cometchat_videochatsessions`
--
ALTER TABLE `cometchat_videochatsessions`
  ADD PRIMARY KEY (`username`),
  ADD KEY `username` (`username`),
  ADD KEY `identity` (`identity`),
  ADD KEY `timestamp` (`timestamp`);

--
-- Indexes for table `cominvoice`
--
ALTER TABLE `cominvoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `avatarid` (`avatarid`),
  ADD KEY `date_modified` (`date_modified`);

--
-- Indexes for table `comphotos`
--
ALTER TABLE `comphotos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instanceformid` (`instanceformid`);

--
-- Indexes for table `configurations`
--
ALTER TABLE `configurations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orgid` (`orgid`),
  ADD KEY `parameter` (`parameter`);

--
-- Indexes for table `countdetails`
--
ALTER TABLE `countdetails`
  ADD PRIMARY KEY (`avatarid`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `css`
--
ALTER TABLE `css`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `customlistfields`
--
ALTER TABLE `customlistfields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customlistviews`
--
ALTER TABLE `customlistviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_login`
--
ALTER TABLE `custom_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `daysout`
--
ALTER TABLE `daysout`
  ADD PRIMARY KEY (`Slno`);

--
-- Indexes for table `demotracker`
--
ALTER TABLE `demotracker`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dependencies`
--
ALTER TABLE `dependencies`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emailaccounts`
--
ALTER TABLE `emailaccounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emailconfig`
--
ALTER TABLE `emailconfig`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emailheaders`
--
ALTER TABLE `emailheaders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_cache`
--
ALTER TABLE `email_cache`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `IDX_USER_EMAIL_FOLDER` (`userid`,`email`,`folder`,`uid`),
  ADD KEY `idx_datetime` (`datetime`),
  ADD KEY `idx_cc` (`cc`(255)),
  ADD KEY `idx_from` (`_from`),
  ADD KEY `idx_subject` (`_subject`(255)),
  ADD KEY `idx_to` (`_to`(255));

--
-- Indexes for table `email_setting`
--
ALTER TABLE `email_setting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_userid` (`userid`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employeeformseq`
--
ALTER TABLE `employeeformseq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `errorlog`
--
ALTER TABLE `errorlog`
  ADD PRIMARY KEY (`policynumber`);

--
-- Indexes for table `escalations`
--
ALTER TABLE `escalations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instanceformid` (`instanceformid`);

--
-- Indexes for table `evolve_avatarformseq`
--
ALTER TABLE `evolve_avatarformseq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evolve_avatars`
--
ALTER TABLE `evolve_avatars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evolve_declined`
--
ALTER TABLE `evolve_declined`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evolve_export`
--
ALTER TABLE `evolve_export`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evolve_exportfields`
--
ALTER TABLE `evolve_exportfields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evolve_instanceseq`
--
ALTER TABLE `evolve_instanceseq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evolve_instanceseq_history`
--
ALTER TABLE `evolve_instanceseq_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evolve_metafields`
--
ALTER TABLE `evolve_metafields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evolve_metaforms`
--
ALTER TABLE `evolve_metaforms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evolve_organizations`
--
ALTER TABLE `evolve_organizations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evolve_premiumcalculations`
--
ALTER TABLE `evolve_premiumcalculations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evolve_premiumlookup`
--
ALTER TABLE `evolve_premiumlookup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evolve_question_values`
--
ALTER TABLE `evolve_question_values`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evolve_question_values_history`
--
ALTER TABLE `evolve_question_values_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evolve_signatures`
--
ALTER TABLE `evolve_signatures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evolve_wizards`
--
ALTER TABLE `evolve_wizards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `executive_desktop`
--
ALTER TABLE `executive_desktop`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instanceform` (`instanceform`);

--
-- Indexes for table `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instanceformid` (`instanceformid`);

--
-- Indexes for table `export_fields`
--
ALTER TABLE `export_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facilities_equipments`
--
ALTER TABLE `facilities_equipments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fields`
--
ALTER TABLE `fields`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ix_name` (`name`);

--
-- Indexes for table `fieldstodeleteinmultipleform`
--
ALTER TABLE `fieldstodeleteinmultipleform`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `formcomments`
--
ALTER TABLE `formcomments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instanceformid` (`instanceformid`);

--
-- Indexes for table `form_menu`
--
ALTER TABLE `form_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`),
  ADD KEY `avatarid` (`avatarid`);

--
-- Indexes for table `game_points`
--
ALTER TABLE `game_points`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gmail`
--
ALTER TABLE `gmail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `goal_label`
--
ALTER TABLE `goal_label`
  ADD PRIMARY KEY (`id`),
  ADD KEY `org_role_id` (`org_role_id`);

--
-- Indexes for table `golfmap_avatarcourses`
--
ALTER TABLE `golfmap_avatarcourses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `golfmap_courses`
--
ALTER TABLE `golfmap_courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `googlelogin`
--
ALTER TABLE `googlelogin`
  ADD PRIMARY KEY (`avatarid`),
  ADD UNIQUE KEY `emailid` (`emailid`);

--
-- Indexes for table `google_gcm`
--
ALTER TABLE `google_gcm`
  ADD PRIMARY KEY (`gcm_registration_id`);

--
-- Indexes for table `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orgid` (`orgid`),
  ADD KEY `moduleid` (`moduleid`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orgid` (`orgid`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `groups_alerts`
--
ALTER TABLE `groups_alerts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups_avatars`
--
ALTER TABLE `groups_avatars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `avatarid` (`avatarid`);

--
-- Indexes for table `groups_brokers`
--
ALTER TABLE `groups_brokers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups_managers`
--
ALTER TABLE `groups_managers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `avatarid` (`managerid`);

--
-- Indexes for table `groups_modules`
--
ALTER TABLE `groups_modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `groupid` (`groupid`);

--
-- Indexes for table `groups_tiles`
--
ALTER TABLE `groups_tiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_timesheet_clients`
--
ALTER TABLE `group_timesheet_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `help_tour`
--
ALTER TABLE `help_tour`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ikra`
--
ALTER TABLE `ikra`
  ADD PRIMARY KEY (`ikraid`),
  ADD KEY `id` (`avatarid`);

--
-- Indexes for table `ikradaily`
--
ALTER TABLE `ikradaily`
  ADD PRIMARY KEY (`id`),
  ADD KEY `avatarid` (`avatarid`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `image_tags`
--
ALTER TABLE `image_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `import_logs`
--
ALTER TABLE `import_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instancefieldbigtext`
--
ALTER TABLE `instancefieldbigtext`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instancefields`
--
ALTER TABLE `instancefields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `avatarid` (`avatarid`,`instanceformid`),
  ADD KEY `fieldid` (`fieldid`,`instanceformid`),
  ADD KEY `instanceformid` (`instanceformid`),
  ADD KEY `orgid` (`orgid`),
  ADD KEY `instanceformid_2` (`instanceformid`);

--
-- Indexes for table `instanceforms`
--
ALTER TABLE `instanceforms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orgid` (`orgid`,`parentinstformid`),
  ADD KEY `parentinstformid` (`parentinstformid`),
  ADD KEY `formid` (`formid`),
  ADD KEY `date_created` (`date_created`),
  ADD FULLTEXT KEY `description` (`description`,`name`);

--
-- Indexes for table `instanceforms_join`
--
ALTER TABLE `instanceforms_join`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instanceformid` (`instanceformid`);

--
-- Indexes for table `instforms_files`
--
ALTER TABLE `instforms_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messageid` (`messageid`),
  ADD KEY `instanceformid` (`instanceformid`);

--
-- Indexes for table `instforms_files_tmp`
--
ALTER TABLE `instforms_files_tmp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messageid` (`avatarid`),
  ADD KEY `instanceformid` (`formid`);

--
-- Indexes for table `instforms_groups`
--
ALTER TABLE `instforms_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instformid` (`instformid`);

--
-- Indexes for table `instforms_links`
--
ALTER TABLE `instforms_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instanceformid` (`instanceformid`);

--
-- Indexes for table `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_job_tracker_type` (`job_tracker`,`job_type`(255)),
  ADD KEY `idx_job_status` (`job_status`),
  ADD KEY `idx_job_in_progress` (`is_job_in_progress`);

--
-- Indexes for table `kra_instanceform`
--
ALTER TABLE `kra_instanceform`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `layer_animations`
--
ALTER TABLE `layer_animations`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `leaderboard`
--
ALTER TABLE `leaderboard`
  ADD PRIMARY KEY (`avatarid`);

--
-- Indexes for table `leaderboard_log`
--
ALTER TABLE `leaderboard_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `avatarid` (`avatarid`),
  ADD KEY `commentid` (`commentid`);

--
-- Indexes for table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loss_run_client`
--
ALTER TABLE `loss_run_client`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loss_run_overall`
--
ALTER TABLE `loss_run_overall`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mapping_table`
--
ALTER TABLE `mapping_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_flags`
--
ALTER TABLE `master_flags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `matrix_config`
--
ALTER TABLE `matrix_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `matrix_days`
--
ALTER TABLE `matrix_days`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `matrix_months`
--
ALTER TABLE `matrix_months`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `matrix_quarter`
--
ALTER TABLE `matrix_quarter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `matrix_years`
--
ALTER TABLE `matrix_years`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus_avatars`
--
ALTER TABLE `menus_avatars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fromid` (`fromid`),
  ADD KEY `instanceformid` (`instanceformid`);

--
-- Indexes for table `message_attachments`
--
ALTER TABLE `message_attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message_recepients`
--
ALTER TABLE `message_recepients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messageid` (`messageid`),
  ADD KEY `toid_status` (`toid`,`status`);

--
-- Indexes for table `metafields`
--
ALTER TABLE `metafields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `formid` (`formid`),
  ADD KEY `name` (`name`),
  ADD KEY `options` (`options`(500));

--
-- Indexes for table `metaforms`
--
ALTER TABLE `metaforms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orgid` (`orgid`),
  ADD KEY `moduleid` (`moduleid`);

--
-- Indexes for table `metaform_fieldorder`
--
ALTER TABLE `metaform_fieldorder`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `metalist`
--
ALTER TABLE `metalist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `metapdffields`
--
ALTER TABLE `metapdffields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `metapdfforms`
--
ALTER TABLE `metapdfforms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `metareportfields`
--
ALTER TABLE `metareportfields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `metareports`
--
ALTER TABLE `metareports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `metastatus`
--
ALTER TABLE `metastatus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Composite` (`formid`,`orgid`,`statusvalue`);

--
-- Indexes for table `meta_import`
--
ALTER TABLE `meta_import`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meta_importcolumns`
--
ALTER TABLE `meta_importcolumns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meta_multiselect`
--
ALTER TABLE `meta_multiselect`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `mletquestions`
--
ALTER TABLE `mletquestions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modulecategories`
--
ALTER TABLE `modulecategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `module_map_category`
--
ALTER TABLE `module_map_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `navigations`
--
ALTER TABLE `navigations`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `non_compliance`
--
ALTER TABLE `non_compliance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth2_setting`
--
ALTER TABLE `oauth2_setting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_userid` (`userid`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_provider` (`provider`);

--
-- Indexes for table `observers`
--
ALTER TABLE `observers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offboarding_audit`
--
ALTER TABLE `offboarding_audit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `operatingrhythm`
--
ALTER TABLE `operatingrhythm`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ownergroupid` (`groupid`);

--
-- Indexes for table `operatingrhythm_avatars`
--
ALTER TABLE `operatingrhythm_avatars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `operatingrhythm_groups`
--
ALTER TABLE `operatingrhythm_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `orgclientlist`
--
ALTER TABLE `orgclientlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orgs_modules`
--
ALTER TABLE `orgs_modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orgs_partners`
--
ALTER TABLE `orgs_partners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `org_role`
--
ALTER TABLE `org_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `or_attendees`
--
ALTER TABLE `or_attendees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `or_meta`
--
ALTER TABLE `or_meta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oxmedia_devices`
--
ALTER TABLE `oxmedia_devices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oxmedia_devices_sliders`
--
ALTER TABLE `oxmedia_devices_sliders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `avatarid` (`deviceid`);

--
-- Indexes for table `oxmedia_playlist`
--
ALTER TABLE `oxmedia_playlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oxmedia_slides`
--
ALTER TABLE `oxmedia_slides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ox_alert`
--
ALTER TABLE `ox_alert`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ox_alert_group_mapper`
--
ALTER TABLE `ox_alert_group_mapper`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ox_announcement`
--
ALTER TABLE `ox_announcement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ox_announcement_group_mapper`
--
ALTER TABLE `ox_announcement_group_mapper`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `podview`
--
ALTER TABLE `podview`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `poll_answers`
--
ALTER TABLE `poll_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `poll_questions`
--
ALTER TABLE `poll_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`prid`);

--
-- Indexes for table `queries`
--
ALTER TABLE `queries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `query_config`
--
ALTER TABLE `query_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questionqueries`
--
ALTER TABLE `questionqueries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD FULLTEXT KEY `questiontext` (`questiontext`);

--
-- Indexes for table `relationshipinstance`
--
ALTER TABLE `relationshipinstance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `relationships`
--
ALTER TABLE `relationships`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rpt_cluster4`
--
ALTER TABLE `rpt_cluster4`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rpt_data`
--
ALTER TABLE `rpt_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rpt_volvo`
--
ALTER TABLE `rpt_volvo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rules`
--
ALTER TABLE `rules`
  ADD PRIMARY KEY (`idrules`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`Date`,`KeyMetrics`);

--
-- Indexes for table `sales_pipeline_kra`
--
ALTER TABLE `sales_pipeline_kra`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `last_activity_idx` (`last_activity`);

--
-- Indexes for table `sla`
--
ALTER TABLE `sla`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `slides`
--
ALTER TABLE `slides`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `spreadsheet_mapper`
--
ALTER TABLE `spreadsheet_mapper`
  ADD PRIMARY KEY (`spreadsheetid`);

--
-- Indexes for table `srf`
--
ALTER TABLE `srf`
  ADD PRIMARY KEY (`srfid`);

--
-- Indexes for table `static_slides`
--
ALTER TABLE `static_slides`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `stattracker`
--
ALTER TABLE `stattracker`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `statusboxes`
--
ALTER TABLE `statusboxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sticky_notes`
--
ALTER TABLE `sticky_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_projects`
--
ALTER TABLE `sub_projects`
  ADD PRIMARY KEY (`sprid`);

--
-- Indexes for table `supportservice`
--
ALTER TABLE `supportservice`
  ADD PRIMARY KEY (`keyID`);

--
-- Indexes for table `table_statesave`
--
ALTER TABLE `table_statesave`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testcase`
--
ALTER TABLE `testcase`
  ADD PRIMARY KEY (`id`),
  ADD KEY `testname_index` (`testname`),
  ADD KEY `assigto_index` (`assignedto`);

--
-- Indexes for table `testcaseparm`
--
ALTER TABLE `testcaseparm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testname`
--
ALTER TABLE `testname`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name_index` (`name`);

--
-- Indexes for table `testnameparm`
--
ALTER TABLE `testnameparm`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parameter_index` (`parameter`),
  ADD KEY `code_index` (`testnameid`);

--
-- Indexes for table `testusers`
--
ALTER TABLE `testusers`
  ADD PRIMARY KEY (`guid`);

--
-- Indexes for table `timesheet_clients`
--
ALTER TABLE `timesheet_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timesheet_client_sla`
--
ALTER TABLE `timesheet_client_sla`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timesheet_cost`
--
ALTER TABLE `timesheet_cost`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timesheet_dropdown1`
--
ALTER TABLE `timesheet_dropdown1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timesheet_dropdown2`
--
ALTER TABLE `timesheet_dropdown2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timesheet_dropdown3`
--
ALTER TABLE `timesheet_dropdown3`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timesheet_dropdown4`
--
ALTER TABLE `timesheet_dropdown4`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timesheet_dropdown5`
--
ALTER TABLE `timesheet_dropdown5`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timesheet_fields`
--
ALTER TABLE `timesheet_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timesheet_fields_mapper`
--
ALTER TABLE `timesheet_fields_mapper`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timesheet_lob`
--
ALTER TABLE `timesheet_lob`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timesheet_process`
--
ALTER TABLE `timesheet_process`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timesheet_project`
--
ALTER TABLE `timesheet_project`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timesheet_status`
--
ALTER TABLE `timesheet_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timesheet_type`
--
ALTER TABLE `timesheet_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `training_app_data`
--
ALTER TABLE `training_app_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transients`
--
ALTER TABLE `transients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_uk` (`username`),
  ADD UNIQUE KEY `email_uk` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`guid`);

--
-- Indexes for table `user_contact`
--
ALTER TABLE `user_contact`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `IDX_USER_CONTACT_EMAIL` (`userid`,`email`),
  ADD KEY `idx_userid` (`userid`);

--
-- Indexes for table `webpivottable`
--
ALTER TABLE `webpivottable`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workflow`
--
ALTER TABLE `workflow`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workflow_elements`
--
ALTER TABLE `workflow_elements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workflow_steps`
--
ALTER TABLE `workflow_steps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `xflat_config`
--
ALTER TABLE `xflat_config`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `active_brokers`
--
ALTER TABLE `active_brokers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(13) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `admin_menus`
--
ALTER TABLE `admin_menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `admin_menu_access`
--
ALTER TABLE `admin_menu_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `admission_primary_physician`
--
ALTER TABLE `admission_primary_physician`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `admission_referring_physician`
--
ALTER TABLE `admission_referring_physician`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `admission_values`
--
ALTER TABLE `admission_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `admission_watchlist`
--
ALTER TABLE `admission_watchlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `api_data`
--
ALTER TABLE `api_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `app_usage`
--
ALTER TABLE `app_usage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `archives`
--
ALTER TABLE `archives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `archives_master`
--
ALTER TABLE `archives_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `assesment_avatarscore`
--
ALTER TABLE `assesment_avatarscore`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `assesment_category`
--
ALTER TABLE `assesment_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `assesment_category_groups`
--
ALTER TABLE `assesment_category_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `assesment_list`
--
ALTER TABLE `assesment_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `assesment_questions`
--
ALTER TABLE `assesment_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `assesment_summary`
--
ALTER TABLE `assesment_summary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `attachmentlogger`
--
ALTER TABLE `attachmentlogger`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `auditlog`
--
ALTER TABLE `auditlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `auditlog_club`
--
ALTER TABLE `auditlog_club`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `avatars`
--
ALTER TABLE `avatars`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `avatars_alerts`
--
ALTER TABLE `avatars_alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `avatars_app`
--
ALTER TABLE `avatars_app`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `avatars_menus`
--
ALTER TABLE `avatars_menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `avatars_modules`
--
ALTER TABLE `avatars_modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `avatars_tiles`
--
ALTER TABLE `avatars_tiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `avatar_alert_verfication`
--
ALTER TABLE `avatar_alert_verfication`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `avatar_api`
--
ALTER TABLE `avatar_api`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `avatar_flags`
--
ALTER TABLE `avatar_flags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `clientmap_address`
--
ALTER TABLE `clientmap_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `client_avatar`
--
ALTER TABLE `client_avatar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `clubinvoice`
--
ALTER TABLE `clubinvoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `club_client_target`
--
ALTER TABLE `club_client_target`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `club_kra`
--
ALTER TABLE `club_kra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `club_kra_sla`
--
ALTER TABLE `club_kra_sla`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `club_matrix_admin`
--
ALTER TABLE `club_matrix_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `club_matrix_filter`
--
ALTER TABLE `club_matrix_filter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `club_matrix_graph_type`
--
ALTER TABLE `club_matrix_graph_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `club_matrix_types`
--
ALTER TABLE `club_matrix_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `club_task`
--
ALTER TABLE `club_task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `club_task_pause`
--
ALTER TABLE `club_task_pause`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `club_timesheet_comments`
--
ALTER TABLE `club_timesheet_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `club_timesheet_daysout`
--
ALTER TABLE `club_timesheet_daysout`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `club_timesheet_in_out`
--
ALTER TABLE `club_timesheet_in_out`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `club_timesheet_in_out_cron`
--
ALTER TABLE `club_timesheet_in_out_cron`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `club_timesheet_mapper`
--
ALTER TABLE `club_timesheet_mapper`
  MODIFY `id` int(13) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cometchat`
--
ALTER TABLE `cometchat`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cometchat_announcements`
--
ALTER TABLE `cometchat_announcements`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cometchat_block`
--
ALTER TABLE `cometchat_block`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cometchat_chatroommessages`
--
ALTER TABLE `cometchat_chatroommessages`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cometchat_chatrooms`
--
ALTER TABLE `cometchat_chatrooms`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cometchat_comethistory`
--
ALTER TABLE `cometchat_comethistory`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cometchat_guests`
--
ALTER TABLE `cometchat_guests`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cometchat_messages_old`
--
ALTER TABLE `cometchat_messages_old`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cominvoice`
--
ALTER TABLE `cominvoice`
  MODIFY `id` int(150) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `comphotos`
--
ALTER TABLE `comphotos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `configurations`
--
ALTER TABLE `configurations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `css`
--
ALTER TABLE `css`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customlistfields`
--
ALTER TABLE `customlistfields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customlistviews`
--
ALTER TABLE `customlistviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `custom_login`
--
ALTER TABLE `custom_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `daysout`
--
ALTER TABLE `daysout`
  MODIFY `Slno` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `demotracker`
--
ALTER TABLE `demotracker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dependencies`
--
ALTER TABLE `dependencies`
  MODIFY `Id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `emailaccounts`
--
ALTER TABLE `emailaccounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `emailconfig`
--
ALTER TABLE `emailconfig`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `emailheaders`
--
ALTER TABLE `emailheaders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `email_cache`
--
ALTER TABLE `email_cache`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `email_setting`
--
ALTER TABLE `email_setting`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employeeformseq`
--
ALTER TABLE `employeeformseq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `escalations`
--
ALTER TABLE `escalations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `evolve_avatarformseq`
--
ALTER TABLE `evolve_avatarformseq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `evolve_avatars`
--
ALTER TABLE `evolve_avatars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `evolve_declined`
--
ALTER TABLE `evolve_declined`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `evolve_export`
--
ALTER TABLE `evolve_export`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `evolve_exportfields`
--
ALTER TABLE `evolve_exportfields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `evolve_instanceseq`
--
ALTER TABLE `evolve_instanceseq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `evolve_instanceseq_history`
--
ALTER TABLE `evolve_instanceseq_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `evolve_metafields`
--
ALTER TABLE `evolve_metafields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `evolve_metaforms`
--
ALTER TABLE `evolve_metaforms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `evolve_organizations`
--
ALTER TABLE `evolve_organizations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `evolve_premiumcalculations`
--
ALTER TABLE `evolve_premiumcalculations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `evolve_premiumlookup`
--
ALTER TABLE `evolve_premiumlookup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `evolve_question_values`
--
ALTER TABLE `evolve_question_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `evolve_question_values_history`
--
ALTER TABLE `evolve_question_values_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `evolve_signatures`
--
ALTER TABLE `evolve_signatures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `evolve_wizards`
--
ALTER TABLE `evolve_wizards`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `executive_desktop`
--
ALTER TABLE `executive_desktop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `export_fields`
--
ALTER TABLE `export_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `facilities_equipments`
--
ALTER TABLE `facilities_equipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fields`
--
ALTER TABLE `fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fieldstodeleteinmultipleform`
--
ALTER TABLE `fieldstodeleteinmultipleform`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `formcomments`
--
ALTER TABLE `formcomments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `form_menu`
--
ALTER TABLE `form_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `game_points`
--
ALTER TABLE `game_points`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `goal_label`
--
ALTER TABLE `goal_label`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `golfmap_avatarcourses`
--
ALTER TABLE `golfmap_avatarcourses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `golfmap_courses`
--
ALTER TABLE `golfmap_courses`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `grade`
--
ALTER TABLE `grade`
  MODIFY `id` int(13) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groups_alerts`
--
ALTER TABLE `groups_alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groups_avatars`
--
ALTER TABLE `groups_avatars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groups_brokers`
--
ALTER TABLE `groups_brokers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groups_managers`
--
ALTER TABLE `groups_managers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groups_modules`
--
ALTER TABLE `groups_modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groups_tiles`
--
ALTER TABLE `groups_tiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `group_timesheet_clients`
--
ALTER TABLE `group_timesheet_clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `help_tour`
--
ALTER TABLE `help_tour`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ikra`
--
ALTER TABLE `ikra`
  MODIFY `ikraid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ikradaily`
--
ALTER TABLE `ikradaily`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `image_tags`
--
ALTER TABLE `image_tags`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `import_logs`
--
ALTER TABLE `import_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `instancefieldbigtext`
--
ALTER TABLE `instancefieldbigtext`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `instancefields`
--
ALTER TABLE `instancefields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `instanceforms`
--
ALTER TABLE `instanceforms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `instanceforms_join`
--
ALTER TABLE `instanceforms_join`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `instforms_files`
--
ALTER TABLE `instforms_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `instforms_files_tmp`
--
ALTER TABLE `instforms_files_tmp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `instforms_groups`
--
ALTER TABLE `instforms_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `instforms_links`
--
ALTER TABLE `instforms_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `job`
--
ALTER TABLE `job`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `kra_instanceform`
--
ALTER TABLE `kra_instanceform`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `layer_animations`
--
ALTER TABLE `layer_animations`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `leaderboard_log`
--
ALTER TABLE `leaderboard_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `loss_run_client`
--
ALTER TABLE `loss_run_client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `loss_run_overall`
--
ALTER TABLE `loss_run_overall`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mapping_table`
--
ALTER TABLE `mapping_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `master_flags`
--
ALTER TABLE `master_flags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `matrix_config`
--
ALTER TABLE `matrix_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `matrix_quarter`
--
ALTER TABLE `matrix_quarter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `menus_avatars`
--
ALTER TABLE `menus_avatars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `message_attachments`
--
ALTER TABLE `message_attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `message_recepients`
--
ALTER TABLE `message_recepients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `metafields`
--
ALTER TABLE `metafields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `metaforms`
--
ALTER TABLE `metaforms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `metaform_fieldorder`
--
ALTER TABLE `metaform_fieldorder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `metalist`
--
ALTER TABLE `metalist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `metapdffields`
--
ALTER TABLE `metapdffields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `metapdfforms`
--
ALTER TABLE `metapdfforms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `metareportfields`
--
ALTER TABLE `metareportfields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `metareports`
--
ALTER TABLE `metareports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `metastatus`
--
ALTER TABLE `metastatus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `meta_import`
--
ALTER TABLE `meta_import`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `meta_importcolumns`
--
ALTER TABLE `meta_importcolumns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `meta_multiselect`
--
ALTER TABLE `meta_multiselect`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mletquestions`
--
ALTER TABLE `mletquestions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `modulecategories`
--
ALTER TABLE `modulecategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `module_map_category`
--
ALTER TABLE `module_map_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `navigations`
--
ALTER TABLE `navigations`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `non_compliance`
--
ALTER TABLE `non_compliance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `oauth2_setting`
--
ALTER TABLE `oauth2_setting`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `observers`
--
ALTER TABLE `observers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `offboarding_audit`
--
ALTER TABLE `offboarding_audit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `operatingrhythm`
--
ALTER TABLE `operatingrhythm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `operatingrhythm_avatars`
--
ALTER TABLE `operatingrhythm_avatars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `operatingrhythm_groups`
--
ALTER TABLE `operatingrhythm_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `orgclientlist`
--
ALTER TABLE `orgclientlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `orgs_modules`
--
ALTER TABLE `orgs_modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `orgs_partners`
--
ALTER TABLE `orgs_partners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `org_role`
--
ALTER TABLE `org_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `or_attendees`
--
ALTER TABLE `or_attendees`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `or_meta`
--
ALTER TABLE `or_meta`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `oxmedia_devices`
--
ALTER TABLE `oxmedia_devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `oxmedia_devices_sliders`
--
ALTER TABLE `oxmedia_devices_sliders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `oxmedia_playlist`
--
ALTER TABLE `oxmedia_playlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `oxmedia_slides`
--
ALTER TABLE `oxmedia_slides`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ox_alert`
--
ALTER TABLE `ox_alert`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ox_alert_group_mapper`
--
ALTER TABLE `ox_alert_group_mapper`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ox_announcement`
--
ALTER TABLE `ox_announcement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ox_announcement_group_mapper`
--
ALTER TABLE `ox_announcement_group_mapper`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `podview`
--
ALTER TABLE `podview`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `poll_answers`
--
ALTER TABLE `poll_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `poll_questions`
--
ALTER TABLE `poll_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `prid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `query_config`
--
ALTER TABLE `query_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `relationshipinstance`
--
ALTER TABLE `relationshipinstance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `relationships`
--
ALTER TABLE `relationships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rpt_data`
--
ALTER TABLE `rpt_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rpt_volvo`
--
ALTER TABLE `rpt_volvo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rules`
--
ALTER TABLE `rules`
  MODIFY `idrules` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sales_pipeline_kra`
--
ALTER TABLE `sales_pipeline_kra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `slides`
--
ALTER TABLE `slides`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `spreadsheet_mapper`
--
ALTER TABLE `spreadsheet_mapper`
  MODIFY `spreadsheetid` int(50) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `srf`
--
ALTER TABLE `srf`
  MODIFY `srfid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `static_slides`
--
ALTER TABLE `static_slides`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `stattracker`
--
ALTER TABLE `stattracker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `statusboxes`
--
ALTER TABLE `statusboxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sticky_notes`
--
ALTER TABLE `sticky_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sub_projects`
--
ALTER TABLE `sub_projects`
  MODIFY `sprid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `table_statesave`
--
ALTER TABLE `table_statesave`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `testcase`
--
ALTER TABLE `testcase`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `testcaseparm`
--
ALTER TABLE `testcaseparm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `testname`
--
ALTER TABLE `testname`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `testnameparm`
--
ALTER TABLE `testnameparm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `timesheet_clients`
--
ALTER TABLE `timesheet_clients`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `timesheet_client_sla`
--
ALTER TABLE `timesheet_client_sla`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `timesheet_cost`
--
ALTER TABLE `timesheet_cost`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `timesheet_dropdown1`
--
ALTER TABLE `timesheet_dropdown1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `timesheet_dropdown2`
--
ALTER TABLE `timesheet_dropdown2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `timesheet_dropdown3`
--
ALTER TABLE `timesheet_dropdown3`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `timesheet_dropdown4`
--
ALTER TABLE `timesheet_dropdown4`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `timesheet_dropdown5`
--
ALTER TABLE `timesheet_dropdown5`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `timesheet_fields`
--
ALTER TABLE `timesheet_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `timesheet_fields_mapper`
--
ALTER TABLE `timesheet_fields_mapper`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `timesheet_lob`
--
ALTER TABLE `timesheet_lob`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `timesheet_process`
--
ALTER TABLE `timesheet_process`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `timesheet_project`
--
ALTER TABLE `timesheet_project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `timesheet_status`
--
ALTER TABLE `timesheet_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `timesheet_type`
--
ALTER TABLE `timesheet_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `transients`
--
ALTER TABLE `transients`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `type`
--
ALTER TABLE `type`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_contact`
--
ALTER TABLE `user_contact`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `webpivottable`
--
ALTER TABLE `webpivottable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `workflow`
--
ALTER TABLE `workflow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `workflow_elements`
--
ALTER TABLE `workflow_elements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `workflow_steps`
--
ALTER TABLE `workflow_steps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `xflat_config`
--
ALTER TABLE `xflat_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
