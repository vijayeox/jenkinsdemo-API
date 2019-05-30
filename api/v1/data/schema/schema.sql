/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
-- ---------------------------------------------------------


-- CREATE TABLE "active_brokers" ---------------------------
-- CREATE TABLE "active_brokers" -------------------------------
CREATE TABLE `active_brokers` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`accountname` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`lookupcode` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`phonenumber` VarChar( 12 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`primarycontact` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`address1` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`address2` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`city` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`statecode` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`zipcode` Int( 10 ) NOT NULL,
	`faxnumber` VarChar( 12 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`primaryemail` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`role` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'broker / prospect',
	`modified_date` Date NULL,
	`longitude` Decimal( 18, 15 ) NULL,
	`latitude` Decimal( 18, 15 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "admin_menu_access" ------------------------
-- CREATE TABLE "admin_menu_access" ----------------------------
CREATE TABLE `admin_menu_access` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`adminid` Int( 11 ) NOT NULL,
	`menuid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "admin_menus" ------------------------------
-- CREATE TABLE "admin_menus" ----------------------------------
CREATE TABLE `admin_menus` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`modulename` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`parentid` Int( 11 ) NOT NULL DEFAULT '0',
	`link` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "admins" -----------------------------------
-- CREATE TABLE "admins" ---------------------------------------
CREATE TABLE `admins` ( 
	`id` Int( 13 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 13 ) NOT NULL,
	`firstname` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`lastname` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`email` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "admission_primary_physician" --------------
-- CREATE TABLE "admission_primary_physician" ------------------
CREATE TABLE `admission_primary_physician` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`firstname` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`middlename` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`lastname` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "admission_referring_physician" ------------
-- CREATE TABLE "admission_referring_physician" ----------------
CREATE TABLE `admission_referring_physician` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`firstname` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`middlename` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`lastname` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "admission_values" -------------------------
-- CREATE TABLE "admission_values" -----------------------------
CREATE TABLE `admission_values` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`ssn` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`instanceformid` Int( 11 ) NOT NULL,
	`formposition` VarChar( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`firstname` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`middlename` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`lastname` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`dateofbirth` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`referraldate` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`referralsource` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`referothers` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`otherdetails` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`primarydoctor` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`otherdoc` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`hospitalchoice` Int( 11 ) NULL,
	`referringphysicianid` Int( 11 ) NULL,
	`dxcode` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`primepayor` TinyInt( 4 ) NULL,
	`clinicallyapproved` TinyInt( 1 ) NULL,
	`watchlistapproval` TinyInt( 4 ) NULL,
	`watchlistapprovalname` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`watchlistapprovaldate` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`streetone` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`streettwo` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`city` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`isstatementaddress` TinyInt( 1 ) NULL,
	`statementstate` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`healthaddress` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`financeaddress` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`statementcity` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`statementaddress` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`phone` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`personhealth` Int( 11 ) NULL,
	`state` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`personfinance` Int( 11 ) NULL,
	`signindate` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`status` TinyInt( 1 ) NOT NULL DEFAULT '0',
	`levelofcare` TinyInt( 4 ) NULL,
	`notskilled` Int( 11 ) NULL,
	`verifymedicare` TinyInt( 4 ) NULL,
	`needform7000` TinyInt( 4 ) NULL,
	`appliedpasrr` TinyInt( 4 ) NULL,
	`selectterm` TinyInt( 4 ) NULL,
	`mcr3` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`hmo3` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`verificationdate3` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`mcr4` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`hmo4` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`verificationdate4` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`hospitaladmitdate` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`hospitaldischargedate` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`primarypayer` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "admission_watchlist" ----------------------
-- CREATE TABLE "admission_watchlist" --------------------------
CREATE TABLE `admission_watchlist` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`ssn` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`firstname` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`middlename` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`lastname` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`dateofbirth` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "admit_dispatched_new" ---------------------
-- CREATE TABLE "admit_dispatched_new" -------------------------
CREATE TABLE `admit_dispatched_new` ( 
	`RiskNumber` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`branch` VarChar( 110 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`Dispatcheddate` Date NULL,
	`Admitted` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "admit_dispatched_renewal" -----------------
-- CREATE TABLE "admit_dispatched_renewal" ---------------------
CREATE TABLE `admit_dispatched_renewal` ( 
	`RiskNumber` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`branch` VarChar( 110 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`Dispatcheddate` Date NULL,
	`Admitted` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "admit_received_new" -----------------------
-- CREATE TABLE "admit_received_new" ---------------------------
CREATE TABLE `admit_received_new` ( 
	`RiskNumber` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`branch` VarChar( 110 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`Receiveddate` Date NULL,
	`Admitted` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "admit_received_renewal" -------------------
-- CREATE TABLE "admit_received_renewal" -----------------------
CREATE TABLE `admit_received_renewal` ( 
	`RiskNumber` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`branch` VarChar( 110 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`Receiveddate` Date NULL,
	`Admitted` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "admneedbydate" ----------------------------
-- CREATE TABLE "admneedbydate" --------------------------------
CREATE TABLE `admneedbydate` ( 
	`DaysOut` Int( 8 ) NULL )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "admneedbydatebybranch" --------------------
-- CREATE TABLE "admneedbydatebybranch" ------------------------
CREATE TABLE `admneedbydatebybranch` ( 
	`branch` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`CurrentDate` Date NULL,
	`DaysOut` Int( 8 ) NULL )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "agora_times" ------------------------------
-- CREATE TABLE "agora_times" ----------------------------------
CREATE TABLE `agora_times` ( 
	`Sl_No` Int( 100 ) NOT NULL,
	`Policy_Number` Int( 200 ) NOT NULL,
	`Process` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`Line_of_Business` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`Received_Date` Date NOT NULL,
	`Processed_Date` Date NOT NULL,
	`KRA` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`Client_Id` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`Status` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`tat` Int( 20 ) NOT NULL,
	PRIMARY KEY ( `Sl_No` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "alerts" -----------------------------------
-- CREATE TABLE "alerts" ---------------------------------------
CREATE TABLE `alerts` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`text` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`type` VarChar( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'system',
	`orgid` Int( 11 ) NOT NULL,
	`disabled` TinyInt( 4 ) NULL DEFAULT '0',
	`enddate` DateTime NULL,
	`creatorid` Int( 11 ) NULL,
	`startdate` DateTime NULL,
	`socialstatus` TinyInt( 1 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "announcements" ----------------------------
-- CREATE TABLE "announcements" --------------------------------
CREATE TABLE `announcements` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 32 ) NOT NULL,
	`date_created` DateTime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`startdate` DateTime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`enddate` DateTime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`text` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`name` VarChar( 259 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`orgid` Int( 32 ) NOT NULL,
	`enabled` TinyInt( 1 ) NOT NULL DEFAULT '1',
	`media_location` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`media_type` Int( 5 ) NOT NULL DEFAULT '1',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "api_data" ---------------------------------
-- CREATE TABLE "api_data" -------------------------------------
CREATE TABLE `api_data` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`api` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`apiid` Int( 11 ) NOT NULL,
	`name` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`values` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`date` DateTime NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
COMMENT 'avatar id mapped from avatar_api table'
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "app_usage" --------------------------------
-- CREATE TABLE "app_usage" ------------------------------------
CREATE TABLE `app_usage` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`moduleid` Int( 11 ) NULL,
	`formid` Int( 11 ) NULL,
	`type` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
	`count` Int( 11 ) NOT NULL,
	`date_used` Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`systeminfo` Text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_unicode_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "archives" ---------------------------------
-- CREATE TABLE "archives" -------------------------------------
CREATE TABLE `archives` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`amid` Int( 11 ) NOT NULL COMMENT 'primary key of archives_master table',
	`table_name` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`column_name` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`records` LongText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`addedon` Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "archives_master" --------------------------
-- CREATE TABLE "archives_master" ------------------------------
CREATE TABLE `archives_master` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NOT NULL COMMENT 'the logged in person\'s id',
	`type` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`orgid` Int( 11 ) NOT NULL DEFAULT '0',
	`groupid` Int( 11 ) NOT NULL DEFAULT '0',
	`userid` Int( 11 ) NOT NULL DEFAULT '0',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
COMMENT 'this table has a child table which is archives'
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "assesment_avatarscore" --------------------
-- CREATE TABLE "assesment_avatarscore" ------------------------
CREATE TABLE `assesment_avatarscore` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`questionid` Int( 11 ) NOT NULL,
	`assesmentid` Int( 4 ) NOT NULL,
	`answer` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`score` Float( 12, 0 ) NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`timestamp` Timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	`attempt` Int( 4 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "assesment_category" -----------------------
-- CREATE TABLE "assesment_category" ---------------------------
CREATE TABLE `assesment_category` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`orgid` Int( 11 ) NOT NULL,
	`instanceformid` Int( 11 ) NULL,
	`assigntonewhire` TinyInt( 4 ) NULL DEFAULT '0',
	`project` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`lob` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`parentgroupid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "assesment_category_groups" ----------------
-- CREATE TABLE "assesment_category_groups" --------------------
CREATE TABLE `assesment_category_groups` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`categoryid` Int( 11 ) NOT NULL,
	`groupid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "assesment_list" ---------------------------
-- CREATE TABLE "assesment_list" -------------------------------
CREATE TABLE `assesment_list` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`categoryid` Int( 11 ) NOT NULL,
	`wizardid` Int( 11 ) NOT NULL DEFAULT '0',
	`name` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`active` TinyInt( 1 ) NOT NULL,
	`Type` Int( 11 ) NOT NULL,
	`duedate` DateTime NOT NULL,
	`duration` Time NOT NULL,
	`createdid` Int( 11 ) NOT NULL,
	`parent` Int( 11 ) NOT NULL,
	`retake` TinyInt( 4 ) NULL DEFAULT '0',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "assesment_questions" ----------------------
-- CREATE TABLE "assesment_questions" --------------------------
CREATE TABLE `assesment_questions` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`sequenceid` Int( 3 ) NOT NULL,
	`assesmentid` Int( 11 ) NOT NULL,
	`categoryid` Int( 11 ) NOT NULL,
	`question` VarChar( 5000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`weightage` Float( 12, 0 ) NOT NULL,
	`answertype` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`options` VarChar( 2500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`answer` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "assesment_summary" ------------------------
-- CREATE TABLE "assesment_summary" ----------------------------
CREATE TABLE `assesment_summary` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`assesmentid` Int( 4 ) NOT NULL,
	`categoryid` Int( 11 ) NOT NULL,
	`star_points` Int( 11 ) NULL,
	`duration` Int( 11 ) NOT NULL,
	`total_questions` Int( 11 ) NULL,
	`total_weightage` Int( 11 ) NOT NULL,
	`correct_answers` Int( 4 ) NOT NULL,
	`status` VarChar( 30 ) CHARACTER SET latin1 COLLATE latin1_general_ci NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`completed_questions` Int( 4 ) NOT NULL,
	`completion_status` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "attachmentlogger" -------------------------
-- CREATE TABLE "attachmentlogger" -----------------------------
CREATE TABLE `attachmentlogger` ( 
	`id` Int( 255 ) AUTO_INCREMENT NOT NULL,
	`fileid` Int( 255 ) NOT NULL,
	`date_modified` DateTime NULL,
	`type` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`metalog` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`filekey` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`avatarid` Int( 255 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "auditlog" ---------------------------------
-- CREATE TABLE "auditlog" -------------------------------------
CREATE TABLE `auditlog` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`moduleid` Int( 11 ) NULL,
	`formid` Int( 11 ) NULL,
	`avatarid` Int( 11 ) NULL,
	`groupid` Int( 11 ) NULL,
	`instanceformid` Int( 11 ) NULL,
	`fieldid` Int( 11 ) NULL,
	`oldvalue` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`newvalue` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`description` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`changetype` VarChar( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`modifieddate` DateTime NULL DEFAULT CURRENT_TIMESTAMP,
	`systeminfo` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "auditlog_club" ----------------------------
-- CREATE TABLE "auditlog_club" --------------------------------
CREATE TABLE `auditlog_club` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`type` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`typeid` Int( 11 ) NOT NULL,
	`action` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`description` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`actionon` Int( 11 ) NULL,
	`modifieddate` DateTime NOT NULL,
	`groupid` Int( 11 ) NULL,
	`systeminfo` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "avatar_api" -------------------------------
-- CREATE TABLE "avatar_api" -----------------------------------
CREATE TABLE `avatar_api` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NULL,
	`api` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`name` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`value` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "avatar_flags" -----------------------------
-- CREATE TABLE "avatar_flags" ---------------------------------
CREATE TABLE `avatar_flags` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`flag` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`value` VarChar( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "avatar_instanceform_mapper" ---------------
-- CREATE TABLE "avatar_instanceform_mapper" -------------------
CREATE TABLE `avatar_instanceform_mapper` ( 
	`avatarid` Int( 10 ) NOT NULL,
	`instanceformid` Int( 20 ) NOT NULL,
	`type` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `avatarid`, `instanceformid`, `type` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "avatars" ----------------------------------
-- CREATE TABLE "avatars" --------------------------------------
CREATE TABLE `avatars` ( 
	`id` Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`gamelevel` VarChar( 111 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`username` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`password` VarChar( 32 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`firstname` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`lastname` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`name` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`role` VarChar( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`last_login` DateTime NULL,
	`orgid` Int( 11 ) NOT NULL,
	`email` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`emailnotify` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Active',
	`sentinel` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'On',
	`icon` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`gamemodeIcon` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`status` VarChar( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Active',
	`ipaddress` VarChar( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`country` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`dob` Date NULL,
	`designation` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`phone` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`address` VarChar( 300 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`sex` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`website` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`about` VarChar( 2000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`interest` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`hobbies` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`managerid` Int( 11 ) NULL,
	`alertsacknowledged` TinyInt( 4 ) NULL DEFAULT '1',
	`pollsacknowledged` TinyInt( 4 ) NOT NULL DEFAULT '1',
	`selfcontribute` TinyInt( 4 ) NULL,
	`contribute_percent` Int( 11 ) NULL,
	`statusbox` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'Matrix|Leaderboard|Alerts',
	`eid` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`defaultgroupid` VarChar( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`cluster` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0',
	`level` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`open_new_tab` TinyInt( 4 ) NOT NULL DEFAULT '0',
	`listtoggle` TinyInt( 4 ) NOT NULL,
	`defaultmatrixid` Int( 12 ) NULL DEFAULT '0',
	`lastactivity` Int( 11 ) NULL DEFAULT '0',
	`locked` TinyInt( 4 ) NULL DEFAULT '0',
	`signature` VarChar( 5000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`location` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`org_role_id` Int( 11 ) NOT NULL DEFAULT '1',
	`in_game` Int( 11 ) NOT NULL DEFAULT '0',
	`mission_link` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`instanceform_link` Int( 10 ) NULL,
	`timezone` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'Asia/Kolkata',
	`inmail_label` VarChar( 10000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '2=>Comment|3=>Observer|4=>Personal',
	`avatar_date_created` DateTime NULL,
	`doj` Date NULL,
	`password_reset_date` Date NULL,
	`otp` Int( 6 ) NULL,
	CONSTRAINT `id` UNIQUE( `id` ),
	CONSTRAINT `users_username_idx` UNIQUE( `username` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "avatars_alerts" ---------------------------
-- CREATE TABLE "avatars_alerts" -------------------------------
CREATE TABLE `avatars_alerts` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`alertid` Int( 11 ) NOT NULL,
	`acknowledged` TinyInt( 4 ) NULL DEFAULT '0',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "avatars_app" ------------------------------
-- CREATE TABLE "avatars_app" ----------------------------------
CREATE TABLE `avatars_app` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`type` VarChar( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`typeid` VarChar( 11 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`moduleid` Int( 11 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "avatars_menus" ----------------------------
-- CREATE TABLE "avatars_menus" --------------------------------
CREATE TABLE `avatars_menus` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`groupid` Int( 11 ) NULL,
	`menuid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "avatars_modules" --------------------------
-- CREATE TABLE "avatars_modules" ------------------------------
CREATE TABLE `avatars_modules` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`moduleid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "avatars_tiles" ----------------------------
-- CREATE TABLE "avatars_tiles" --------------------------------
CREATE TABLE `avatars_tiles` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`tileid` Int( 11 ) NOT NULL,
	`sequence` Int( 4 ) NULL,
	`type` VarChar( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'admin: tiles assigned from admin->list avatars;group: assigned through group and manage tiles, avatar: assigned through profile prefrences',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "categories" -------------------------------
-- CREATE TABLE "categories" -----------------------------------
CREATE TABLE `categories` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`cat_name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_bin NULL,
	`cat_icon` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`cat_parent_id` Int( 11 ) NULL,
	`cat_free_flag` Int( 1 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "clearance_view" ---------------------------
-- CREATE TABLE "clearance_view" -------------------------------
CREATE TABLE `clearance_view` ( 
	`underwriter` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`status` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`type` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`premium` Decimal( 42, 2 ) NULL,
	`files` BigInt( 21 ) NULL )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "client_avatar" ----------------------------
-- CREATE TABLE "client_avatar" --------------------------------
CREATE TABLE `client_avatar` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`formid` Int( 11 ) NOT NULL,
	`clientid` Int( 11 ) NOT NULL,
	`customername` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`avatarname` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`groupid` Int( 11 ) NOT NULL,
	`groupname` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`managerid` Int( 11 ) NOT NULL,
	`primary` TinyInt( 1 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "clientmap_address" ------------------------
-- CREATE TABLE "clientmap_address" ----------------------------
CREATE TABLE `clientmap_address` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`street` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`city` VarChar( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`state` VarChar( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`zip` Int( 5 ) NULL,
	`phone` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`status` TinyInt( 4 ) NOT NULL,
	`modifiedby` Int( 11 ) NULL,
	`modifieddate` Date NULL,
	`website` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`producer` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'Name',
	`policy_start_date` Date NULL,
	`longitude` Decimal( 18, 15 ) NULL,
	`latitude` Decimal( 18, 15 ) NULL,
	`contactname` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`primaryemail` VarChar( 300 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`otherinformation` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "club_client_target" -----------------------
-- CREATE TABLE "club_client_target" ---------------------------
CREATE TABLE `club_client_target` ( 
	`id` Int( 100 ) AUTO_INCREMENT NOT NULL,
	`client_id` VarChar( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`target` Int( 100 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "club_dashboard_menu" ----------------------
-- CREATE TABLE "club_dashboard_menu" --------------------------
CREATE TABLE `club_dashboard_menu` ( 
	`id` Int( 30 ) NOT NULL,
	`clientid` VarChar( 50 ) CHARACTER SET ucs2 COLLATE ucs2_general_ci NULL,
	`client_role` VarChar( 70 ) CHARACTER SET ucs2 COLLATE ucs2_general_ci NULL,
	`menu_name` VarChar( 80 ) CHARACTER SET ucs2 COLLATE ucs2_general_ci NULL,
	`link` VarChar( 100 ) CHARACTER SET ucs2 COLLATE ucs2_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "club_kra" ---------------------------------
-- CREATE TABLE "club_kra" -------------------------------------
CREATE TABLE `club_kra` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`groupid` Int( 11 ) NULL,
	`level` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`org_role` Int( 11 ) NULL,
	`avatarid` Int( 11 ) NULL,
	`url` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`creatorid` Int( 11 ) NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`filter` VarChar( 4000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fieldset` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`aggregate` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`groupby` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`targettype` Int( 11 ) NULL,
	`src_table` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`formid` Int( 11 ) NULL,
	`datetype` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`rygtype` Int( 11 ) NOT NULL,
	`srctype` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'setting it to 2 will add the goal value instead of incrementing it',
	`enddate` Date NULL,
	`startdate` Date NULL,
	`avatar_field` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`goal_field` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`default_point` Double( 22, 0 ) NULL,
	`calc_type` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'sum|times|diff|div|avg',
	`field_color` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "club_kra_sla" -----------------------------
-- CREATE TABLE "club_kra_sla" ---------------------------------
CREATE TABLE `club_kra_sla` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`kraid` Int( 11 ) NULL COMMENT 'primary key of query_config table',
	`groupid` Int( 11 ) NULL,
	`name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`year` Year NULL,
	`month` Int( 11 ) NULL,
	`sla_type` Int( 11 ) NULL,
	`redlowlimit` Float( 12, 0 ) NULL,
	`redlowworkflow` Int( 11 ) NULL,
	`redhighlimit` Float( 12, 0 ) NULL,
	`redhighworkflow` Int( 11 ) NULL,
	`yellowlowlimit` Float( 12, 0 ) NULL,
	`yellowlowworkflow` Int( 11 ) NULL,
	`yellowhighlimit` Float( 12, 0 ) NULL,
	`yellowhighworkflow` Int( 11 ) NULL,
	`greenlowlimit` Float( 12, 0 ) NULL,
	`greenlowworkflow` Int( 11 ) NULL,
	`greenhighlimit` Float( 12, 0 ) NULL,
	`greenhighworkflow` Int( 11 ) NULL,
	`goal_label_id` Int( 11 ) NULL,
	`triggertype` Int( 11 ) NULL COMMENT 'type of OR',
	`after` Int( 11 ) NULL COMMENT 'trigger after',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "club_matrix_admin" ------------------------
-- CREATE TABLE "club_matrix_admin" ----------------------------
CREATE TABLE `club_matrix_admin` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL DEFAULT '1',
	`name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`formid` Int( 11 ) NOT NULL,
	`matrixlink` Int( 11 ) NULL,
	`sel_status` TinyInt( 1 ) NOT NULL DEFAULT '0',
	`sel_assigned` TinyInt( 1 ) NOT NULL DEFAULT '0',
	`srctype` Int( 11 ) NULL,
	`client` Int( 11 ) NULL,
	`rows` Int( 11 ) NOT NULL DEFAULT '1',
	`datetype` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`defaultrange` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "club_matrix_filter" -----------------------
-- CREATE TABLE "club_matrix_filter" ---------------------------
CREATE TABLE `club_matrix_filter` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`matrixid` Int( 11 ) NOT NULL,
	`name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`label` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`type` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "club_matrix_graph_type" -------------------
-- CREATE TABLE "club_matrix_graph_type" -----------------------
CREATE TABLE `club_matrix_graph_type` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`matrixid` Int( 10 ) NULL,
	`matrixtypeid` Int( 10 ) NULL,
	`graphtype` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`category` VarChar( 10000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`filtercount` Int( 11 ) NULL,
	`aggregate` VarChar( 10000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`filter` VarChar( 10000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`invert` Int( 1 ) NULL DEFAULT '0',
	`percentage` Int( 11 ) NULL,
	`fieldset` VarChar( 10000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`metaformid` Int( 11 ) NULL,
	`instanceformid` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`instanceformfield` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "club_matrix_types" ------------------------
-- CREATE TABLE "club_matrix_types" ----------------------------
CREATE TABLE `club_matrix_types` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`matrixid` Int( 11 ) NOT NULL,
	`name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`creatorid` Int( 11 ) NOT NULL,
	`filter` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fieldset` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`aggregate` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`sub_aggregate` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`groupby` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`sortfield` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`sortorder` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`charttype` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`chartrow` Int( 11 ) NULL,
	`chartpos` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`legend` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`title` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`x_label` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`y_label` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`matrixlink` Int( 11 ) NULL,
	`enable_drilldown` Int( 5 ) NOT NULL DEFAULT '0',
	`drilldownvalue` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`drilldown_fields` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`link` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`link_tooltip` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`url_title` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`matrixexport` Int( 1 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "club_task" --------------------------------
-- CREATE TABLE "club_task" ------------------------------------
CREATE TABLE `club_task` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`task_name` VarChar( 145 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`avatar_id` Int( 11 ) NULL,
	`status` Int( 11 ) NULL,
	`start_time` DateTime NULL,
	`end_time` DateTime NULL,
	`task_duration` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '00:00:00',
	`process` Int( 11 ) NULL,
	`project` Int( 11 ) NULL,
	`billable` Int( 11 ) NULL,
	`lob` Int( 11 ) NULL,
	`client` Int( 11 ) NULL,
	`client_id` Int( 11 ) NULL,
	`received_date` DateTime NULL,
	`effective_date` DateTime NULL,
	`state` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`tat` Int( 11 ) NULL,
	`days_out` Int( 11 ) NULL,
	`comments` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`error` Int( 11 ) NOT NULL DEFAULT '0',
	`file_share` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`skip_counting` Int( 11 ) NULL DEFAULT '0' COMMENT 'Skip counting entry in matrix',
	`field1` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`field2` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`field3` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`field4` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`field5` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`field6` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`field7` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`field8` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`field9` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`field10` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`dropdown1` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`dropdown2` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`dropdown3` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`dropdown4` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`dropdown5` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`datefield1` DateTime NULL,
	`datefield2` DateTime NULL,
	`datefield3` DateTime NULL,
	`datefield4` DateTime NULL,
	`datefield5` DateTime NULL,
	`cost` Float( 11, 2 ) NULL,
	`cost_quality` Int( 11 ) NULL,
	`error_date` Date NULL,
	`session` Int( 11 ) NOT NULL DEFAULT '0',
	`last_modified` DateTime NULL,
	`instanceforms` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`matrixid` Int( 11 ) NULL,
	`file_upload` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`file_download` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`points_flag` Int( 11 ) NOT NULL DEFAULT '0' COMMENT '0=>Points not awarded 1=>Points awarded',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "club_task_pause" --------------------------
-- CREATE TABLE "club_task_pause" ------------------------------
CREATE TABLE `club_task_pause` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`task_id` Int( 11 ) NULL,
	`avatar_id` Int( 11 ) NULL,
	`start_time` DateTime NULL,
	`end_time` DateTime NULL,
	`pause_duration` Time NULL,
	`comment` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "club_timesheet_comments" ------------------
-- CREATE TABLE "club_timesheet_comments" ----------------------
CREATE TABLE `club_timesheet_comments` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`client` Int( 11 ) NOT NULL,
	`role` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`comment_date` DateTime NOT NULL,
	`process` Int( 11 ) NOT NULL,
	`comments` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`status` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`field1` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`field2` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`field3` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`field4` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`field5` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`field6` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`value1` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`value2` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`value3` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`value4` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`value5` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`value6` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`status1` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`status2` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`status3` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`status4` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`status5` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`status6` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`quality_score` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`client_quality_score` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`qc_per` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`qc_comment` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`qc_status` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`positive_feedback` Int( 10 ) NOT NULL DEFAULT '0',
	`negative_feedback` Int( 10 ) NOT NULL DEFAULT '0',
	`utilization` Int( 10 ) NULL,
	`billable_count` Int( 10 ) NULL,
	`actual_count` Int( 10 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "club_timesheet_daysout" -------------------
-- CREATE TABLE "club_timesheet_daysout" -----------------------
CREATE TABLE `club_timesheet_daysout` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`client` Int( 11 ) NOT NULL,
	`date` Date NULL,
	`role` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`field` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`value` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "club_timesheet_in_out" --------------------
-- CREATE TABLE "club_timesheet_in_out" ------------------------
CREATE TABLE `club_timesheet_in_out` ( 
	`id` Int( 10 ) AUTO_INCREMENT NOT NULL,
	`avatar_id` Int( 100 ) NOT NULL,
	`avatar_name` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`in_time` DateTime NOT NULL,
	`out_time` DateTime NULL,
	`status` VarChar( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`client_id` VarChar( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "club_timesheet_in_out_cron" ---------------
-- CREATE TABLE "club_timesheet_in_out_cron" -------------------
CREATE TABLE `club_timesheet_in_out_cron` ( 
	`id` Int( 100 ) AUTO_INCREMENT NOT NULL,
	`client_id` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`avatar_id` Int( 100 ) NULL,
	`avatar_name` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`date` Date NOT NULL,
	`break_duration` Float( 12, 0 ) NULL,
	`total_breaks` Int( 100 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "club_timesheet_mapper" --------------------
-- CREATE TABLE "club_timesheet_mapper" ------------------------
CREATE TABLE `club_timesheet_mapper` ( 
	`id` Int( 13 ) AUTO_INCREMENT NOT NULL,
	`club_id` Int( 11 ) NOT NULL,
	`timesheet_fieldid` Int( 11 ) NOT NULL,
	`club_field` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`timesheet_field` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "clubinvoice" ------------------------------
-- CREATE TABLE "clubinvoice" ----------------------------------
CREATE TABLE `clubinvoice` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`customername` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`owner` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`msa` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`msa_instanceid` Int( 11 ) NULL,
	`invoicenumber` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`address` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`workorder` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`rate` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`transaction` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`tsprocess` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`currentinvoice` Int( 11 ) NULL,
	`dategenerated` Date NOT NULL,
	`datemodified` DateTime NULL,
	`description` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`currency` Int( 11 ) NOT NULL,
	`clientid` Int( 11 ) NULL,
	`projectid` Int( 11 ) NOT NULL,
	`pastdue` VarChar( 11 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`totalamount` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`vat` VarChar( 11 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`status` Int( 11 ) NULL,
	`transactiontype` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`notetocustomers` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`invoicehtml` VarChar( 20000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`days` Int( 10 ) NULL,
	`servicetax` VarChar( 11 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`summary` TinyInt( 1 ) NULL DEFAULT '0',
	`swachhbharath` Float( 12, 0 ) NOT NULL DEFAULT '0',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "cometchat" --------------------------------
-- CREATE TABLE "cometchat" ------------------------------------
CREATE TABLE `cometchat` ( 
	`id` Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`from` Int( 10 ) UNSIGNED NOT NULL,
	`to` Int( 10 ) UNSIGNED NOT NULL,
	`message` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`sent` Int( 10 ) UNSIGNED NOT NULL DEFAULT '0',
	`read` TinyInt( 1 ) UNSIGNED NOT NULL DEFAULT '0',
	`direction` TinyInt( 1 ) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "cometchat_announcements" ------------------
-- CREATE TABLE "cometchat_announcements" ----------------------
CREATE TABLE `cometchat_announcements` ( 
	`id` Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`announcement` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`time` Int( 10 ) UNSIGNED NOT NULL,
	`to` Int( 10 ) NOT NULL,
	`recd` Int( 1 ) NOT NULL DEFAULT '0',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "cometchat_block" --------------------------
-- CREATE TABLE "cometchat_block" ------------------------------
CREATE TABLE `cometchat_block` ( 
	`id` Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`fromid` Int( 10 ) UNSIGNED NOT NULL,
	`toid` Int( 10 ) UNSIGNED NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "cometchat_chatroommessages" ---------------
-- CREATE TABLE "cometchat_chatroommessages" -------------------
CREATE TABLE `cometchat_chatroommessages` ( 
	`id` Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`userid` Int( 10 ) UNSIGNED NOT NULL,
	`chatroomid` Int( 10 ) UNSIGNED NOT NULL,
	`message` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`sent` Int( 10 ) UNSIGNED NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "cometchat_chatrooms" ----------------------
-- CREATE TABLE "cometchat_chatrooms" --------------------------
CREATE TABLE `cometchat_chatrooms` ( 
	`id` Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`name` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`lastactivity` Int( 10 ) UNSIGNED NOT NULL,
	`createdby` Int( 10 ) UNSIGNED NOT NULL,
	`password` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`type` TinyInt( 1 ) UNSIGNED NOT NULL,
	`vidsession` VarChar( 512 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "cometchat_chatrooms_users" ----------------
-- CREATE TABLE "cometchat_chatrooms_users" --------------------
CREATE TABLE `cometchat_chatrooms_users` ( 
	`userid` Int( 10 ) UNSIGNED NOT NULL,
	`chatroomid` Int( 10 ) UNSIGNED NOT NULL,
	`lastactivity` Int( 10 ) UNSIGNED NOT NULL,
	`isbanned` Int( 1 ) NULL DEFAULT '0',
	PRIMARY KEY ( `userid`, `chatroomid` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "cometchat_comethistory" -------------------
-- CREATE TABLE "cometchat_comethistory" -----------------------
CREATE TABLE `cometchat_comethistory` ( 
	`id` Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`channel` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`message` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`sent` Int( 10 ) UNSIGNED NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "cometchat_games" --------------------------
-- CREATE TABLE "cometchat_games" ------------------------------
CREATE TABLE `cometchat_games` ( 
	`userid` Int( 10 ) UNSIGNED NOT NULL,
	`score` Int( 10 ) UNSIGNED NULL,
	`games` Int( 10 ) UNSIGNED NULL,
	`recentlist` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`highscorelist` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `userid` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "cometchat_guests" -------------------------
-- CREATE TABLE "cometchat_guests" -----------------------------
CREATE TABLE `cometchat_guests` ( 
	`id` Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`name` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`lastactivity` Int( 10 ) UNSIGNED NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "cometchat_messages_old" -------------------
-- CREATE TABLE "cometchat_messages_old" -----------------------
CREATE TABLE `cometchat_messages_old` ( 
	`id` Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`from` Int( 10 ) UNSIGNED NOT NULL,
	`to` Int( 10 ) UNSIGNED NOT NULL,
	`message` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`sent` Int( 10 ) UNSIGNED NOT NULL DEFAULT '0',
	`read` TinyInt( 1 ) UNSIGNED NOT NULL DEFAULT '0',
	`direction` TinyInt( 1 ) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "cometchat_status" -------------------------
-- CREATE TABLE "cometchat_status" -----------------------------
CREATE TABLE `cometchat_status` ( 
	`userid` Int( 10 ) UNSIGNED NOT NULL,
	`message` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`status` Enum( 'available', 'away', 'busy', 'invisible', 'offline' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`typingto` Int( 10 ) UNSIGNED NULL,
	`typingtime` Int( 10 ) UNSIGNED NULL,
	PRIMARY KEY ( `userid` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "cometchat_videochatsessions" --------------
-- CREATE TABLE "cometchat_videochatsessions" ------------------
CREATE TABLE `cometchat_videochatsessions` ( 
	`username` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`identity` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`timestamp` Int( 10 ) UNSIGNED NULL DEFAULT '0',
	PRIMARY KEY ( `username` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "cominvoice" -------------------------------
-- CREATE TABLE "cominvoice" -----------------------------------
CREATE TABLE `cominvoice` ( 
	`id` Int( 150 ) AUTO_INCREMENT NOT NULL,
	`company_name` VarChar( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`va_code` VarChar( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`contract_renewal_date` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`msa_number` VarChar( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`work_order_number` VarChar( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`type` VarChar( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`fte` Float( 12, 0 ) NULL,
	`rate` Double( 22, 0 ) NULL,
	`customer_contact_person` VarChar( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`title` VarChar( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`telephone` VarChar( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`email` VarChar( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`address` VarChar( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`sales_person` VarChar( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`account_manager` VarChar( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`contract_start_date` VarChar( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`project_status` VarChar( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`commission_payable` VarChar( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`commission_percentage` VarChar( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`work_order_detail` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`city` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`state` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`zip` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`dashboard` VarChar( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`work_order` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`cc` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`process` VarChar( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`mr` VarChar( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`location` VarChar( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`group` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`in_no` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "comments" ---------------------------------
-- CREATE TABLE "comments" -------------------------------------
CREATE TABLE `comments` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`groupid` Int( 11 ) NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`comment` VarChar( 5000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`date_created` DateTime NOT NULL,
	`date_modified` DateTime NULL,
	`replyid` Int( 11 ) NULL DEFAULT '0',
	`approval_status` Int( 11 ) NULL DEFAULT '0' COMMENT '0=>pending,1=>approved,2=>rejected',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "comphotos" --------------------------------
-- CREATE TABLE "comphotos" ------------------------------------
CREATE TABLE `comphotos` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`file` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`instanceformid` Int( 11 ) NOT NULL,
	`link` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`tag` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "configurations" ---------------------------
-- CREATE TABLE "configurations" -------------------------------
CREATE TABLE `configurations` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`parameter` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`value` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "countdetails" -----------------------------
-- CREATE TABLE "countdetails" ---------------------------------
CREATE TABLE `countdetails` ( 
	`avatarid` Int( 11 ) NOT NULL,
	`messagecount` Int( 11 ) NOT NULL,
	`assignedtocount` Int( 11 ) NOT NULL,
	`followupscount` Int( 11 ) NOT NULL,
	`starpointscount` Int( 11 ) NOT NULL,
	`rank` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `avatarid` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "country" ----------------------------------
-- CREATE TABLE "country" --------------------------------------
CREATE TABLE `country` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`iso` Char( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`name` VarChar( 80 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`iso3` Char( 3 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`numcode` Smallint( 6 ) NULL,
	`phonecode` Int( 5 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "css" --------------------------------------
-- CREATE TABLE "css" ------------------------------------------
CREATE TABLE `css` ( 
	`id` Int( 9 ) AUTO_INCREMENT NOT NULL,
	`handle` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`settings` LongText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`hover` LongText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`params` LongText CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`advanced` LongText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	CONSTRAINT `id` UNIQUE( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "custom_login" -----------------------------
-- CREATE TABLE "custom_login" ---------------------------------
CREATE TABLE `custom_login` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`url` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`loginpage` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`forgotpassword` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`logo` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "customlistfields" -------------------------
-- CREATE TABLE "customlistfields" -----------------------------
CREATE TABLE `customlistfields` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`sequence` Int( 11 ) NULL,
	`customlistid` Int( 11 ) NOT NULL,
	`fieldname` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`fieldtext` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`sortable` VarChar( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`moduleid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "customlistviews" --------------------------
-- CREATE TABLE "customlistviews" ------------------------------
CREATE TABLE `customlistviews` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`moduleid` Int( 11 ) NOT NULL,
	`formid` Int( 11 ) NOT NULL,
	`customphtml` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`type` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`filter` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`parameters` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`groupid` Int( 11 ) NOT NULL,
	`sortby` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "daysout" ----------------------------------
-- CREATE TABLE "daysout" --------------------------------------
CREATE TABLE `daysout` ( 
	`Date` Date NOT NULL,
	`Daysout` Int( 11 ) NOT NULL,
	`Notes` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`Slno` Int( 11 ) AUTO_INCREMENT NOT NULL,
	PRIMARY KEY ( `Slno` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "demotracker" ------------------------------
-- CREATE TABLE "demotracker" ----------------------------------
CREATE TABLE `demotracker` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`email` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`firstname` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`lastname` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`companyname` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "dependencies" -----------------------------
-- CREATE TABLE "dependencies" ---------------------------------
CREATE TABLE `dependencies` ( 
	`Id` Int( 11 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`From` Int( 11 ) NULL,
	`To` Int( 11 ) NULL,
	`Type` Int( 11 ) NULL,
	`Cls` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`Lag` Int( 11 ) NULL,
	`LagUnit` VarChar( 12 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `Id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "documents" --------------------------------
-- CREATE TABLE "documents" ------------------------------------
CREATE TABLE `documents` ( 
	`id` Int( 11 ) NOT NULL,
	`name` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "email_cache" ------------------------------
-- CREATE TABLE "email_cache" ----------------------------------
CREATE TABLE `email_cache` ( 
	`id` Int( 11 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`userid` Int( 11 ) NOT NULL,
	`email` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`folder` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`uid` Int( 11 ) NOT NULL,
	`cc` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`_from` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`_subject` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`_to` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`envelope` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`unseen` TinyInt( 1 ) NOT NULL DEFAULT '0',
	`datetime` DateTime NOT NULL,
	PRIMARY KEY ( `id` ),
	CONSTRAINT `IDX_USER_EMAIL_FOLDER` UNIQUE( `userid`, `email`, `folder`, `uid` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "email_setting" ----------------------------
-- CREATE TABLE "email_setting" --------------------------------
CREATE TABLE `email_setting` ( 
	`id` Int( 11 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`userid` Int( 11 ) NOT NULL,
	`email` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`username` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`password` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`host` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`port` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`secure` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`folders` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`last_sync_time` DateTime NULL,
	`last_sync_duration` Int( 11 ) NOT NULL DEFAULT '0',
	`last_sync_status` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`smtp_host` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`smtp_port` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`smtp_username` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`smtp_password` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`smtp_secure` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`month_since` Int( 11 ) NOT NULL DEFAULT '0',
	`oauth_provider` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "email_templates" --------------------------
-- CREATE TABLE "email_templates" ------------------------------
CREATE TABLE `email_templates` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`subject` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`body` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "emailaccounts" ----------------------------
-- CREATE TABLE "emailaccounts" --------------------------------
CREATE TABLE `emailaccounts` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`username` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`password` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`incomingserver` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`incomingport` Int( 11 ) NOT NULL,
	`protocol` VarChar( 5 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`incomingencryption` VarChar( 5 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "emailconfig" ------------------------------
-- CREATE TABLE "emailconfig" ----------------------------------
CREATE TABLE `emailconfig` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`host` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`emailid` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
	`password` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`assignedid` Int( 11 ) NOT NULL,
	`groupid` Int( 11 ) NULL,
	`moduleid` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "emailheaders" -----------------------------
-- CREATE TABLE "emailheaders" ---------------------------------
CREATE TABLE `emailheaders` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`messageid` Int( 11 ) NOT NULL,
	`accountid` Int( 11 ) NOT NULL,
	`receivedfrom` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`subject` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`timestamp` DateTime NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "employeeformseq" --------------------------
-- CREATE TABLE "employeeformseq" ------------------------------
CREATE TABLE `employeeformseq` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`sequenceid` Int( 11 ) NOT NULL,
	`nextsequenceid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "employees" --------------------------------
-- CREATE TABLE "employees" ------------------------------------
CREATE TABLE `employees` ( 
	`id` Int( 11 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`step` Int( 3 ) NOT NULL DEFAULT '1',
	`status` TinyInt( 1 ) NOT NULL DEFAULT '0',
	`addedtime` DateTime NULL,
	`modifiedtime` DateTime NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "errorlog" ---------------------------------
-- CREATE TABLE "errorlog" -------------------------------------
CREATE TABLE `errorlog` ( 
	`policynumber` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`branch` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`Issuedfor` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`Issuedby` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`LOB` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`FQC` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`uwsrater` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`Date` Date NULL,
	`ErrorFixedOn` Date NULL,
	`Error` VarChar( 10000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`UWSaction` VarChar( 2000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`replysent` VarChar( 10000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`Client_ID` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `policynumber` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "escalations" ------------------------------
-- CREATE TABLE "escalations" ----------------------------------
CREATE TABLE `escalations` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`instanceformid` Int( 11 ) NOT NULL,
	`groupid` Int( 11 ) NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`level` VarChar( 5 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`primary` VarChar( 5 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`orgid` Int( 11 ) NULL,
	`creatorid` Int( 11 ) NULL,
	`moduleid` Int( 10 ) NULL,
	`formid` Int( 10 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "evolve_avatarformseq" ---------------------
-- CREATE TABLE "evolve_avatarformseq" -------------------------
CREATE TABLE `evolve_avatarformseq` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`formid` Int( 11 ) NOT NULL,
	`index` Int( 11 ) NULL DEFAULT '0',
	`nextid` Int( 11 ) NULL,
	`previd` Int( 11 ) NULL,
	`positionflag` Int( 11 ) NULL,
	`visible` Int( 11 ) NULL,
	`count` Int( 11 ) NULL DEFAULT '0',
	`instanceformid` Int( 11 ) NULL,
	`wizardid` Int( 11 ) NOT NULL,
	`status` TinyInt( 4 ) NOT NULL DEFAULT '0',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "evolve_avatars" ---------------------------
-- CREATE TABLE "evolve_avatars" -------------------------------
CREATE TABLE `evolve_avatars` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`avatarformid` Int( 11 ) NULL,
	`ssn` Int( 11 ) NULL,
	`premium` Decimal( 10, 0 ) NULL,
	`completedatetime` DateTime NULL,
	`language` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`locationid` Int( 11 ) NULL,
	`createddate` DateTime NOT NULL,
	`status` TinyInt( 4 ) NOT NULL DEFAULT '0',
	`wizardid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "evolve_declined" --------------------------
-- CREATE TABLE "evolve_declined" ------------------------------
CREATE TABLE `evolve_declined` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`name` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`value` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`wizardid` Int( 11 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "evolve_export" ----------------------------
-- CREATE TABLE "evolve_export" --------------------------------
CREATE TABLE `evolve_export` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`wizardid` Int( 12 ) NULL,
	`controllername` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`actionname` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "evolve_exportfields" ----------------------
-- CREATE TABLE "evolve_exportfields" --------------------------
CREATE TABLE `evolve_exportfields` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`evolveexportid` Int( 11 ) NOT NULL,
	`evolveformid` Int( 11 ) NOT NULL,
	`evolvefieldid` Int( 11 ) NOT NULL,
	`condition` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`expression` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`evolvefieldname` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "evolve_instanceseq" -----------------------
-- CREATE TABLE "evolve_instanceseq" ---------------------------
CREATE TABLE `evolve_instanceseq` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`instanceformid` Int( 11 ) NULL,
	`avatarformseqid` Int( 11 ) NOT NULL,
	`wizardid` Int( 11 ) NULL,
	`avatarid` Int( 11 ) NULL,
	`firstformseqid` Int( 11 ) NULL,
	`status` TinyInt( 1 ) NOT NULL DEFAULT '0',
	`ssn` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`premium` Decimal( 10, 2 ) NULL,
	`employercost` Decimal( 10, 2 ) NOT NULL,
	`completedatetime` DateTime NULL,
	`language` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`locationid` Int( 11 ) NULL,
	`terminationdate` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`currentstatusofavatar` VarChar( 4 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'A',
	`changeeffectivedate` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`lifechangeflag` TinyInt( 4 ) NOT NULL DEFAULT '0',
	`createddate` DateTime NOT NULL,
	`effectivedate` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`exportstatus` TinyInt( 4 ) NOT NULL DEFAULT '1',
	`exportstatusdate` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`assementscore` Int( 11 ) NOT NULL DEFAULT '0',
	`enrollment_startdate` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`enrollment_enddate` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`rewindflag` TinyInt( 4 ) NOT NULL DEFAULT '0',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "evolve_instanceseq_history" ---------------
-- CREATE TABLE "evolve_instanceseq_history" -------------------
CREATE TABLE `evolve_instanceseq_history` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`old_id` Int( 11 ) NULL,
	`instanceformid` Int( 11 ) NULL,
	`avatarformseqid` Int( 11 ) NOT NULL,
	`wizardid` Int( 11 ) NULL,
	`avatarid` Int( 11 ) NULL,
	`firstformseqid` Int( 11 ) NULL,
	`status` TinyInt( 1 ) NOT NULL DEFAULT '0',
	`ssn` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`premium` Decimal( 10, 2 ) NULL,
	`employercost` Decimal( 10, 2 ) NOT NULL,
	`completedatetime` DateTime NULL,
	`language` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`locationid` Int( 11 ) NULL,
	`terminationdate` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`currentstatusofavatar` VarChar( 4 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'A',
	`changeeffectivedate` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`lifechangeflag` TinyInt( 4 ) NOT NULL DEFAULT '0',
	`createddate` DateTime NOT NULL,
	`effectivedate` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`exportstatus` TinyInt( 4 ) NOT NULL DEFAULT '1',
	`assementscore` Int( 4 ) NOT NULL DEFAULT '0',
	`exportstatusdate` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`enrollment_startdate` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`enrollment_enddate` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`rewindflag` TinyInt( 4 ) NOT NULL DEFAULT '0',
	`history_datetime` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "evolve_metafields" ------------------------
-- CREATE TABLE "evolve_metafields" ----------------------------
CREATE TABLE `evolve_metafields` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`sequence` Int( 11 ) NOT NULL,
	`evolveformid` Int( 11 ) NOT NULL,
	`name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`text` VarChar( 10000 ) CHARACTER SET ucs2 COLLATE ucs2_general_ci NOT NULL,
	`helpertext` VarChar( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`type` VarChar( 30 ) CHARACTER SET ucs2 COLLATE ucs2_general_ci NULL,
	`instancefield` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`other_inst` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`other_instfield` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`options` VarChar( 10000 ) CHARACTER SET ucs2 COLLATE ucs2_general_ci NULL,
	`color` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`dependson` VarChar( 50 ) CHARACTER SET ucs2 COLLATE ucs2_general_ci NULL,
	`disablejavascript` TinyInt( 4 ) NULL DEFAULT '0',
	`required` TinyInt( 1 ) NULL DEFAULT '0',
	`regexpvalidator` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`validationtext` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`specialvalidator` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`readonly` TinyInt( 1 ) NULL DEFAULT '0',
	`expression` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canbehidden` TinyInt( 4 ) NOT NULL DEFAULT '0',
	`dateordependson` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`onlyownrcanchng` TinyInt( 1 ) NULL DEFAULT '0',
	`condition` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`premiumname` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`textspanish` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`dontshowpremiumbutton` Int( 4 ) NULL,
	`scoreablequestion` TinyInt( 1 ) NULL DEFAULT '0',
	`answer` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`weightage` Int( 4 ) NULL,
	`questiontype` VarChar( 40 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`dependentrequired` TinyInt( 1 ) NOT NULL DEFAULT '0',
	`encrypted` TinyInt( 1 ) NOT NULL DEFAULT '0',
	`multiplefield` TinyInt( 4 ) NULL DEFAULT '0',
	`tableproperty` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`classname` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`dontshowinfirstform` TinyInt( 4 ) NOT NULL DEFAULT '0',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "evolve_metaforms" -------------------------
-- CREATE TABLE "evolve_metaforms" -----------------------------
CREATE TABLE `evolve_metaforms` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`description` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`sequence` Int( 11 ) NULL,
	`htmltext` LongText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`modulename` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`video` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`videotype` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`multiple` Int( 11 ) NULL,
	`dynamic` Int( 11 ) NULL,
	`customform` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`condition` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`customcontroller` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`customaction` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`validation` VarChar( 300 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`validationmessage` VarChar( 300 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`formid` Int( 11 ) NOT NULL,
	`wizard_id` Int( 11 ) NOT NULL,
	`workflowid` Int( 11 ) NULL,
	`orgid` Int( 11 ) NOT NULL,
	`classvideos` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`hookfunction` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`pauseduration` TinyInt( 1 ) NOT NULL DEFAULT '0',
	`multiplecondition` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`calculatepremium` TinyInt( 4 ) NOT NULL DEFAULT '0',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "evolve_organizations" ---------------------
-- CREATE TABLE "evolve_organizations" -------------------------
CREATE TABLE `evolve_organizations` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`videofolder` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`pdfenable` TinyInt( 1 ) NOT NULL DEFAULT '0',
	`malepercentage` Int( 11 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "evolve_premiumcalculations" ---------------
-- CREATE TABLE "evolve_premiumcalculations" -------------------
CREATE TABLE `evolve_premiumcalculations` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`sequence` Int( 11 ) NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`text` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`dependentfield` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`expression` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`condition` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`showcondition` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`customclass` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`wizardid` Int( 11 ) NULL,
	`otherajaxfields` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`hidecoverage` TinyInt( 4 ) NOT NULL DEFAULT '0',
	`brochurename` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`brochurelink` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`conditiontohidincompletepage` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`multipleform` TinyInt( 4 ) NOT NULL DEFAULT '0',
	`multipleformfieldname` VarChar( 200 ) CHARACTER SET latin1 COLLATE latin1_bin NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "evolve_premiumlookup" ---------------------
-- CREATE TABLE "evolve_premiumlookup" -------------------------
CREATE TABLE `evolve_premiumlookup` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`name` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`minage` Int( 11 ) NULL,
	`maxage` Int( 11 ) NULL,
	`smoking` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`type` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`custom1` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`custom2` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`custom3` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`value` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`coverage` Int( 11 ) NULL,
	`totalcost` Decimal( 10, 2 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "evolve_question_values" -------------------
-- CREATE TABLE "evolve_question_values" -----------------------
CREATE TABLE `evolve_question_values` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`wizardid` Int( 11 ) NOT NULL,
	`instanceformid` Int( 11 ) NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`empformid` Int( 11 ) NULL,
	`formid` Int( 11 ) NOT NULL,
	`formname` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`questionname` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`questionid` Int( 11 ) NOT NULL,
	`value` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`score` TinyInt( 4 ) NULL,
	`encrypted` TinyInt( 1 ) NOT NULL DEFAULT '0',
	`oldvalue` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`modified_avatarid` Int( 11 ) NOT NULL,
	`modified_date` DateTime NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "evolve_question_values_history" -----------
-- CREATE TABLE "evolve_question_values_history" ---------------
CREATE TABLE `evolve_question_values_history` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`old_id` Int( 11 ) NULL,
	`orgid` Int( 11 ) NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`empformid` Int( 11 ) NULL,
	`formid` Int( 11 ) NOT NULL,
	`formname` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`questionname` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`questionid` Int( 11 ) NOT NULL,
	`value` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`wizardid` Int( 11 ) NULL,
	`instanceformid` Int( 11 ) NULL,
	`score` TinyInt( 4 ) NULL,
	`attempt` TinyInt( 4 ) NULL,
	`history_datetime` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`oldvalue` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`modified_avatarid` Int( 11 ) NOT NULL,
	`modified_date` DateTime NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "evolve_signatures" ------------------------
-- CREATE TABLE "evolve_signatures" ----------------------------
CREATE TABLE `evolve_signatures` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`username` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`password` VarChar( 32 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`signed_date` Timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "evolve_wizards" ---------------------------
-- CREATE TABLE "evolve_wizards" -------------------------------
CREATE TABLE `evolve_wizards` ( 
	`id` Int( 12 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`type` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`showpremium` TinyInt( 1 ) NOT NULL DEFAULT '0',
	`orgid` Int( 11 ) NOT NULL,
	`metaformid` Int( 11 ) NOT NULL,
	`customreview` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`customcomplete` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`disablevideo` TinyInt( 1 ) NOT NULL DEFAULT '0',
	`disableoptionsinvideo` TinyInt( 1 ) NOT NULL DEFAULT '0',
	`hideprevious` TinyInt( 4 ) NOT NULL DEFAULT '0',
	`hidenavigation` TinyInt( 4 ) NOT NULL DEFAULT '0',
	`allowforexport` Int( 11 ) NULL,
	`active` TinyInt( 1 ) NOT NULL,
	`categoryid` Int( 11 ) NOT NULL,
	`createid` Int( 11 ) NOT NULL,
	`startdate` Date NOT NULL,
	`duedate` DateTime NOT NULL,
	`onlineormanual` TinyInt( 4 ) NOT NULL,
	`retake` TinyInt( 4 ) NOT NULL DEFAULT '0',
	`duration` Time NOT NULL,
	`parent` Int( 11 ) NOT NULL,
	`passingper` Int( 11 ) NULL,
	`rules` LongText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`disableeditfromreview` TinyInt( 4 ) NOT NULL DEFAULT '0',
	`headingtitle` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'Total Paycheck Contribution',
	`uniquecount` Int( 11 ) NULL,
	`autogeneratedno` Int( 11 ) NOT NULL,
	`pauseduration` TinyInt( 1 ) NOT NULL DEFAULT '0',
	`retaketrainingperiod` TinyInt( 4 ) NOT NULL DEFAULT '0',
	`themecolor` VarChar( 11 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`workflowid` Int( 11 ) NULL,
	`email_status` TinyInt( 4 ) NOT NULL DEFAULT '0',
	`email_from` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`email_to` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`email_temp_id` Int( 11 ) NULL,
	`w1` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`w2` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`w3` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`w4` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`w5` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`w6` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`w7` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`w8` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`w9` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`w10` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`w11` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`w12` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`wbig1` VarChar( 10000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`wbig2` VarChar( 10000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`showrewindbutton` TinyInt( 4 ) NULL,
	`wizard_access` TinyInt( 4 ) NULL,
	`remark1` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`remark1lowerlimit` Int( 11 ) NULL,
	`remark1upperlimit` Int( 11 ) NULL,
	`remark2` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`remark2lowerlimit` Int( 11 ) NULL,
	`remark2upperlimit` Int( 11 ) NULL,
	`remark3` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`remark3lowerlimit` Int( 11 ) NULL,
	`remark3upperlimit` Int( 11 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "executive_desktop" ------------------------
-- CREATE TABLE "executive_desktop" ----------------------------
CREATE TABLE `executive_desktop` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`avatarid` Int( 11 ) NULL,
	`instanceform` Int( 11 ) NULL,
	`target` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`acheived` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`status` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`color` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`parentid` Int( 11 ) NOT NULL,
	`contract_link` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`contract_id` Int( 11 ) NULL,
	`matrix_link` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`matrix_id` Int( 11 ) NULL,
	`type` TinyInt( 4 ) NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "expense" ----------------------------------
-- CREATE TABLE "expense" --------------------------------------
CREATE TABLE `expense` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`employeeid` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`approvedby` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`date` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`description` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`category` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`moneytype` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`cost` Double( 22, 0 ) NULL,
	`avatarid` Int( 11 ) NULL,
	`status` TinyInt( 4 ) NULL,
	`createdid` Int( 11 ) NULL,
	`attachment` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`orgid` Int( 11 ) NOT NULL,
	`instanceformid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "export_fields" ----------------------------
-- CREATE TABLE "export_fields" --------------------------------
CREATE TABLE `export_fields` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`formid` Int( 11 ) NOT NULL,
	`name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`fields` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "facilities_equipments" --------------------
-- CREATE TABLE "facilities_equipments" ------------------------
CREATE TABLE `facilities_equipments` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`type` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`test_name` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`astm` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "fieldstodeleteinmultipleform" -------------
-- CREATE TABLE "fieldstodeleteinmultipleform" -----------------
CREATE TABLE `fieldstodeleteinmultipleform` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`formid` Int( 11 ) NOT NULL,
	`questionid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "form_menu" --------------------------------
-- CREATE TABLE "form_menu" ------------------------------------
CREATE TABLE `form_menu` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`formid` Int( 11 ) NOT NULL,
	`name` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`workflowid` Int( 11 ) NULL,
	`customaction` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`icon` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "formcomments" -----------------------------
-- CREATE TABLE "formcomments" ---------------------------------
CREATE TABLE `formcomments` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`instanceformid` Int( 11 ) NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`comment` VarChar( 5000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`date_created` DateTime NOT NULL,
	`date_modified` DateTime NULL,
	`replyid` Int( 11 ) NOT NULL DEFAULT '0',
	`observers` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`moduleid` Int( 11 ) NOT NULL,
	`approval_status` Int( 11 ) NULL DEFAULT '0' COMMENT '0=>pending,1=>approved,2=>rejected',
	`nextactiondate` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`email_ids` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`status` Int( 3 ) NULL,
	`ownerid` Int( 11 ) NULL,
	`assignedto` Int( 11 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "friends" ----------------------------------
-- CREATE TABLE "friends" --------------------------------------
CREATE TABLE `friends` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`friendid` Int( 11 ) NOT NULL,
	`relation_direction` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "game_points" ------------------------------
-- CREATE TABLE "game_points" ----------------------------------
CREATE TABLE `game_points` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`parentinstanceformid` Int( 11 ) NOT NULL,
	`childinstanceformid` Int( 11 ) NULL,
	`score` Int( 11 ) NOT NULL,
	`description` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`orgid` Int( 11 ) NOT NULL,
	`game_date` DateTime NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "gmail" ------------------------------------
-- CREATE TABLE "gmail" ----------------------------------------
CREATE TABLE `gmail` ( 
	`avatarid` Int( 11 ) NOT NULL,
	`id` VarChar( 32 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`subject` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`date` VarChar( 32 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`sender` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`status` Int( 2 ) NOT NULL,
	`attachments_flag` Int( 2 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "goal_label" -------------------------------
-- CREATE TABLE "goal_label" -----------------------------------
CREATE TABLE `goal_label` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NULL,
	`groupid` Int( 11 ) NULL,
	`org_role_id` Int( 11 ) NULL,
	`level` Int( 11 ) NULL,
	`goal_id` VarChar( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`goal_label` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`description` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`kra_id` Int( 11 ) NULL,
	`orgid` Int( 11 ) NOT NULL,
	`CALC_TYPE` VarChar( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'sum',
	`REVERSE_FLAG` TinyInt( 1 ) NOT NULL DEFAULT '0',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "golfmap_avatarcourses" --------------------
-- CREATE TABLE "golfmap_avatarcourses" ------------------------
CREATE TABLE `golfmap_avatarcourses` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`courseid` Int( 11 ) NOT NULL,
	`score` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "golfmap_courses" --------------------------
-- CREATE TABLE "golfmap_courses" ------------------------------
CREATE TABLE `golfmap_courses` ( 
	`id` Int( 5 ) AUTO_INCREMENT NOT NULL,
	`biz_name` VarChar( 117 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`e_address` VarChar( 83 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`e_city` VarChar( 38 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`e_state` VarChar( 22 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`e_postal` VarChar( 7 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`e_zip_full` VarChar( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`e_country` VarChar( 14 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`loc_county` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`loc_area_code` VarChar( 3 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`loc_FIPS` VarChar( 5 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`loc_MSA` VarChar( 4 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`loc_PMSA` VarChar( 4 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`loc_TZ` VarChar( 5 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`loc_DST` VarChar( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`loc_LAT_centroid` VarChar( 6 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`loc_LAT_poly` VarChar( 9 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`loc_LONG_centroid` VarChar( 8 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`loc_LONG_poly` VarChar( 11 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`biz_phone` VarChar( 14 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`metal_spikes` VarChar( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`play_five` VarChar( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`c_holes` VarChar( 4 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`c_type` VarChar( 12 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`year_built` VarChar( 4 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`c_designer` VarChar( 57 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`c_season` VarChar( 52 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`guest_policy` VarChar( 80 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`dress_code` VarChar( 76 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`green_fees` VarChar( 37 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`weekend_rates` VarChar( 12 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`adv_tee` VarChar( 8 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "google_gcm" -------------------------------
-- CREATE TABLE "google_gcm" -----------------------------------
CREATE TABLE `google_gcm` ( 
	`avatarid` Int( 11 ) NOT NULL,
	`gcm_registration_id` VarChar( 512 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`device` VarChar( 3 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `gcm_registration_id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "googlelogin" ------------------------------
-- CREATE TABLE "googlelogin" ----------------------------------
CREATE TABLE `googlelogin` ( 
	`avatarid` Int( 11 ) NOT NULL,
	`emailid` VarChar( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`name` VarChar( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`refreshtoken` VarChar( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`accesstoken` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `avatarid` ),
	CONSTRAINT `emailid` UNIQUE( `emailid` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "grade" ------------------------------------
-- CREATE TABLE "grade" ----------------------------------------
CREATE TABLE `grade` ( 
	`id` Int( 13 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`moduleid` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`cancreate` TinyInt( 1 ) NULL DEFAULT '0',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "group_timesheet_clients" ------------------
-- CREATE TABLE "group_timesheet_clients" ----------------------
CREATE TABLE `group_timesheet_clients` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`group_id` Int( 11 ) NOT NULL,
	`client_id` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "groups" -----------------------------------
-- CREATE TABLE "groups" ---------------------------------------
CREATE TABLE `groups` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 400 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`parentid` Int( 11 ) NULL,
	`orgid` Int( 11 ) NOT NULL,
	`managerid` Int( 11 ) NULL,
	`moduleid` Int( 11 ) NULL,
	`disablechat` TinyInt( 4 ) NULL,
	`assigntomanager` TinyInt( 4 ) NOT NULL DEFAULT '0',
	`description` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`logo` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`coverphoto` VarChar( 111 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`power_users` TinyInt( 4 ) NOT NULL DEFAULT '0',
	`type` TinyInt( 4 ) NULL DEFAULT '0',
	`hiddentopicons` TinyInt( 1 ) NOT NULL,
	`hidetiles` TinyInt( 1 ) NOT NULL,
	`hidewall` TinyInt( 1 ) NOT NULL,
	`hideannouncement` TinyInt( 1 ) NOT NULL,
	`hideleaderboard` TinyInt( 1 ) NOT NULL,
	`hiddenmessage` TinyInt( 1 ) NULL,
	`hiddenassignment` TinyInt( 1 ) NULL,
	`hiddenfollowup` TinyInt( 1 ) NULL,
	`hiddencreate` TinyInt( 1 ) NULL,
	`hiddensearch` TinyInt( 1 ) NULL,
	`hiddengroup` TinyInt( 1 ) NULL,
	`status` Enum( 'Active', 'Inactive' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Active',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "groups_alerts" ----------------------------
-- CREATE TABLE "groups_alerts" --------------------------------
CREATE TABLE `groups_alerts` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`groupid` Int( 11 ) NOT NULL,
	`alertid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "groups_avatars" ---------------------------
-- CREATE TABLE "groups_avatars" -------------------------------
CREATE TABLE `groups_avatars` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`groupid` Int( 11 ) NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "groups_brokers" ---------------------------
-- CREATE TABLE "groups_brokers" -------------------------------
CREATE TABLE `groups_brokers` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`groupid` Int( 11 ) NOT NULL COMMENT 'active_brokers',
	`brokerid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "groups_managers" --------------------------
-- CREATE TABLE "groups_managers" ------------------------------
CREATE TABLE `groups_managers` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`groupid` Int( 11 ) NOT NULL,
	`managerid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "groups_modules" ---------------------------
-- CREATE TABLE "groups_modules" -------------------------------
CREATE TABLE `groups_modules` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`groupid` Int( 11 ) NOT NULL,
	`moduleid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "groups_tiles" -----------------------------
-- CREATE TABLE "groups_tiles" ---------------------------------
CREATE TABLE `groups_tiles` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`groupid` Int( 11 ) NOT NULL,
	`tileid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "help_tour" --------------------------------
-- CREATE TABLE "help_tour" ------------------------------------
CREATE TABLE `help_tour` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`sequence` Int( 11 ) NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`element` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	`title` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	`content` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	`placement` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_unicode_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "ikra" -------------------------------------
-- CREATE TABLE "ikra" -----------------------------------------
CREATE TABLE `ikra` ( 
	`avatarid` Int( 11 ) NOT NULL,
	`month` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`year` Int( 20 ) NULL,
	`ikradate` Date NULL,
	`k1` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`k2` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`k3` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`k4` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`k5` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`average` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`starpoints` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`weightage` Float( 11, 2 ) NULL,
	`quality` Float( 11, 2 ) NOT NULL,
	`ikraid` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`k6` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`k7` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`k8` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`k9` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`k10` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`comments` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`sentinalupdate` Int( 10 ) NOT NULL DEFAULT '0',
	PRIMARY KEY ( `ikraid` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "ikradaily" --------------------------------
-- CREATE TABLE "ikradaily" ------------------------------------
CREATE TABLE `ikradaily` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NULL,
	`year` Int( 11 ) NULL,
	`month` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`ikradate` DateTime NULL,
	`average` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`starpoints` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`weightage` Float( 11, 2 ) NULL DEFAULT '0.00',
	`quality` Float( 11, 2 ) NULL DEFAULT '0.00',
	`k1` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`k2` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`k3` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`k4` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`k5` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`k6` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`k7` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`k8` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`k9` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`k10` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`comments` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`sentinalupdate` Int( 10 ) NOT NULL DEFAULT '0',
	`mygoal` Float( 11, 2 ) NULL DEFAULT '0.00',
	`teamgoal` Float( 11, 2 ) NULL DEFAULT '0.00',
	`update_log` VarChar( 10000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "image_tags" -------------------------------
-- CREATE TABLE "image_tags" -----------------------------------
CREATE TABLE `image_tags` ( 
	`id` Int( 12 ) AUTO_INCREMENT NOT NULL,
	`imgid` Int( 12 ) NOT NULL,
	`tag_name` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`instanceformid` Int( 12 ) NOT NULL,
	`pos_x` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`pos_y` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`pos_width` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`pos_height` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "images" -----------------------------------
-- CREATE TABLE "images" ---------------------------------------
CREATE TABLE `images` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`url` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "import_logs" ------------------------------
-- CREATE TABLE "import_logs" ----------------------------------
CREATE TABLE `import_logs` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`title` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`description` TinyText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`mime_type` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`filesize` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`filename` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`original_filename` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`errors` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`created_date` Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`status` TinyInt( 1 ) NOT NULL COMMENT '1=success, 9=failed',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "instancefieldbigtext" ---------------------
-- CREATE TABLE "instancefieldbigtext" -------------------------
CREATE TABLE `instancefieldbigtext` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`field` Text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "instancefields" ---------------------------
-- CREATE TABLE "instancefields" -------------------------------
CREATE TABLE `instancefields` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`instanceformid` Int( 11 ) NOT NULL,
	`fieldid` Int( 11 ) NOT NULL,
	`fieldname` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`value` VarChar( 5000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "instanceforms" ----------------------------
-- CREATE TABLE "instanceforms" --------------------------------
CREATE TABLE `instanceforms` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`assessid` Int( 11 ) NULL,
	`name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`description` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`htmltext` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`leaf` Int( 11 ) NULL,
	`color` Int( 11 ) NULL,
	`durationunit` Int( 11 ) NULL,
	`percentdone` Int( 11 ) NULL,
	`duration` Int( 11 ) NULL,
	`orgid` Int( 11 ) NOT NULL,
	`formid` Int( 11 ) NOT NULL,
	`createdid` Int( 11 ) NULL,
	`original_createdid` Int( 11 ) NOT NULL,
	`modifiedid` Int( 11 ) NULL,
	`assignedto` Int( 11 ) NULL,
	`assignedgroup` Int( 11 ) NULL,
	`date_created` DateTime NULL,
	`date_modified` DateTime NULL,
	`ownergroupid` Int( 11 ) NULL,
	`parentinstformid` Int( 11 ) NULL,
	`status` Int( 11 ) NULL,
	`duplicate` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`startdate` DateTime NULL,
	`nextactiondate` DateTime NULL,
	`emailaddress1` VarChar( 123 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`enddate` DateTime NULL,
	`cost` Float( 12, 0 ) NULL,
	`starpoints` Int( 11 ) NULL,
	`testerid` Int( 11 ) NULL,
	`testercode` VarChar( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`field3` Int( 10 ) NULL,
	`tags` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`category` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_bin NULL,
	`goals` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`kracategory` Int( 11 ) NULL,
	`krasubcategory` Int( 11 ) NULL,
	`observer` VarChar( 2000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`location` Int( 11 ) NOT NULL,
	`pod` TinyInt( 1 ) NOT NULL DEFAULT '0',
	`observeravatardel` VarChar( 2000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`observergroupdel` VarChar( 2000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`comment_moderator` TinyInt( 4 ) NULL DEFAULT '1',
	`reffield1` VarChar( 2000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`reffield2` VarChar( 2000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`reffield3` VarChar( 2000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`reffield4` VarChar( 2000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`reffield5` VarChar( 2000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f1` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f2` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f3` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f4` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f5` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f6` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f7` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f8` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f9` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f10` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f11` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f12` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f13` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f14` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f15` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f16` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f17` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f18` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f19` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f20` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f21` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f22` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f23` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f24` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f25` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f26` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f27` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f28` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f29` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`f30` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fbig1` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fbig2` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fbig3` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fbig4` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fbig5` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fbig6` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fbig7` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fbig8` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fbig9` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fbig10` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fbig11` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fbig12` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fbig13` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fbig14` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fbig15` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fbig16` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fbig17` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fbig18` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fbig19` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fbig20` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`locked` Int( 10 ) NULL DEFAULT '0',
	`points_flag` Int( 11 ) NOT NULL DEFAULT '0' COMMENT '0=>Points not awarded 1=>Points awarded',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "instanceforms_join" -----------------------
-- CREATE TABLE "instanceforms_join" ---------------------------
CREATE TABLE `instanceforms_join` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`instanceformid` Int( 11 ) NOT NULL,
	`g1` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g2` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g3` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g4` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g5` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g6` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g7` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g8` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g9` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g10` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g11` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g12` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g13` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g14` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g15` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g16` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g17` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g18` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g19` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g20` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g21` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g22` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g23` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g24` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g25` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g26` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g27` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g28` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g29` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g30` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g31` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g32` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g33` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g34` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g35` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g36` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g37` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g38` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g39` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`g40` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`gbig1` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`gbig2` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`gbig3` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`gbig4` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`gbig5` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`gbig6` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`gbig7` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`gbig8` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`gbig9` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`gbig10` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "instforms_files" --------------------------
-- CREATE TABLE "instforms_files" ------------------------------
CREATE TABLE `instforms_files` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`instanceformid` Int( 11 ) NULL,
	`messageid` Int( 11 ) NULL,
	`filename` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`viewflag` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
	`created` Int( 255 ) NOT NULL,
	`date_created` DateTime NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "instforms_files_tmp" ----------------------
-- CREATE TABLE "instforms_files_tmp" --------------------------
CREATE TABLE `instforms_files_tmp` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`formid` Int( 11 ) NULL,
	`avatarid` Int( 11 ) NULL,
	`filename` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "instforms_groups" -------------------------
-- CREATE TABLE "instforms_groups" -----------------------------
CREATE TABLE `instforms_groups` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`instformid` Int( 11 ) NOT NULL,
	`groupid` Int( 11 ) NOT NULL,
	`access` VarChar( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "instforms_links" --------------------------
-- CREATE TABLE "instforms_links" ------------------------------
CREATE TABLE `instforms_links` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`instanceformid` Int( 11 ) NOT NULL,
	`instanceformid_two` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "job" --------------------------------------
-- CREATE TABLE "job" ------------------------------------------
CREATE TABLE `job` ( 
	`id` Int( 11 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`job_tracker` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`job_type` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`job_executor` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`job_params` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`job_frequency_minutes` Int( 11 ) NOT NULL DEFAULT '0',
	`max_runs` Int( 3 ) NOT NULL DEFAULT '1',
	`num_of_runs` Int( 3 ) NOT NULL DEFAULT '0',
	`job_status` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'RUNNING',
	`is_job_in_progress` TinyInt( 1 ) NOT NULL DEFAULT '0',
	`date_created` Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`date_completed` Timestamp NULL,
	`last_exec_start_time` Timestamp NULL,
	`last_exec_end_time` Timestamp NULL,
	`last_exec_status` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`last_exec_details` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "kra_instanceform" -------------------------
-- CREATE TABLE "kra_instanceform" -----------------------------
CREATE TABLE `kra_instanceform` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`queryid` Int( 11 ) NOT NULL,
	`instanceformid` Int( 11 ) NOT NULL,
	`sequence` Int( 11 ) NOT NULL DEFAULT '1',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "layer_animations" -------------------------
-- CREATE TABLE "layer_animations" -----------------------------
CREATE TABLE `layer_animations` ( 
	`id` Int( 9 ) AUTO_INCREMENT NOT NULL,
	`handle` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`params` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`settings` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	CONSTRAINT `id` UNIQUE( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "leaderboard" ------------------------------
-- CREATE TABLE "leaderboard" ----------------------------------
CREATE TABLE `leaderboard` ( 
	`avatarid` Int( 20 ) NOT NULL,
	`goals` Double( 22, 0 ) NOT NULL,
	`starpoints` Int( 20 ) NOT NULL,
	`teamgoal` Double( 22, 0 ) NULL,
	`total` Double( 22, 0 ) NOT NULL,
	PRIMARY KEY ( `avatarid` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "leaderboard_log" --------------------------
-- CREATE TABLE "leaderboard_log" ------------------------------
CREATE TABLE `leaderboard_log` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`goals` Double( 22, 0 ) NOT NULL,
	`source_id` Int( 11 ) NOT NULL,
	`source` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'T=>Timesheet|I=>Instanceform|A=>Subordinate Avatarid',
	`update_date` DateTime NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "likes" ------------------------------------
-- CREATE TABLE "likes" ----------------------------------------
CREATE TABLE `likes` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`commentid` Int( 11 ) NOT NULL,
	`instanceformid` Int( 11 ) NULL,
	`type` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`groupid` Int( 11 ) NULL,
	`date_created` DateTime NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "links" ------------------------------------
-- CREATE TABLE "links" ----------------------------------------
CREATE TABLE `links` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NULL,
	`groupid` Int( 11 ) NULL,
	`orgid` Int( 11 ) NOT NULL,
	`name` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`type` VarChar( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`text` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`url` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "locations" --------------------------------
-- CREATE TABLE "locations" ------------------------------------
CREATE TABLE `locations` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`code` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`name` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "loss_run_client" --------------------------
-- CREATE TABLE "loss_run_client" ------------------------------
CREATE TABLE `loss_run_client` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`loss_date` Date NULL,
	`create_date` Date NULL,
	`create_user` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`loss_id` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`pol_seq` Int( 11 ) NULL,
	`policy_number` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`ins` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`cov` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`client_code` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`client_name` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`eff_date` Date NULL,
	`exp_date` Date NULL,
	`insured_name` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`typ` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`loss_type` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`rep_date` Date NULL,
	`loss_status` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`adjuster_assigned` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`loc` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`date_closed` Date NULL,
	`written_premium` Float( 11, 2 ) NULL,
	`annual_premium` Float( 11, 2 ) NULL,
	`description` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`last_entry_date` Date NULL,
	`org_id` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "loss_run_overall" -------------------------
-- CREATE TABLE "loss_run_overall" -----------------------------
CREATE TABLE `loss_run_overall` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`claim_number` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`claimant_name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`lob` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`loss_date` Date NULL,
	`carrier_report_date` Date NULL,
	`incurred_indemnity` Float( 10, 2 ) NULL,
	`incurred_medical` Float( 10, 2 ) NULL,
	`incurred_expense` Float( 10, 2 ) NULL,
	`total_incurred` Float( 10, 2 ) NULL,
	`location` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`paid_indemnity` Float( 10, 2 ) NULL,
	`paid_medical` Float( 10, 2 ) NULL,
	`pain_expense` Float( 10, 2 ) NULL,
	`total_paid` Float( 10, 2 ) NULL,
	`cause` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`status` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`jurisdiction_state` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`indemnity_or` Float( 10, 2 ) NULL,
	`medical_or` Float( 10, 2 ) NULL,
	`expense_or` Float( 10, 2 ) NULL,
	`outstanding_reserve` Float( 10, 2 ) NULL,
	`accident_state` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`catalyst` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`date_closed` Date NULL,
	`date_of_hire` Date NULL,
	`date_reopened` Date NULL,
	`litigation_status` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`lost_time_days` Int( 11 ) NULL,
	`nature_of_injury` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`part_of_body` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`policy_number` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`org_id` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`premium` Float( 10, 2 ) NULL,
	`last_rundate` Date NULL,
	`loss_type` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`client_code` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "mapping_table" ----------------------------
-- CREATE TABLE "mapping_table" --------------------------------
CREATE TABLE `mapping_table` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`type` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`keyvalue` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`value` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "master_flags" -----------------------------
-- CREATE TABLE "master_flags" ---------------------------------
CREATE TABLE `master_flags` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`flag` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`value` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "matrix_config" ----------------------------
-- CREATE TABLE "matrix_config" --------------------------------
CREATE TABLE `matrix_config` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`instanceformid` Int( 11 ) NULL,
	`type` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`name_prefix` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`formid` Int( 11 ) NULL,
	`fieldid` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fieldname` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`source` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "matrix_days" ------------------------------
-- CREATE TABLE "matrix_days" ----------------------------------
CREATE TABLE `matrix_days` ( 
	`id` Int( 11 ) NOT NULL,
	`matrix_date` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "matrix_months" ----------------------------
-- CREATE TABLE "matrix_months" --------------------------------
CREATE TABLE `matrix_months` ( 
	`id` Int( 11 ) NOT NULL,
	`matrix_month` Int( 11 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "matrix_quarter" ---------------------------
-- CREATE TABLE "matrix_quarter" -------------------------------
CREATE TABLE `matrix_quarter` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`quarter` Int( 11 ) NOT NULL,
	`name` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "matrix_years" -----------------------------
-- CREATE TABLE "matrix_years" ---------------------------------
CREATE TABLE `matrix_years` ( 
	`id` Int( 11 ) NOT NULL,
	`matrix_years` Int( 11 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "menus" ------------------------------------
-- CREATE TABLE "menus" ----------------------------------------
CREATE TABLE `menus` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "menus_avatars" ----------------------------
-- CREATE TABLE "menus_avatars" --------------------------------
CREATE TABLE `menus_avatars` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`canAvatars` TinyInt( 1 ) NOT NULL,
	`avatarsgroupid` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canGroups` TinyInt( 1 ) NOT NULL,
	`groupsgroupid` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canAlert` TinyInt( 1 ) NOT NULL,
	`alertgroupid` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canAnnouncement` TinyInt( 1 ) NOT NULL,
	`announcementgroupid` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canMenus` TinyInt( 1 ) NOT NULL,
	`menusgroupid` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canLinks` TinyInt( 1 ) NOT NULL,
	`linksgroupid` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canTiles` TinyInt( 1 ) NOT NULL,
	`tilesgroupid` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canAssesments` TinyInt( 1 ) NOT NULL,
	`assesmentsgroupid` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canFeature` TinyInt( 1 ) NOT NULL,
	`featuregroupid` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canExport` TinyInt( 1 ) NOT NULL,
	`exportgroupid` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canImport` TinyInt( 1 ) NOT NULL,
	`importgroupid` VarChar( 10000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canEscalations` TinyInt( 1 ) NOT NULL,
	`escalationsgroupid` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canFlash` TinyInt( 4 ) NOT NULL,
	`flashgroupid` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canWorkflow` TinyInt( 4 ) NOT NULL,
	`workflowgroupid` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canKra` TinyInt( 4 ) NOT NULL,
	`kragroupid` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canPolls` TinyInt( 4 ) NOT NULL,
	`pollsgroupid` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canGamepoint` TinyInt( 4 ) NOT NULL,
	`gamepointgroupid` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canPodview` TinyInt( 4 ) NOT NULL,
	`podviewgroupid` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canContractrate` TinyInt( 4 ) NOT NULL,
	`canCarveoutsCostContract` TinyInt( 4 ) NOT NULL,
	`canPrimaryPhysician` TinyInt( 4 ) NOT NULL,
	`canReferPhysician` TinyInt( 4 ) NOT NULL,
	`canCostGroup` TinyInt( 4 ) NOT NULL,
	`canExecdesktop` TinyInt( 4 ) NOT NULL,
	`execdesktopgroups` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canMatrix` TinyInt( 4 ) NOT NULL,
	`matrixgroup` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canTimesheet` TinyInt( 4 ) NOT NULL,
	`timesheetgroup` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canCustomlistview` TinyInt( 4 ) NOT NULL,
	`customlistviewgroup` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canAppmatrix` TinyInt( 4 ) NOT NULL,
	`appmartixgroup` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canManagemodule` TinyInt( 4 ) NOT NULL,
	`modulegroup` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canmonitorusers` TinyInt( 4 ) NOT NULL,
	`monitorusersgroup` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`createdemolink` TinyInt( 4 ) NOT NULL DEFAULT '0',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "message_attachments" ----------------------
-- CREATE TABLE "message_attachments" --------------------------
CREATE TABLE `message_attachments` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`messageid` Int( 11 ) NOT NULL,
	`instanceformid` Int( 11 ) NOT NULL,
	`friendly_url` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "message_recepients" -----------------------
-- CREATE TABLE "message_recepients" ---------------------------
CREATE TABLE `message_recepients` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`messageid` Int( 11 ) NOT NULL,
	`toid` Int( 11 ) NOT NULL,
	`status` TinyInt( 1 ) NOT NULL,
	`message_status` Int( 1 ) NULL DEFAULT '0',
	`label` Int( 2 ) NOT NULL DEFAULT '1',
	`date_moved` DateTime NULL,
	`cc_bcc_flag` Int( 11 ) NOT NULL DEFAULT '0',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "messages" ---------------------------------
-- CREATE TABLE "messages" -------------------------------------
CREATE TABLE `messages` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`fromid` Int( 11 ) NOT NULL,
	`subject` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`message` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`replyid` Int( 11 ) NULL DEFAULT '0',
	`date_created` DateTime NOT NULL,
	`setflag` TinyInt( 4 ) NOT NULL DEFAULT '0',
	`tags` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`externalemail` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`ccemaillist` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`bccemaillist` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`old_message` Int( 1 ) NOT NULL DEFAULT '0',
	`instanceformid` Int( 11 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "meta_import" ------------------------------
-- CREATE TABLE "meta_import" ----------------------------------
CREATE TABLE `meta_import` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`description` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`type` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`existingkeycolumn` Int( 11 ) NULL,
	`terminationdatecolno` TinyInt( 4 ) NULL,
	`datatype` Int( 11 ) NULL,
	`tablename` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`existingflag` TinyInt( 4 ) NULL,
	`existingkeyfield` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`wizardids` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "meta_importcolumns" -----------------------
-- CREATE TABLE "meta_importcolumns" ---------------------------
CREATE TABLE `meta_importcolumns` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`importid` Int( 11 ) NOT NULL,
	`columnnumber` Int( 11 ) NULL,
	`fieldname` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`fieldtype` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`expression` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`lookup` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`wizardid` Int( 15 ) NULL,
	`multiple` VarChar( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "meta_multiselect" -------------------------
-- CREATE TABLE "meta_multiselect" -----------------------------
CREATE TABLE `meta_multiselect` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`instanceformid` Int( 11 ) NOT NULL,
	`fieldid` Int( 11 ) NOT NULL,
	`selectedid` Int( 3 ) NOT NULL,
	`selectedvalue` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "metafields" -------------------------------
-- CREATE TABLE "metafields" -----------------------------------
CREATE TABLE `metafields` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`sequence` Int( 11 ) NOT NULL,
	`formid` Int( 11 ) NOT NULL,
	`name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`text` VarChar( 400 ) CHARACTER SET ucs2 COLLATE ucs2_general_ci NOT NULL,
	`columnname` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`helpertext` VarChar( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`type` VarChar( 30 ) CHARACTER SET ucs2 COLLATE ucs2_general_ci NULL,
	`options` VarChar( 10000 ) CHARACTER SET ucs2 COLLATE ucs2_general_ci NULL,
	`color` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`dependson` VarChar( 50 ) CHARACTER SET ucs2 COLLATE ucs2_general_ci NULL,
	`disablejavascript` TinyInt( 4 ) NULL DEFAULT '0',
	`required` TinyInt( 1 ) NULL DEFAULT '0',
	`regexpvalidator` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`validationtext` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`specialvalidator` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`readonly` TinyInt( 1 ) NULL DEFAULT '0',
	`expression` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canbehidden` TinyInt( 4 ) NOT NULL DEFAULT '0',
	`dateordependson` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`onlyownrcanchng` TinyInt( 1 ) NULL DEFAULT '0',
	`condition` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`premiumname` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`xflat_parameter` Int( 2 ) NOT NULL DEFAULT '0',
	`esign_parameter` Int( 11 ) NOT NULL DEFAULT '0' COMMENT 'this field will be used in esign api',
	`field_type` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'config',
	`category` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1',
	`field_value` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`display` VarChar( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "metaform_fieldorder" ----------------------
-- CREATE TABLE "metaform_fieldorder" --------------------------
CREATE TABLE `metaform_fieldorder` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`fieldname` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`formid` Int( 11 ) NULL,
	`fieldorder` Int( 3 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "metaforms" --------------------------------
-- CREATE TABLE "metaforms" ------------------------------------
CREATE TABLE `metaforms` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`canhaveemail` TinyInt( 1 ) NOT NULL,
	`name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`description` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`canhavedigitalsign` TinyInt( 1 ) NULL,
	`canhavecategory` TinyInt( 1 ) NULL,
	`sequence` Int( 11 ) NOT NULL,
	`nextsequence` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`htmltext` LongText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`orgid` Int( 11 ) NOT NULL,
	`moduleid` Int( 11 ) NOT NULL,
	`canhaveparent` TinyInt( 1 ) NULL DEFAULT '0',
	`canhavespreadsheet` TinyInt( 1 ) NOT NULL DEFAULT '1',
	`makeparentmandatory` TinyInt( 1 ) NULL DEFAULT '0',
	`canassign` TinyInt( 1 ) NULL,
	`canassigngroup` TinyInt( 4 ) NULL,
	`canhidetime` TinyInt( 1 ) NULL DEFAULT '0',
	`canmultiassign` TinyInt( 1 ) NULL,
	`onlyadmincancreate` TinyInt( 4 ) NULL DEFAULT '0',
	`statusfield` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`emailfields` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`printfields` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`defaultassigngroup` Int( 11 ) NULL,
	`canhaveattachment` TinyInt( 4 ) NOT NULL,
	`canhavewriteaccess` TinyInt( 4 ) NOT NULL,
	`canhavereadaccess` TinyInt( 4 ) NOT NULL,
	`can_create_duplicate` TinyInt( 1 ) NOT NULL COMMENT 'create copy of the form on create (smith featuring)',
	`nodelete` TinyInt( 1 ) NOT NULL COMMENT 'remove delete button with a value 1',
	`hidestarpoints` TinyInt( 1 ) NOT NULL,
	`hidecost` TinyInt( 1 ) NOT NULL,
	`startdatefield` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`nextactiondatefield` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`enddatefield` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`assignedtoview` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'view',
	`assignedtofromdefaultgroup` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`statuslist` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`statuslistcolor` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`hidetags` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`hideleveldifference` Int( 4 ) NULL,
	`showallassignedgroup` TinyInt( 1 ) NOT NULL DEFAULT '0',
	`showallownergroup` TinyInt( 1 ) NOT NULL DEFAULT '0',
	`kracategories` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`krasubcategories` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`wizard_id` Int( 12 ) NULL,
	`canhavekra` TinyInt( 4 ) NULL DEFAULT '0',
	`goals` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`type` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`customcreate` Int( 11 ) NULL,
	`customview` Int( 11 ) NULL,
	`disable_mupdate` TinyInt( 4 ) NOT NULL DEFAULT '0',
	`disable_inlineedit` TinyInt( 4 ) NOT NULL DEFAULT '0',
	`discussionStartCount` Int( 3 ) NULL,
	`allow_moderator` Int( 11 ) NULL DEFAULT '0' COMMENT 'Allow Comments Moderaor',
	`disable_calendar` TinyInt( 4 ) NULL DEFAULT '0',
	`can_have_map` TinyInt( 4 ) NULL DEFAULT '0',
	`emailaddress` TinyInt( 11 ) NOT NULL,
	`reffield1` Int( 11 ) NULL,
	`reffield2` Int( 11 ) NULL,
	`reffield3` Int( 11 ) NULL,
	`reffield4` Int( 11 ) NULL,
	`reffield5` Int( 11 ) NULL,
	`template` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`canvas` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`formdeleteaccess` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`defaultgroupaccess` Int( 11 ) NULL,
	`nextactiondatediff` Int( 11 ) NULL,
	`enddatediff` Int( 11 ) NULL,
	`ownerassignedcanedit` TinyInt( 4 ) NULL,
	`cancopywizardvalues` TinyInt( 1 ) NOT NULL,
	`category` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1=>General',
	`can_have_print` TinyInt( 1 ) NULL DEFAULT '1',
	`can_have_copyURL` TinyInt( 1 ) NULL DEFAULT '1',
	`can_have_pm` TinyInt( 1 ) NULL DEFAULT '1',
	`can_have_logofactivities` TinyInt( 1 ) NULL DEFAULT '1',
	`can_have_stickynotes` TinyInt( 1 ) NULL DEFAULT '1',
	`can_have_lockrecord` TinyInt( 1 ) NULL DEFAULT '1',
	`can_have_convert` TinyInt( 1 ) NULL DEFAULT '1',
	`can_have_edit` TinyInt( 1 ) NULL DEFAULT '1',
	`can_have_message` TinyInt( 1 ) NULL DEFAULT '1',
	`can_have_like` TinyInt( 1 ) NULL DEFAULT '1',
	`can_have_comments` TinyInt( 1 ) NULL DEFAULT '1',
	`can_have_workrelated` TinyInt( 1 ) NULL DEFAULT '1',
	`can_have_spreadsheet` TinyInt( 1 ) NULL DEFAULT '1',
	`can_have_assignments` TinyInt( 1 ) NULL DEFAULT '1',
	`can_have_quick_edit` TinyInt( 1 ) NULL DEFAULT '1',
	`can_have_dyn_info_view` TinyInt( 1 ) NULL DEFAULT '1',
	`fieldview` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'col-md-3',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "metalist" ---------------------------------
-- CREATE TABLE "metalist" -------------------------------------
CREATE TABLE `metalist` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`value` LongText CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "metapdffields" ----------------------------
-- CREATE TABLE "metapdffields" --------------------------------
CREATE TABLE `metapdffields` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`pdfformid` Int( 11 ) NOT NULL,
	`page` Int( 11 ) NOT NULL,
	`xcoord` Int( 11 ) NOT NULL,
	`ycoord` Int( 11 ) NOT NULL,
	`field` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`expression` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fontsize` TinyInt( 4 ) NULL,
	`fontcolor` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`tablename` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`multiple` TinyInt( 1 ) NOT NULL DEFAULT '0',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "metapdfforms" -----------------------------
-- CREATE TABLE "metapdfforms" ---------------------------------
CREATE TABLE `metapdfforms` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`name` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`description` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`modulename` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`filename` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`condition` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`tables` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`type` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`wizardid` Int( 11 ) NULL,
	`customfunction` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`multiple` TinyInt( 1 ) NOT NULL DEFAULT '0',
	`multiplefieldname` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`sequence` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`formid` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "metareportfields" -------------------------
-- CREATE TABLE "metareportfields" -----------------------------
CREATE TABLE `metareportfields` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`reportid` Int( 11 ) NOT NULL,
	`formid` Int( 11 ) NOT NULL,
	`fieldid` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`expression` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "metareports" ------------------------------
-- CREATE TABLE "metareports" ----------------------------------
CREATE TABLE `metareports` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`description` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`clientid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "metastatus" -------------------------------
-- CREATE TABLE "metastatus" -----------------------------------
CREATE TABLE `metastatus` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`formid` Int( 11 ) NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`statusvalue` Int( 11 ) NULL,
	`statusname` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	PRIMARY KEY ( `id` ),
	CONSTRAINT `Composite` UNIQUE( `formid`, `orgid`, `statusvalue` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "migrations" -------------------------------
-- CREATE TABLE "migrations" -----------------------------------
CREATE TABLE `migrations` ( 
	`version` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	PRIMARY KEY ( `version` ) )
CHARACTER SET = utf8
COLLATE = utf8_unicode_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "mletquestions" ----------------------------
-- CREATE TABLE "mletquestions" --------------------------------
CREATE TABLE `mletquestions` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`questiontext` VarChar( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`parameters` VarChar( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`queryconfigid` Int( 11 ) NULL,
	`html` LongText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`groupid` Int( 11 ) NULL,
	`orgid` Int( 11 ) NOT NULL,
	`mletlist` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`where_used` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`description` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`templateid` Int( 11 ) NULL,
	`directsql` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "mod_training_app_data" --------------------
-- CREATE TABLE "mod_training_app_data" ------------------------
CREATE TABLE `mod_training_app_data` ( 
	`id` TinyInt( 4 ) NOT NULL,
	`assessid` TinyInt( 4 ) NOT NULL,
	`name` TinyInt( 4 ) NOT NULL,
	`description` TinyInt( 4 ) NOT NULL,
	`htmltext` TinyInt( 4 ) NOT NULL,
	`orgid` TinyInt( 4 ) NOT NULL,
	`formid` TinyInt( 4 ) NOT NULL,
	`createdid` TinyInt( 4 ) NOT NULL,
	`modifiedid` TinyInt( 4 ) NOT NULL,
	`assignedto` TinyInt( 4 ) NOT NULL,
	`assignedgroup` TinyInt( 4 ) NOT NULL,
	`date_created` TinyInt( 4 ) NOT NULL,
	`date_modified` TinyInt( 4 ) NOT NULL,
	`ownergroupid` TinyInt( 4 ) NOT NULL,
	`parentinstformid` TinyInt( 4 ) NOT NULL,
	`status` TinyInt( 4 ) NOT NULL,
	`duplicate` TinyInt( 4 ) NOT NULL,
	`startdate` TinyInt( 4 ) NOT NULL,
	`nextactiondate` TinyInt( 4 ) NOT NULL,
	`enddate` TinyInt( 4 ) NOT NULL,
	`cost` TinyInt( 4 ) NOT NULL,
	`starpoints` TinyInt( 4 ) NOT NULL,
	`testerid` TinyInt( 4 ) NOT NULL,
	`testercode` TinyInt( 4 ) NOT NULL,
	`field3` TinyInt( 4 ) NOT NULL,
	`tags` TinyInt( 4 ) NOT NULL,
	`category` TinyInt( 4 ) NOT NULL,
	`goals` TinyInt( 4 ) NOT NULL,
	`kracategory` TinyInt( 4 ) NOT NULL,
	`krasubcategory` TinyInt( 4 ) NOT NULL,
	`observer` TinyInt( 4 ) NOT NULL,
	`region` TinyInt( 4 ) NOT NULL,
	`centre` TinyInt( 4 ) NOT NULL,
	`batchcode` TinyInt( 4 ) NOT NULL,
	`rollno` TinyInt( 4 ) NOT NULL,
	`Type` TinyInt( 4 ) NOT NULL,
	`sprint` TinyInt( 4 ) NOT NULL,
	`teachers` TinyInt( 4 ) NOT NULL,
	`grade` TinyInt( 4 ) NOT NULL,
	`date` TinyInt( 4 ) NOT NULL,
	`trackerupdate` TinyInt( 4 ) NOT NULL,
	`schedule_conformance` TinyInt( 4 ) NOT NULL,
	`attendance` TinyInt( 4 ) NOT NULL,
	`assignment` TinyInt( 4 ) NOT NULL,
	`dormitory` TinyInt( 4 ) NOT NULL,
	`lastsprintscore` TinyInt( 4 ) NOT NULL,
	`lastsprintstatus` TinyInt( 4 ) NOT NULL,
	`Tollgate` TinyInt( 4 ) NOT NULL,
	`tollgatestatus` TinyInt( 4 ) NOT NULL,
	`MOP` TinyInt( 4 ) NOT NULL,
	`fees` TinyInt( 4 ) NOT NULL )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "module_map_category" ----------------------
-- CREATE TABLE "module_map_category" --------------------------
CREATE TABLE `module_map_category` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`moduleid` Int( 11 ) NOT NULL,
	`categoryid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "modulecategories" -------------------------
-- CREATE TABLE "modulecategories" -----------------------------
CREATE TABLE `modulecategories` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`color` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`sequence` Int( 11 ) NOT NULL,
	`orgid` Int( 10 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "modules" ----------------------------------
-- CREATE TABLE "modules" --------------------------------------
CREATE TABLE `modules` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`description` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`sequence` Int( 11 ) NOT NULL,
	`htmltext` LongText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`type` VarChar( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`viewtype` VarChar( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`customname` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`logo` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`email` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Active',
	`appcolor` VarChar( 111 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'blue',
	`helppdf` VarChar( 11 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`matrix_reference_name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`hidepivotgrid0` TinyInt( 4 ) NULL,
	`hidepivotgrid1` TinyInt( 4 ) NULL,
	`hidepivotgrid2` TinyInt( 4 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "navigations" ------------------------------
-- CREATE TABLE "navigations" ----------------------------------
CREATE TABLE `navigations` ( 
	`id` Int( 9 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 191 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`handle` VarChar( 191 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`css` LongText CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`markup` LongText CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`settings` LongText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	CONSTRAINT `id` UNIQUE( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "non_compliance" ---------------------------
-- CREATE TABLE "non_compliance" -------------------------------
CREATE TABLE `non_compliance` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`assesmentid` Int( 11 ) NOT NULL,
	`instanceformid` Int( 12 ) NOT NULL,
	`name` VarChar( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`avatar` Int( 12 ) NOT NULL,
	`Parent` Int( 12 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
COMMENT 'This table will help to make everything non complaint whatever needed '
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "oauth2_setting" ---------------------------
-- CREATE TABLE "oauth2_setting" -------------------------------
CREATE TABLE `oauth2_setting` ( 
	`id` Int( 11 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`userid` Int( 11 ) NOT NULL,
	`email` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`provider` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`credentials` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`refresh_token` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`calendarflag` TinyInt( 4 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "observers" --------------------------------
-- CREATE TABLE "observers" ------------------------------------
CREATE TABLE `observers` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarids` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`groupid` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`group_avatars` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`external_emails` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`instanceformid` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "offboarding_audit" ------------------------
-- CREATE TABLE "offboarding_audit" ----------------------------
CREATE TABLE `offboarding_audit` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL DEFAULT '0',
	`avatarid` Int( 11 ) NOT NULL DEFAULT '0' COMMENT 'the person who had done the offboarding',
	`status` TinyInt( 1 ) NOT NULL COMMENT '1=export completed, 2=deactivate users, 3=deactivate groups, 4=deactivated organization,5=remove from groups, 6=remove as observer',
	`datetime` Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
COMMENT 'Based on offboarding, that particular orgid, status and the avatarid will be recorded in this table.'
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "operatingrhythm" --------------------------
-- CREATE TABLE "operatingrhythm" ------------------------------
CREATE TABLE `operatingrhythm` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`summary` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`startdate` Timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT '0000-00-00 00:00:00',
	`enddate` Timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	`organizer` Int( 11 ) NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`type` Int( 255 ) NULL,
	`instanceformid` Int( 11 ) NULL,
	`groupid` Int( 11 ) NULL,
	`reid` Int( 11 ) NULL,
	`rrule` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`rexception` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`location` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`reminderperiod` Int( 255 ) NULL,
	`emails` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "operatingrhythm_avatars" ------------------
-- CREATE TABLE "operatingrhythm_avatars" ----------------------
CREATE TABLE `operatingrhythm_avatars` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`operatingrhythmid` Int( 11 ) NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "operatingrhythm_groups" -------------------
-- CREATE TABLE "operatingrhythm_groups" -----------------------
CREATE TABLE `operatingrhythm_groups` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`groupid` Int( 11 ) NOT NULL,
	`operatingrhythmid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "or_attendees" -----------------------------
-- CREATE TABLE "or_attendees" ---------------------------------
CREATE TABLE `or_attendees` ( 
	`id` Int( 255 ) AUTO_INCREMENT NOT NULL,
	`eventid` Int( 255 ) NOT NULL,
	`avatarid` Int( 255 ) NOT NULL,
	`status` Int( 255 ) NOT NULL,
	`reminder` Int( 255 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "or_meta" ----------------------------------
-- CREATE TABLE "or_meta" --------------------------------------
CREATE TABLE `or_meta` ( 
	`id` Int( 255 ) AUTO_INCREMENT NOT NULL,
	`eventtype` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`eventid` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`repeat_start` Date NULL,
	`repeat_end` Date NULL,
	`repeat_interval` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`repeat_year` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '*',
	`repeat_month` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '*',
	`repeat_day` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '*',
	`repeat_week` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '*',
	`repeat_weekday` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '*',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "org_role" ---------------------------------
-- CREATE TABLE "org_role" -------------------------------------
CREATE TABLE `org_role` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`org_role` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`hiddentopicons` TinyInt( 1 ) NOT NULL,
	`hidetiles` TinyInt( 1 ) NOT NULL,
	`hidewall` TinyInt( 1 ) NOT NULL,
	`hideannouncement` TinyInt( 1 ) NOT NULL,
	`hideleaderboard` TinyInt( 1 ) NOT NULL,
	`hiddenmessage` TinyInt( 1 ) NULL,
	`hiddenassignment` TinyInt( 1 ) NULL,
	`hiddenfollowup` TinyInt( 1 ) NULL,
	`hiddencreate` TinyInt( 1 ) NULL,
	`hiddensearch` TinyInt( 1 ) NULL,
	`hiddengroup` TinyInt( 1 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "organizations" ----------------------------
-- CREATE TABLE "organizations" --------------------------------
CREATE TABLE `organizations` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`address` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`city` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`state` VarChar( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`zip` VarChar( 5 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`logo` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`defaultgroupid` Int( 11 ) NOT NULL,
	`statusbox` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Matrix|MyKRA|StarPoints|Alerts',
	`labelfile` VarChar( 40 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`messagecount` Int( 12 ) NULL DEFAULT '200',
	`languagefile` VarChar( 40 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'en',
	`orgtype` TinyInt( 1 ) NOT NULL DEFAULT '0',
	`flash_msg` Int( 11 ) NULL DEFAULT '0',
	`email` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Active',
	`themes` TinyInt( 4 ) NOT NULL DEFAULT '0',
	`formview` TinyInt( 4 ) NOT NULL DEFAULT '0',
	`assign_followuplimit` Int( 11 ) NOT NULL DEFAULT '10',
	`insurelearn` TinyInt( 4 ) NOT NULL DEFAULT '0',
	`reset_password` Int( 11 ) NULL DEFAULT '0',
	`status` Enum( 'Active', 'Inactive' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Active',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "orgclientlist" ----------------------------
-- CREATE TABLE "orgclientlist" --------------------------------
CREATE TABLE `orgclientlist` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`clientid` Int( 11 ) NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`clientname` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "orgs_modules" -----------------------------
-- CREATE TABLE "orgs_modules" ---------------------------------
CREATE TABLE `orgs_modules` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`moduleid` Int( 11 ) NOT NULL,
	`email` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Active',
	`instanceformid` Int( 11 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "orgs_partners" ----------------------------
-- CREATE TABLE "orgs_partners" --------------------------------
CREATE TABLE `orgs_partners` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`partnerid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------

-- ---------------------------------------------------------


-- CREATE TABLE "oxmedia_devices" --------------------------
-- CREATE TABLE "oxmedia_devices" ------------------------------
CREATE TABLE `oxmedia_devices` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`device_name` VarChar( 400 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`device_id` VarChar( 400 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`description` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "oxmedia_devices_sliders" ------------------
-- CREATE TABLE "oxmedia_devices_sliders" ----------------------
CREATE TABLE `oxmedia_devices_sliders` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`sliderid` Int( 11 ) NOT NULL,
	`deviceid` Int( 11 ) NOT NULL,
	`enable` TinyInt( 1 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "oxmedia_playlist" -------------------------
-- CREATE TABLE "oxmedia_playlist" -----------------------------
CREATE TABLE `oxmedia_playlist` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`title` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`socialmedia` Int( 5 ) NULL COMMENT '1=>Twitter|2=>Instagram',
	`hashtag` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`watermark` TinyInt( 1 ) NOT NULL,
	`medialocation` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`venue_id` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "oxmedia_slides" ---------------------------
-- CREATE TABLE "oxmedia_slides" -------------------------------
CREATE TABLE `oxmedia_slides` ( 
	`id` Int( 6 ) AUTO_INCREMENT NOT NULL,
	`slider_id` Int( 6 ) NOT NULL,
	`name` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`type` Int( 5 ) NOT NULL COMMENT '0=>image,1=>video,2=>mlet',
	`instformid` Int( 11 ) NULL,
	`alertid` Int( 11 ) NULL,
	`duration` Int( 10 ) NULL,
	`enable` Int( 5 ) NOT NULL,
	`medialocation` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`options` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`sequence_no` Int( 11 ) NULL,
	`socialmediaid` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`endtime` DateTime NULL,
	`starttime` DateTime NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "podview" ----------------------------------
-- CREATE TABLE "podview" --------------------------------------
CREATE TABLE `podview` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`title` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`instanceform` Int( 11 ) NOT NULL,
	`type` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`color` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`url` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`position` VarChar( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`sequence` Int( 11 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "poll_answers" -----------------------------
-- CREATE TABLE "poll_answers" ---------------------------------
CREATE TABLE `poll_answers` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`pollid` Int( 11 ) NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`answer` TinyInt( 1 ) NOT NULL,
	`modifieddate` DateTime NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "poll_questions" ---------------------------
-- CREATE TABLE "poll_questions" -------------------------------
CREATE TABLE `poll_questions` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`pollid` Int( 11 ) NOT NULL,
	`question` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`creatorid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "projects" ---------------------------------
-- CREATE TABLE "projects" -------------------------------------
CREATE TABLE `projects` ( 
	`prid` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`realname` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`owner` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`priority` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`remarks` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`status` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `prid` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "queries" ----------------------------------
-- CREATE TABLE "queries" --------------------------------------
CREATE TABLE `queries` ( 
	`id` Int( 11 ) NOT NULL,
	`name` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`type` Int( 50 ) NOT NULL,
	`querytext` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`resulttype` Int( 11 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "query_config" -----------------------------
-- CREATE TABLE "query_config" ---------------------------------
CREATE TABLE `query_config` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`question_text` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`question_name` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`source` Int( 11 ) NULL COMMENT 'the source of data 1=>instanceform|2=>Timesheet',
	`sourceoption` VarChar( 10000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'main filter parameter like formid for instancerform',
	`type` Int( 11 ) NOT NULL COMMENT 'where this query is used',
	`configs` VarChar( 10000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`link` VarChar( 10000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`orgid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "questionqueries" --------------------------
-- CREATE TABLE "questionqueries" ------------------------------
CREATE TABLE `questionqueries` ( 
	`id` Int( 11 ) NOT NULL,
	`resultkey` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`questionid` Int( 11 ) NOT NULL,
	`queryid` Int( 11 ) NOT NULL,
	`parameters` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "questions" --------------------------------
-- CREATE TABLE "questions" ------------------------------------
CREATE TABLE `questions` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`questiontext` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`parameters` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`instanceform` Int( 11 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "relationshipinstance" ---------------------
-- CREATE TABLE "relationshipinstance" -------------------------
CREATE TABLE `relationshipinstance` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`relationshipid` Int( 11 ) NOT NULL,
	`instanceformidfrom` Int( 11 ) NOT NULL,
	`instanceformidto` Int( 11 ) NOT NULL,
	`f1` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`f2` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`f3` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`f4` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`f5` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`f6` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`f7` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`f8` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "relationships" ----------------------------
-- CREATE TABLE "relationships" --------------------------------
CREATE TABLE `relationships` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`metaformidfrom` Int( 11 ) NOT NULL,
	`metaformidto` Int( 11 ) NOT NULL,
	`displayfields` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`reffieldname` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`labelfrom` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`labelto` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`instanceformfields` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`fieldmapping` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`options` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`relationtype` Int( 11 ) NOT NULL,
	`parentrelation` Int( 11 ) NULL,
	`mappertype` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`fieldmultiplier` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`required` VarChar( 11 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'false',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "rpt_cluster4" -----------------------------
-- CREATE TABLE "rpt_cluster4" ---------------------------------
CREATE TABLE `rpt_cluster4` ( 
	`id` Int( 11 ) NOT NULL,
	`date` Date NULL,
	`received_calls` Int( 11 ) NULL,
	`abandoned_calls` Int( 11 ) NULL,
	`answered_calls` Int( 11 ) NULL,
	`average_per` Float( 12, 0 ) NULL,
	`answered_sl` Int( 11 ) NULL,
	`service_level_per` Float( 12, 0 ) NULL,
	`aht` Int( 11 ) NULL,
	`hold_time` Int( 11 ) NULL,
	`acw` Int( 11 ) NULL,
	`att` Int( 11 ) NULL,
	`idle_time` Int( 11 ) NULL,
	`held_calls_per` Float( 12, 0 ) NULL,
	`held_calls` Int( 11 ) NULL,
	`abandoned_per` Float( 12, 0 ) NULL,
	`asa` Float( 12, 0 ) NULL,
	`client` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`report` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "rpt_data" ---------------------------------
-- CREATE TABLE "rpt_data" -------------------------------------
CREATE TABLE `rpt_data` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`client` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`aht` Float( 12, 0 ) NOT NULL,
	`acwt` Float( 12, 0 ) NOT NULL,
	`att` Float( 12, 0 ) NOT NULL,
	`asa` Float( 12, 0 ) NOT NULL,
	`call_date` Date NULL,
	`service_level_per` Float( 12, 0 ) NULL,
	`idle_time` Int( 11 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "rpt_volvo" --------------------------------
-- CREATE TABLE "rpt_volvo" ------------------------------------
CREATE TABLE `rpt_volvo` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`call_date` Date NULL,
	`start_time` Time NULL,
	`end_time` Time NULL,
	`calls_received` Int( 11 ) NULL,
	`calls_answered` Int( 11 ) NULL,
	`acc_call_ans` Float( 12, 0 ) NULL,
	`ans_per` Float( 12, 0 ) NULL,
	`service_level` Float( 12, 0 ) NULL,
	`aht` Int( 11 ) NULL,
	`aban_call` Int( 11 ) NULL,
	`client` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "rules" ------------------------------------
-- CREATE TABLE "rules" ----------------------------------------
CREATE TABLE `rules` ( 
	`idrules` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`rulename` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`procedurename` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `idrules` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "sales" ------------------------------------
-- CREATE TABLE "sales" ----------------------------------------
CREATE TABLE `sales` ( 
	`Date` Date NOT NULL,
	`KeyMetrics` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`FinalParameter` Decimal( 15, 2 ) NULL,
	`Qty` Int( 11 ) NULL,
	`ActualRevenue` Decimal( 15, 2 ) NULL,
	`ActualRevenueChange` Decimal( 15, 2 ) NULL,
	`ActualMargin` Decimal( 15, 2 ) NULL,
	`ActualMarginChange` Decimal( 15, 2 ) NULL,
	`PlanRevenue` Decimal( 15, 2 ) NULL,
	`PlanRevenueChange` Decimal( 15, 2 ) NULL,
	`PlanMargin` Decimal( 15, 2 ) NULL,
	`PlanMarginChange` Decimal( 15, 2 ) NULL,
	`ClientID` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`FYYear` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `Date`, `KeyMetrics` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "sales_pipeline_kra" -----------------------
-- CREATE TABLE "sales_pipeline_kra" ---------------------------
CREATE TABLE `sales_pipeline_kra` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`date` Date NOT NULL,
	`account_target` Int( 11 ) NOT NULL,
	`amount_target` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "sessions" ---------------------------------
-- CREATE TABLE "sessions" -------------------------------------
CREATE TABLE `sessions` ( 
	`session_id` VarChar( 40 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
	`ip_address` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
	`user_agent` VarChar( 120 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`last_activity` Int( 10 ) UNSIGNED NOT NULL DEFAULT '0',
	`user_data` MediumText CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `session_id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "sla" --------------------------------------
-- CREATE TABLE "sla" ------------------------------------------
CREATE TABLE `sla` ( 
	`id` Int( 11 ) NOT NULL,
	`groupid` Int( 11 ) NULL,
	`slacol` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`date` Date NULL,
	`target` Int( 11 ) NULL,
	`status` VarChar( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`error` Int( 10 ) NULL,
	`comment` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "sliders" ----------------------------------
-- CREATE TABLE "sliders" --------------------------------------
CREATE TABLE `sliders` ( 
	`id` Int( 9 ) AUTO_INCREMENT NOT NULL,
	`title` TinyText CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`alias` TinyText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`params` LongText CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`settings` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`type` VarChar( 191 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	CONSTRAINT `id` UNIQUE( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "slides" -----------------------------------
-- CREATE TABLE "slides" ---------------------------------------
CREATE TABLE `slides` ( 
	`id` Int( 9 ) AUTO_INCREMENT NOT NULL,
	`slider_id` Int( 9 ) NOT NULL,
	`slide_order` Int( 11 ) NOT NULL,
	`params` LongText CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`layers` LongText CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`settings` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	CONSTRAINT `id` UNIQUE( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "spreadsheet_mapper" -----------------------
-- CREATE TABLE "spreadsheet_mapper" ---------------------------
CREATE TABLE `spreadsheet_mapper` ( 
	`spreadsheetid` Int( 50 ) AUTO_INCREMENT NOT NULL,
	`instanceformid` Int( 11 ) NOT NULL,
	`locked` Int( 16 ) NULL,
	PRIMARY KEY ( `spreadsheetid` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "srf" --------------------------------------
-- CREATE TABLE "srf" ------------------------------------------
CREATE TABLE `srf` ( 
	`srfid` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`department` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`prid` Int( 11 ) NULL,
	`remarks` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`status` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`sprid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `srfid` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "static_slides" ----------------------------
-- CREATE TABLE "static_slides" --------------------------------
CREATE TABLE `static_slides` ( 
	`id` Int( 9 ) AUTO_INCREMENT NOT NULL,
	`slider_id` Int( 9 ) NOT NULL,
	`params` LongText CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`layers` LongText CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`settings` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	CONSTRAINT `id` UNIQUE( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "stattracker" ------------------------------
-- CREATE TABLE "stattracker" ----------------------------------
CREATE TABLE `stattracker` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`browser` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`ip` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`thedate_visited` DateTime NULL,
	`page` VarChar( 70 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`logout_date` DateTime NULL,
	`systeminfo` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "statusboxes" ------------------------------
-- CREATE TABLE "statusboxes" ----------------------------------
CREATE TABLE `statusboxes` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`class` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`label` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Status Box',
	`color` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'green',
	`link` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`imageicon` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'icon-bell',
	`linklabel` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Status Box',
	`class_method` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`description` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`popup` LongText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`popuptitle` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`showinpopup` TinyInt( 1 ) NULL DEFAULT '0',
	`style` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`linkclass` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`subtile` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`embed` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`props` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`sequence_no` Int( 10 ) NULL,
	`force_add_avatar` TinyInt( 1 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "sticky_notes" -----------------------------
-- CREATE TABLE "sticky_notes" ---------------------------------
CREATE TABLE `sticky_notes` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`instanceformid` Int( 11 ) NOT NULL,
	`message` LongText CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`color` Int( 2 ) NULL,
	`left` VarChar( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`top` VarChar( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`tabid` VarChar( 55 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`avatarid` Int( 11 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "store" ------------------------------------
-- CREATE TABLE "store" ----------------------------------------
CREATE TABLE `store` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`available` Int( 11 ) NOT NULL,
	`requested` Int( 11 ) NULL,
	`vendor` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "sub_projects" -----------------------------
-- CREATE TABLE "sub_projects" ---------------------------------
CREATE TABLE `sub_projects` ( 
	`sprid` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`realname` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`priority` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`remarks` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`status` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`assigned_to` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`prid` Int( 11 ) NOT NULL,
	`spr_owner_guid` Int( 100 ) NOT NULL,
	PRIMARY KEY ( `sprid` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "supportservice" ---------------------------
-- CREATE TABLE "supportservice" -------------------------------
CREATE TABLE `supportservice` ( 
	`keyID` VarChar( 110 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`Date` Date NOT NULL,
	`Incoming` Int( 11 ) NOT NULL,
	`Outgoing` Int( 11 ) NOT NULL,
	`Process` VarChar( 110 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`OtherFactor` Int( 11 ) NOT NULL,
	`AddInfo` Int( 11 ) NOT NULL,
	`Pending` Int( 11 ) NOT NULL,
	`Error` Int( 11 ) NOT NULL,
	`Client_ID` VarChar( 110 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`KRA` VarChar( 110 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `keyID` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "table_statesave" --------------------------
-- CREATE TABLE "table_statesave" ------------------------------
CREATE TABLE `table_statesave` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`avatarid` Int( 11 ) NOT NULL,
	`groupid` Int( 11 ) NULL DEFAULT '0',
	`moduleid` Int( 11 ) NULL DEFAULT '0',
	`formid` Int( 11 ) NULL,
	`type` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`state` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`colorder` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`hiddencol` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`collocked` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`name` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'Default',
	`def` TinyInt( 4 ) NULL DEFAULT '0',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "testcase" ---------------------------------
-- CREATE TABLE "testcase" -------------------------------------
CREATE TABLE `testcase` ( 
	`id` Int( 11 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`srfid` VarChar( 11 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`type` VarChar( 10000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`testname` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`code` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`cond1` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`cond2` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`assignedto` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`created_date` Date NULL,
	`estimated_date` Date NULL,
	`percent_completion` VarChar( 123 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0',
	`status` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`sup_remarks` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`remarks` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "testcaseparm" -----------------------------
-- CREATE TABLE "testcaseparm" ---------------------------------
CREATE TABLE `testcaseparm` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`parameter` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`instanceformid` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`value` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`remark` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "tester" -----------------------------------
-- CREATE TABLE "tester" ---------------------------------------
CREATE TABLE `tester` ( 
	`id` Int( 11 ) NOT NULL,
	`testerid` Int( 11 ) NOT NULL,
	`testercode` VarChar( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "testname" ---------------------------------
-- CREATE TABLE "testname" -------------------------------------
CREATE TABLE `testname` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`code` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "testnameparm" -----------------------------
-- CREATE TABLE "testnameparm" ---------------------------------
CREATE TABLE `testnameparm` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`parameter` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`testnameid` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "testusers" --------------------------------
-- CREATE TABLE "testusers" ------------------------------------
CREATE TABLE `testusers` ( 
	`guid` Int( 11 ) NOT NULL,
	`name` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `guid` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "timesheet_client_sla" ---------------------
-- CREATE TABLE "timesheet_client_sla" -------------------------
CREATE TABLE `timesheet_client_sla` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`client_id` Int( 11 ) NOT NULL,
	`role` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`process` Int( 11 ) NULL,
	`field_name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`redlowlimit` Int( 11 ) NOT NULL,
	`redhighlimit` Int( 11 ) NOT NULL,
	`yellowlowlimit` Int( 11 ) NOT NULL,
	`yellowhighlimit` Int( 11 ) NOT NULL,
	`greenlowlimit` Int( 11 ) NOT NULL,
	`greenhighlimit` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "timesheet_clients" ------------------------
-- CREATE TABLE "timesheet_clients" ----------------------------
CREATE TABLE `timesheet_clients` ( 
	`id` Int( 111 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL DEFAULT '1',
	`client_name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'VA',
	`show_sla_process` TinyInt( 4 ) NULL,
	`show_days_out` TinyInt( 4 ) NULL,
	`show_tat` TinyInt( 4 ) NULL,
	`show_error` TinyInt( 4 ) NULL,
	`ryg_matrix` TinyInt( 4 ) NULL,
	`show_import` Int( 11 ) NOT NULL DEFAULT '0',
	`show_startstop` Int( 11 ) NOT NULL DEFAULT '0',
	`validation_check` Int( 11 ) NOT NULL DEFAULT '1',
	`view_dashboard` Int( 11 ) NOT NULL DEFAULT '1',
	`managerdashboard` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`clientdashboard` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`appreciation` Int( 11 ) NOT NULL DEFAULT '0',
	`appreciationlink` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`errorlink` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`rcalink` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`eventlink` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`show_daysout_trend` Int( 11 ) NULL,
	`view_received` Int( 11 ) NULL DEFAULT '1',
	`view_sla_popup` TinyInt( 1 ) NULL,
	`daily_trend_sla` Int( 10 ) NOT NULL DEFAULT '0',
	`daily_error_sla` Int( 10 ) NOT NULL DEFAULT '0',
	`monthly_trend_sla` Int( 10 ) NOT NULL DEFAULT '0',
	`monthly_error_sla` Int( 10 ) NOT NULL DEFAULT '0',
	`daysout_trend_sla` Int( 10 ) NOT NULL DEFAULT '0',
	`matrix_y_label` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Files',
	`matrix_qc_option` Int( 1 ) NOT NULL DEFAULT '0',
	`auditlink` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`show_hours` TinyInt( 4 ) NULL DEFAULT '0',
	`skip_adding` Int( 4 ) NOT NULL DEFAULT '0',
	`file_upload` TinyInt( 4 ) NOT NULL DEFAULT '0',
	`show_trending` TinyInt( 1 ) NOT NULL DEFAULT '1',
	`sequence` Int( 10 ) NULL DEFAULT '1',
	`disable_edit` Int( 1 ) NOT NULL DEFAULT '0',
	`addinfo_trend` Int( 1 ) NOT NULL DEFAULT '0',
	`timesheet_type` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`show_calendar` TinyInt( 1 ) NULL DEFAULT '0',
	`project_status` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`comment_shortcut` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "timesheet_cost" ---------------------------
-- CREATE TABLE "timesheet_cost" -------------------------------
CREATE TABLE `timesheet_cost` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`client` Int( 11 ) NULL,
	`process` Int( 11 ) NULL,
	`cost` Float( 12, 0 ) NULL,
	`cost_quality` Float( 12, 0 ) NULL,
	`status` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`expiry_date` Date NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "timesheet_dropdown1" ----------------------
-- CREATE TABLE "timesheet_dropdown1" --------------------------
CREATE TABLE `timesheet_dropdown1` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`client_id` Int( 11 ) NOT NULL,
	`field_name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "timesheet_dropdown2" ----------------------
-- CREATE TABLE "timesheet_dropdown2" --------------------------
CREATE TABLE `timesheet_dropdown2` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`client_id` Int( 11 ) NOT NULL,
	`field_name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "timesheet_dropdown3" ----------------------
-- CREATE TABLE "timesheet_dropdown3" --------------------------
CREATE TABLE `timesheet_dropdown3` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`client_id` Int( 11 ) NOT NULL,
	`field_name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "timesheet_dropdown4" ----------------------
-- CREATE TABLE "timesheet_dropdown4" --------------------------
CREATE TABLE `timesheet_dropdown4` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`client_id` Int( 11 ) NOT NULL,
	`field_name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "timesheet_dropdown5" ----------------------
-- CREATE TABLE "timesheet_dropdown5" --------------------------
CREATE TABLE `timesheet_dropdown5` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`client_id` Int( 11 ) NOT NULL,
	`field_name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "timesheet_fields" -------------------------
-- CREATE TABLE "timesheet_fields" -----------------------------
CREATE TABLE `timesheet_fields` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`client_id` Int( 11 ) NOT NULL,
	`field_id` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`field_name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`field_type` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`field_format` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`sequence` Int( 11 ) NOT NULL DEFAULT '0',
	`required` TinyInt( 1 ) NOT NULL DEFAULT '1',
	`field_formula` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "timesheet_fields_mapper" ------------------
-- CREATE TABLE "timesheet_fields_mapper" ----------------------
CREATE TABLE `timesheet_fields_mapper` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`timesheet_tablename` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`employee_id` Int( 11 ) NULL,
	`field_id` Int( 11 ) NOT NULL,
	`field_value_id` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`field_value` Int( 11 ) NULL,
	`clientid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "timesheet_lob" ----------------------------
-- CREATE TABLE "timesheet_lob" --------------------------------
CREATE TABLE `timesheet_lob` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`client_id` Int( 11 ) NOT NULL,
	`field_name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "timesheet_process" ------------------------
-- CREATE TABLE "timesheet_process" ----------------------------
CREATE TABLE `timesheet_process` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`client_id` Int( 11 ) NOT NULL,
	`field_name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`default_value` TinyInt( 4 ) NOT NULL DEFAULT '0',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "timesheet_project" ------------------------
-- CREATE TABLE "timesheet_project" ----------------------------
CREATE TABLE `timesheet_project` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`client_id` Int( 11 ) NOT NULL,
	`field_name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "timesheet_status" -------------------------
-- CREATE TABLE "timesheet_status" -----------------------------
CREATE TABLE `timesheet_status` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`client_id` Int( 11 ) NOT NULL,
	`field_name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`field_id` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "timesheet_type" ---------------------------
-- CREATE TABLE "timesheet_type" -------------------------------
CREATE TABLE `timesheet_type` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`fieldname` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`fieldtext` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "training_app_data" ------------------------
-- CREATE TABLE "training_app_data" ----------------------------
CREATE TABLE `training_app_data` ( 
	`id` Int( 11 ) NOT NULL,
	`name` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`description` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`formid` Int( 11 ) NOT NULL,
	`assignedto` Int( 11 ) NULL,
	`createdid` Int( 11 ) NULL,
	`startdate` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`nextactiondate` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`enddate` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`region` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`centre` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`batchcode` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`rollno` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`Type` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`sprint` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`teachers` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`grade` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`date` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`trackerupdate` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`schedule_conformance` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`attendance` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`assignment` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`dormitory` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`lastsprintscore` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`lastsprintstatus` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`Tollgate` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`tollgatestatus` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`MOP` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "transients" -------------------------------
-- CREATE TABLE "transients" -----------------------------------
CREATE TABLE `transients` ( 
	`id` Int( 9 ) AUTO_INCREMENT NOT NULL,
	`handle` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`expires` Timestamp NULL,
	`value` LongText CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "type" -------------------------------------
-- CREATE TABLE "type" -----------------------------------------
CREATE TABLE `type` ( 
	`id` TinyInt( 4 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`text` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "user" -------------------------------------
-- CREATE TABLE "user" -----------------------------------------
CREATE TABLE `user` ( 
	`id` Int( 11 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`join_date` Timestamp NULL,
	`last_visit` Timestamp NULL,
	`username` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`password` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`email` VarChar( 120 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`salt` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ),
	CONSTRAINT `email_uk` UNIQUE( `email` ),
	CONSTRAINT `username_uk` UNIQUE( `username` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "user_contact" -----------------------------
-- CREATE TABLE "user_contact" ---------------------------------
CREATE TABLE `user_contact` ( 
	`id` Int( 11 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`userid` Int( 11 ) NOT NULL,
	`name` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`email` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ),
	CONSTRAINT `IDX_USER_CONTACT_EMAIL` UNIQUE( `userid`, `email` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "users" ------------------------------------
-- CREATE TABLE "users" ----------------------------------------
CREATE TABLE `users` ( 
	`guid` Int( 2 ) NOT NULL,
	`name` VarChar( 300 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`lastactivity` Int( 11 ) NULL DEFAULT '0',
	PRIMARY KEY ( `guid` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "webpivottable" ----------------------------
-- CREATE TABLE "webpivottable" --------------------------------
CREATE TABLE `webpivottable` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`orgid` Int( 11 ) NOT NULL,
	`formid` Int( 11 ) NOT NULL,
	`query` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "workflow" ---------------------------------
-- CREATE TABLE "workflow" -------------------------------------
CREATE TABLE `workflow` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`instanceformid` Int( 11 ) NULL,
	`formid` Int( 11 ) NULL,
	`orgid` Int( 11 ) NOT NULL,
	`moduleid` Int( 11 ) NULL,
	`groupid` Int( 11 ) NULL,
	`sentinel` TinyInt( 1 ) NOT NULL DEFAULT '0',
	`single_stage` TinyInt( 1 ) NOT NULL DEFAULT '0',
	`type` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "workflow_elements" ------------------------
-- CREATE TABLE "workflow_elements" ----------------------------
CREATE TABLE `workflow_elements` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`workflowstageid` Int( 11 ) NOT NULL,
	`flow_action` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`field` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`value` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`expression` Smallint( 6 ) NULL,
	`condition` VarChar( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`custom_method` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "workflow_steps" ---------------------------
-- CREATE TABLE "workflow_steps" -------------------------------
CREATE TABLE `workflow_steps` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`workflowid` Int( 11 ) NOT NULL,
	`statusid` Int( 11 ) NOT NULL,
	`type` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`customclass` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`keyid` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = MyISAM
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE TABLE "xflat_config" -----------------------------
-- CREATE TABLE "xflat_config" ---------------------------------
CREATE TABLE `xflat_config` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 10000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`instanceformid` Int( 11 ) NOT NULL,
	`basicconfig` VarChar( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'first box in the congif',
	`dropdownconfig` VarChar( 10000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'multiple select values',
	`red_avatar_trigger` VarChar( 10000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '_a=>avatars _g=>groups',
	`yellow_avatar_trigger` VarChar( 10000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '_a=>avatars _g=>groups',
	`green_avatar_trigger` VarChar( 10000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '_a=>avatars _g=>groups',
	`targets` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'red,yellow,green,(1=>Daily|2=>Weekly|3=>Monthly|4=>Quarter)',
	`link` VarChar( 10000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "avatarid" ---------------------------------
-- CREATE INDEX "avatarid" -------------------------------------
CREATE INDEX `avatarid` USING BTREE ON `app_usage`( `avatarid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "assesmentid" ------------------------------
-- CREATE INDEX "assesmentid" ----------------------------------
CREATE INDEX `assesmentid` USING BTREE ON `assesment_questions`( `assesmentid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "avatarid" ---------------------------------
-- CREATE INDEX "avatarid" -------------------------------------
CREATE INDEX `avatarid` USING BTREE ON `assesment_summary`( `avatarid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "avatarid" ---------------------------------
-- CREATE INDEX "avatarid" -------------------------------------
CREATE INDEX `avatarid` USING BTREE ON `auditlog`( `avatarid`, `groupid`, `instanceformid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "Instance" ---------------------------------
-- CREATE INDEX "Instance" -------------------------------------
CREATE INDEX `Instance` USING BTREE ON `auditlog`( `instanceformid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "modifieddate" -----------------------------
-- CREATE INDEX "modifieddate" ---------------------------------
CREATE INDEX `modifieddate` USING BTREE ON `auditlog`( `modifieddate` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "avatarid" ---------------------------------
-- CREATE INDEX "avatarid" -------------------------------------
CREATE INDEX `avatarid` USING BTREE ON `auditlog_club`( `avatarid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "avatarid" ---------------------------------
-- CREATE INDEX "avatarid" -------------------------------------
CREATE INDEX `avatarid` USING BTREE ON `avatar_flags`( `avatarid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "flag" -------------------------------------
-- CREATE INDEX "flag" -----------------------------------------
CREATE INDEX `flag` USING BTREE ON `avatar_flags`( `flag` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "email" ------------------------------------
-- CREATE INDEX "email" ----------------------------------------
CREATE INDEX `email` USING BTREE ON `avatars`( `email` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "name" -------------------------------------
-- CREATE INDEX "name" -----------------------------------------
CREATE FULLTEXT INDEX `name` ON `avatars`( `name`, `firstname`, `lastname`, `email`, `address`, `phone` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "orgid" ------------------------------------
-- CREATE INDEX "orgid" ----------------------------------------
CREATE INDEX `orgid` USING BTREE ON `avatars`( `orgid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "status" -----------------------------------
-- CREATE INDEX "status" ---------------------------------------
CREATE INDEX `status` USING BTREE ON `avatars`( `status` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "acknowledged" -----------------------------
-- CREATE INDEX "acknowledged" ---------------------------------
CREATE INDEX `acknowledged` USING BTREE ON `avatars_alerts`( `acknowledged` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "avatarid" ---------------------------------
-- CREATE INDEX "avatarid" -------------------------------------
CREATE INDEX `avatarid` USING BTREE ON `avatars_alerts`( `avatarid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "avatar_id" --------------------------------
-- CREATE INDEX "avatar_id" ------------------------------------
CREATE INDEX `avatar_id` USING BTREE ON `club_task`( `avatar_id` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "client" -----------------------------------
-- CREATE INDEX "client" ---------------------------------------
CREATE INDEX `client` USING BTREE ON `club_task`( `client` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "id" ---------------------------------------
-- CREATE INDEX "id" -------------------------------------------
CREATE INDEX `id` USING BTREE ON `club_task`( `id`, `avatar_id` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "direction" --------------------------------
-- CREATE INDEX "direction" ------------------------------------
CREATE INDEX `direction` USING BTREE ON `cometchat`( `direction` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "from" -------------------------------------
-- CREATE INDEX "from" -----------------------------------------
CREATE INDEX `from` USING BTREE ON `cometchat`( `from` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "read" -------------------------------------
-- CREATE INDEX "read" -----------------------------------------
CREATE INDEX `read` USING BTREE ON `cometchat`( `read` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "sent" -------------------------------------
-- CREATE INDEX "sent" -----------------------------------------
CREATE INDEX `sent` USING BTREE ON `cometchat`( `sent` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "to" ---------------------------------------
-- CREATE INDEX "to" -------------------------------------------
CREATE INDEX `to` USING BTREE ON `cometchat`( `to` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "time" -------------------------------------
-- CREATE INDEX "time" -----------------------------------------
CREATE INDEX `time` USING BTREE ON `cometchat_announcements`( `time` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "to" ---------------------------------------
-- CREATE INDEX "to" -------------------------------------------
CREATE INDEX `to` USING BTREE ON `cometchat_announcements`( `to` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "to_id" ------------------------------------
-- CREATE INDEX "to_id" ----------------------------------------
CREATE INDEX `to_id` USING BTREE ON `cometchat_announcements`( `to`, `id` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "fromid" -----------------------------------
-- CREATE INDEX "fromid" ---------------------------------------
CREATE INDEX `fromid` USING BTREE ON `cometchat_block`( `fromid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "fromid_toid" ------------------------------
-- CREATE INDEX "fromid_toid" ----------------------------------
CREATE INDEX `fromid_toid` USING BTREE ON `cometchat_block`( `fromid`, `toid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "toid" -------------------------------------
-- CREATE INDEX "toid" -----------------------------------------
CREATE INDEX `toid` USING BTREE ON `cometchat_block`( `toid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "chatroomid" -------------------------------
-- CREATE INDEX "chatroomid" -----------------------------------
CREATE INDEX `chatroomid` USING BTREE ON `cometchat_chatroommessages`( `chatroomid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "sent" -------------------------------------
-- CREATE INDEX "sent" -----------------------------------------
CREATE INDEX `sent` USING BTREE ON `cometchat_chatroommessages`( `sent` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "userid" -----------------------------------
-- CREATE INDEX "userid" ---------------------------------------
CREATE INDEX `userid` USING BTREE ON `cometchat_chatroommessages`( `userid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "createdby" --------------------------------
-- CREATE INDEX "createdby" ------------------------------------
CREATE INDEX `createdby` USING BTREE ON `cometchat_chatrooms`( `createdby` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "lastactivity" -----------------------------
-- CREATE INDEX "lastactivity" ---------------------------------
CREATE INDEX `lastactivity` USING BTREE ON `cometchat_chatrooms`( `lastactivity` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "type" -------------------------------------
-- CREATE INDEX "type" -----------------------------------------
CREATE INDEX `type` USING BTREE ON `cometchat_chatrooms`( `type` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "chatroomid" -------------------------------
-- CREATE INDEX "chatroomid" -----------------------------------
CREATE INDEX `chatroomid` USING BTREE ON `cometchat_chatrooms_users`( `chatroomid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "lastactivity" -----------------------------
-- CREATE INDEX "lastactivity" ---------------------------------
CREATE INDEX `lastactivity` USING BTREE ON `cometchat_chatrooms_users`( `lastactivity` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "userid" -----------------------------------
-- CREATE INDEX "userid" ---------------------------------------
CREATE INDEX `userid` USING BTREE ON `cometchat_chatrooms_users`( `userid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "userid_chatroomid" ------------------------
-- CREATE INDEX "userid_chatroomid" ----------------------------
CREATE INDEX `userid_chatroomid` USING BTREE ON `cometchat_chatrooms_users`( `chatroomid`, `userid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "channel" ----------------------------------
-- CREATE INDEX "channel" --------------------------------------
CREATE INDEX `channel` USING BTREE ON `cometchat_comethistory`( `channel` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "channel_sent" -----------------------------
-- CREATE INDEX "channel_sent" ---------------------------------
CREATE INDEX `channel_sent` USING BTREE ON `cometchat_comethistory`( `channel`, `sent` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "sent" -------------------------------------
-- CREATE INDEX "sent" -----------------------------------------
CREATE INDEX `sent` USING BTREE ON `cometchat_comethistory`( `sent` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "lastactivity" -----------------------------
-- CREATE INDEX "lastactivity" ---------------------------------
CREATE INDEX `lastactivity` USING BTREE ON `cometchat_guests`( `lastactivity` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "direction" --------------------------------
-- CREATE INDEX "direction" ------------------------------------
CREATE INDEX `direction` USING BTREE ON `cometchat_messages_old`( `direction` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "from" -------------------------------------
-- CREATE INDEX "from" -----------------------------------------
CREATE INDEX `from` USING BTREE ON `cometchat_messages_old`( `from` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "read" -------------------------------------
-- CREATE INDEX "read" -----------------------------------------
CREATE INDEX `read` USING BTREE ON `cometchat_messages_old`( `read` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "sent" -------------------------------------
-- CREATE INDEX "sent" -----------------------------------------
CREATE INDEX `sent` USING BTREE ON `cometchat_messages_old`( `sent` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "to" ---------------------------------------
-- CREATE INDEX "to" -------------------------------------------
CREATE INDEX `to` USING BTREE ON `cometchat_messages_old`( `to` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "typingtime" -------------------------------
-- CREATE INDEX "typingtime" -----------------------------------
CREATE INDEX `typingtime` USING BTREE ON `cometchat_status`( `typingtime` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "typingto" ---------------------------------
-- CREATE INDEX "typingto" -------------------------------------
CREATE INDEX `typingto` USING BTREE ON `cometchat_status`( `typingto` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "identity" ---------------------------------
-- CREATE INDEX "identity" -------------------------------------
CREATE INDEX `identity` USING BTREE ON `cometchat_videochatsessions`( `identity` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "timestamp" --------------------------------
-- CREATE INDEX "timestamp" ------------------------------------
CREATE INDEX `timestamp` USING BTREE ON `cometchat_videochatsessions`( `timestamp` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "username" ---------------------------------
-- CREATE INDEX "username" -------------------------------------
CREATE INDEX `username` USING BTREE ON `cometchat_videochatsessions`( `username` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "avatarid" ---------------------------------
-- CREATE INDEX "avatarid" -------------------------------------
CREATE INDEX `avatarid` USING BTREE ON `comments`( `avatarid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "date_modified" ----------------------------
-- CREATE INDEX "date_modified" --------------------------------
CREATE INDEX `date_modified` USING BTREE ON `comments`( `date_modified` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "instanceformid" ---------------------------
-- CREATE INDEX "instanceformid" -------------------------------
CREATE INDEX `instanceformid` USING BTREE ON `comphotos`( `instanceformid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "orgid" ------------------------------------
-- CREATE INDEX "orgid" ----------------------------------------
CREATE INDEX `orgid` USING BTREE ON `configurations`( `orgid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "parameter" --------------------------------
-- CREATE INDEX "parameter" ------------------------------------
CREATE INDEX `parameter` USING BTREE ON `configurations`( `parameter` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "idx_cc" -----------------------------------
-- CREATE INDEX "idx_cc" ---------------------------------------
CREATE INDEX `idx_cc` USING BTREE ON `email_cache`( `cc`( 255 ) );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "idx_datetime" -----------------------------
-- CREATE INDEX "idx_datetime" ---------------------------------
CREATE INDEX `idx_datetime` USING BTREE ON `email_cache`( `datetime` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "idx_from" ---------------------------------
-- CREATE INDEX "idx_from" -------------------------------------
CREATE INDEX `idx_from` USING BTREE ON `email_cache`( `_from` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "idx_subject" ------------------------------
-- CREATE INDEX "idx_subject" ----------------------------------
CREATE INDEX `idx_subject` USING BTREE ON `email_cache`( `_subject`( 255 ) );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "idx_to" -----------------------------------
-- CREATE INDEX "idx_to" ---------------------------------------
CREATE INDEX `idx_to` USING BTREE ON `email_cache`( `_to`( 255 ) );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "idx_email" --------------------------------
-- CREATE INDEX "idx_email" ------------------------------------
CREATE INDEX `idx_email` USING BTREE ON `email_setting`( `email` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "idx_userid" -------------------------------
-- CREATE INDEX "idx_userid" -----------------------------------
CREATE INDEX `idx_userid` USING BTREE ON `email_setting`( `userid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "instanceformid" ---------------------------
-- CREATE INDEX "instanceformid" -------------------------------
CREATE INDEX `instanceformid` USING BTREE ON `escalations`( `instanceformid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "instanceform" -----------------------------
-- CREATE INDEX "instanceform" ---------------------------------
CREATE INDEX `instanceform` USING BTREE ON `executive_desktop`( `instanceform` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "instanceformid" ---------------------------
-- CREATE INDEX "instanceformid" -------------------------------
CREATE INDEX `instanceformid` USING BTREE ON `expense`( `instanceformid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "instanceformid" ---------------------------
-- CREATE INDEX "instanceformid" -------------------------------
CREATE INDEX `instanceformid` USING BTREE ON `formcomments`( `instanceformid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "avatarid" ---------------------------------
-- CREATE INDEX "avatarid" -------------------------------------
CREATE INDEX `avatarid` USING BTREE ON `friends`( `avatarid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "org_role_id" ------------------------------
-- CREATE INDEX "org_role_id" ----------------------------------
CREATE INDEX `org_role_id` USING BTREE ON `goal_label`( `org_role_id` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "moduleid" ---------------------------------
-- CREATE INDEX "moduleid" -------------------------------------
CREATE INDEX `moduleid` USING BTREE ON `grade`( `moduleid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "orgid" ------------------------------------
-- CREATE INDEX "orgid" ----------------------------------------
CREATE INDEX `orgid` USING BTREE ON `grade`( `orgid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "orgid" ------------------------------------
-- CREATE INDEX "orgid" ----------------------------------------
CREATE INDEX `orgid` USING BTREE ON `groups`( `orgid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "status" -----------------------------------
-- CREATE INDEX "status" ---------------------------------------
CREATE INDEX `status` USING BTREE ON `groups`( `status` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "avatarid" ---------------------------------
-- CREATE INDEX "avatarid" -------------------------------------
CREATE INDEX `avatarid` USING BTREE ON `groups_avatars`( `avatarid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "avatarid" ---------------------------------
-- CREATE INDEX "avatarid" -------------------------------------
CREATE INDEX `avatarid` USING BTREE ON `groups_managers`( `managerid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "groupid" ----------------------------------
-- CREATE INDEX "groupid" --------------------------------------
CREATE INDEX `groupid` USING BTREE ON `groups_modules`( `groupid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "id" ---------------------------------------
-- CREATE INDEX "id" -------------------------------------------
CREATE INDEX `id` USING BTREE ON `ikra`( `avatarid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "avatarid" ---------------------------------
-- CREATE INDEX "avatarid" -------------------------------------
CREATE INDEX `avatarid` USING BTREE ON `ikradaily`( `avatarid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "avatarid" ---------------------------------
-- CREATE INDEX "avatarid" -------------------------------------
CREATE INDEX `avatarid` USING BTREE ON `instancefields`( `avatarid`, `instanceformid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "fieldid" ----------------------------------
-- CREATE INDEX "fieldid" --------------------------------------
CREATE INDEX `fieldid` USING BTREE ON `instancefields`( `fieldid`, `instanceformid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "instanceformid" ---------------------------
-- CREATE INDEX "instanceformid" -------------------------------
CREATE INDEX `instanceformid` USING BTREE ON `instancefields`( `instanceformid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "instanceformid_2" -------------------------
-- CREATE INDEX "instanceformid_2" -----------------------------
CREATE INDEX `instanceformid_2` USING BTREE ON `instancefields`( `instanceformid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "orgid" ------------------------------------
-- CREATE INDEX "orgid" ----------------------------------------
CREATE INDEX `orgid` USING BTREE ON `instancefields`( `orgid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "date_created" -----------------------------
-- CREATE INDEX "date_created" ---------------------------------
CREATE INDEX `date_created` USING BTREE ON `instanceforms`( `date_created` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "description" ------------------------------
-- CREATE INDEX "description" ----------------------------------
CREATE FULLTEXT INDEX `description` ON `instanceforms`( `description`, `name` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "formid" -----------------------------------
-- CREATE INDEX "formid" ---------------------------------------
CREATE INDEX `formid` USING BTREE ON `instanceforms`( `formid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "orgid" ------------------------------------
-- CREATE INDEX "orgid" ----------------------------------------
CREATE INDEX `orgid` USING BTREE ON `instanceforms`( `orgid`, `parentinstformid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "parentinstformid" -------------------------
-- CREATE INDEX "parentinstformid" -----------------------------
CREATE INDEX `parentinstformid` USING BTREE ON `instanceforms`( `parentinstformid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "instanceformid" ---------------------------
-- CREATE INDEX "instanceformid" -------------------------------
CREATE INDEX `instanceformid` USING BTREE ON `instanceforms_join`( `instanceformid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "instanceformid" ---------------------------
-- CREATE INDEX "instanceformid" -------------------------------
CREATE INDEX `instanceformid` USING BTREE ON `instforms_files`( `instanceformid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "messageid" --------------------------------
-- CREATE INDEX "messageid" ------------------------------------
CREATE INDEX `messageid` USING BTREE ON `instforms_files`( `messageid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "instanceformid" ---------------------------
-- CREATE INDEX "instanceformid" -------------------------------
CREATE INDEX `instanceformid` USING BTREE ON `instforms_files_tmp`( `formid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "messageid" --------------------------------
-- CREATE INDEX "messageid" ------------------------------------
CREATE INDEX `messageid` USING BTREE ON `instforms_files_tmp`( `avatarid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "instformid" -------------------------------
-- CREATE INDEX "instformid" -----------------------------------
CREATE INDEX `instformid` USING BTREE ON `instforms_groups`( `instformid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "instanceformid" ---------------------------
-- CREATE INDEX "instanceformid" -------------------------------
CREATE INDEX `instanceformid` USING BTREE ON `instforms_links`( `instanceformid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "idx_job_in_progress" ----------------------
-- CREATE INDEX "idx_job_in_progress" --------------------------
CREATE INDEX `idx_job_in_progress` USING BTREE ON `job`( `is_job_in_progress` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "idx_job_status" ---------------------------
-- CREATE INDEX "idx_job_status" -------------------------------
CREATE INDEX `idx_job_status` USING BTREE ON `job`( `job_status` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "idx_job_tracker_type" ---------------------
-- CREATE INDEX "idx_job_tracker_type" -------------------------
CREATE INDEX `idx_job_tracker_type` USING BTREE ON `job`( `job_tracker`, `job_type`( 255 ) );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "avatarid" ---------------------------------
-- CREATE INDEX "avatarid" -------------------------------------
CREATE INDEX `avatarid` USING BTREE ON `likes`( `avatarid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "commentid" --------------------------------
-- CREATE INDEX "commentid" ------------------------------------
CREATE INDEX `commentid` USING BTREE ON `likes`( `commentid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "messageid" --------------------------------
-- CREATE INDEX "messageid" ------------------------------------
CREATE INDEX `messageid` USING BTREE ON `message_recepients`( `messageid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "toid_status" ------------------------------
-- CREATE INDEX "toid_status" ----------------------------------
CREATE INDEX `toid_status` USING BTREE ON `message_recepients`( `toid`, `status` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "fromid" -----------------------------------
-- CREATE INDEX "fromid" ---------------------------------------
CREATE INDEX `fromid` USING BTREE ON `messages`( `fromid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "instanceformid" ---------------------------
-- CREATE INDEX "instanceformid" -------------------------------
CREATE INDEX `instanceformid` USING BTREE ON `messages`( `instanceformid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "formid" -----------------------------------
-- CREATE INDEX "formid" ---------------------------------------
CREATE INDEX `formid` USING BTREE ON `metafields`( `formid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "name" -------------------------------------
-- CREATE INDEX "name" -----------------------------------------
CREATE INDEX `name` USING BTREE ON `metafields`( `name` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "options" ----------------------------------
-- CREATE INDEX "options" --------------------------------------
CREATE INDEX `options` USING BTREE ON `metafields`( `options`( 500 ) );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "moduleid" ---------------------------------
-- CREATE INDEX "moduleid" -------------------------------------
CREATE INDEX `moduleid` USING BTREE ON `metaforms`( `moduleid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "orgid" ------------------------------------
-- CREATE INDEX "orgid" ----------------------------------------
CREATE INDEX `orgid` USING BTREE ON `metaforms`( `orgid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "name" -------------------------------------
-- CREATE INDEX "name" -----------------------------------------
CREATE INDEX `name` USING BTREE ON `metalist`( `name` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "idx_email" --------------------------------
-- CREATE INDEX "idx_email" ------------------------------------
CREATE INDEX `idx_email` USING BTREE ON `oauth2_setting`( `email` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "idx_provider" -----------------------------
-- CREATE INDEX "idx_provider" ---------------------------------
CREATE INDEX `idx_provider` USING BTREE ON `oauth2_setting`( `provider` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "idx_userid" -------------------------------
-- CREATE INDEX "idx_userid" -----------------------------------
CREATE INDEX `idx_userid` USING BTREE ON `oauth2_setting`( `userid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "ownergroupid" -----------------------------
-- CREATE INDEX "ownergroupid" ---------------------------------
CREATE INDEX `ownergroupid` USING BTREE ON `operatingrhythm`( `groupid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "status" -----------------------------------
-- CREATE INDEX "status" ---------------------------------------
CREATE INDEX `status` USING BTREE ON `organizations`( `status` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "avatarid" ---------------------------------
-- CREATE INDEX "avatarid" -------------------------------------
CREATE INDEX `avatarid` USING BTREE ON `oxmedia_devices_sliders`( `deviceid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "questiontext" -----------------------------
-- CREATE INDEX "questiontext" ---------------------------------
CREATE FULLTEXT INDEX `questiontext` ON `questions`( `questiontext` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "last_activity_idx" ------------------------
-- CREATE INDEX "last_activity_idx" ----------------------------
CREATE INDEX `last_activity_idx` USING BTREE ON `sessions`( `last_activity` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "assigto_index" ----------------------------
-- CREATE INDEX "assigto_index" --------------------------------
CREATE INDEX `assigto_index` USING BTREE ON `testcase`( `assignedto` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "testname_index" ---------------------------
-- CREATE INDEX "testname_index" -------------------------------
CREATE INDEX `testname_index` USING BTREE ON `testcase`( `testname` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "name_index" -------------------------------
-- CREATE INDEX "name_index" -----------------------------------
CREATE INDEX `name_index` USING BTREE ON `testname`( `name` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "code_index" -------------------------------
-- CREATE INDEX "code_index" -----------------------------------
CREATE INDEX `code_index` USING BTREE ON `testnameparm`( `testnameid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "parameter_index" --------------------------
-- CREATE INDEX "parameter_index" ------------------------------
CREATE INDEX `parameter_index` USING BTREE ON `testnameparm`( `parameter` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "idx_userid" -------------------------------
-- CREATE INDEX "idx_userid" -----------------------------------
CREATE INDEX `idx_userid` USING BTREE ON `user_contact`( `userid` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- ---------------------------------------------------------


