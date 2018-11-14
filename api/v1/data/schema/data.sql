insert into organizations (id, name, address, city, state, zip, logo, defaultgroupid, statusbox, labelfile, messagecount, languagefile, orgtype, flash_msg, email, themes, formview, assign_followuplimit, insurelearn, reset_password, status)
values (1, "Cleveland Cavaliers.", "King James Street", "Cleveland", "OH", 42123, "", 1, "", "en", 200, "en", 1, 1, "Active", 1, 1, 10, 0, 0, "Active");    

insert into avatars (id, gamelevel, username, password,firstname,lastname, name,dob,doj ,sex,managerid,level, role, orgid, email, emailnotify, sentinel, status, alertsacknowledged, pollsacknowledged, statusbox, cluster, open_new_tab, listtoggle, defaultmatrixid, lastactivity, locked, org_role_id, in_game, mission_link, timezone, inmail_label)
values (1, "", "bharatg", "1619d7adc23f4f633f11014d2f22b7d8","Bharat","Gogineni","Bharat Gogineni","1991-02-28 00:00:00","1991-02-28 00:00:00","Male",1,"WANNABEE", "admin", 1, "", "Active", "On", "Active", 1, 1, "Matrix|Leaderboard|Alerts", 0, 0, 0, 0, 0, 0, 1, 0, "", "United States/New York", "2=>Comment|3=>Observer|4=>Personal");
insert into avatars (id, gamelevel, username, password,firstname,lastname, name,dob,doj ,sex,managerid,level, role, orgid, email, emailnotify, sentinel, status, alertsacknowledged, pollsacknowledged, statusbox, cluster, open_new_tab, listtoggle, defaultmatrixid, lastactivity, locked, org_role_id, in_game, mission_link, timezone, inmail_label)
values (2, "", "karan", "1619d7adc23f4f633f11014d2f22b7d8","Karan","Agarwal", "Karan Agarwal","1991-02-28 00:00:00","1991-02-28 00:00:00","Male",1,"WANNABEE", "employee", 1, "", "Active", "On", "Active", 1, 1, "Matrix|Leaderboard|Alerts", 0, 0, 0, 0, 0, 0, 1, 0, "", "Asia/Kolkata", "2=>Comment|3=>Observer|4=>Personal");

insert into avatars (id, gamelevel, username, password,firstname,lastname, name,dob,doj ,sex,managerid,level, role, orgid, email, emailnotify, sentinel, status, alertsacknowledged, pollsacknowledged, statusbox, cluster, open_new_tab, listtoggle, defaultmatrixid, lastactivity, locked, org_role_id, in_game, mission_link, timezone, inmail_label)
values (3, "", "rakshith", "1619d7adc23f4f633f11014d2f22b7d8","Rakshith","Amin","Rakshith Amin","1991-02-28 00:00:00","1991-02-28 00:00:00","Male",1,"WANNABEE", "manager", 1, "", "Active", "On", "Active", 1, 1, "Matrix|Leaderboard|Alerts", 0, 0, 0, 0, 0, 0, 1, 0, "", "Asia/Kolkata", "2=>Comment|3=>Observer|4=>Personal");

insert into groups (id, name, orgid, managerid, assigntomanager, power_users, type, hiddentopicons, hidetiles, hidewall, hideannouncement, hideleaderboard, status)
values (1, "Lions", 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, "Active");

insert into groups (id, name, orgid, managerid, assigntomanager, power_users, type, hiddentopicons, hidetiles, hidewall, hideannouncement, hideleaderboard, status)
values (2, "Tigers", 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, "Active");

insert into groups (id, name, orgid, managerid, assigntomanager, power_users, type, hiddentopicons, hidetiles, hidewall, hideannouncement, hideleaderboard, status)
values (3, "BOS", 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, "Active");

insert into groups_avatars (id, groupid, avatarid)
values (1, 1, 1);
insert into groups_avatars (id, groupid, avatarid)
values (2, 2, 1);
insert into groups_avatars (id, groupid, avatarid)
values (3, 3, 1);
insert into groups_avatars (id, groupid, avatarid)
values (4, 1, 2);
insert into groups_avatars (id, groupid, avatarid)
values (5, 2, 2);
insert into groups_avatars (id, groupid, avatarid)
values (6, 2, 3);

INSERT INTO `ox_group` (`id`, `name`, `parent_id`, `org_id`, `manager_id`, `description`, `logo`, `cover_photo`, `type`, `status`, `date_created`, `date_modified`, `created_id`, `modified_id`) VALUES ('1368', 'OX ZIon HR', '0', '1', '435', 'Please enter the des', 'NULL', 'NULL', '1', 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '0');
INSERT INTO `ox_group` (`id`, `name`, `parent_id`, `org_id`, `manager_id`, `description`, `logo`, `cover_photo`, `type`, `status`, `date_created`, `date_modified`, `created_id`, `modified_id`) VALUES ('1369', 'Dogs', '0', '1', '400', '<p>York Client</p>\n', 'NULL', 'NULL', '0', 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '0');

INSERT INTO `ox_user_group` (`id`, `group_id`, `avatar_id`) VALUES ('', '1368', '436');
INSERT INTO `ox_user_group` (`group_id`, `avatar_id`) VALUES ('1368', '436');
