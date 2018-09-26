insert into organizations (id, name, address, city, state, zip, logo, defaultgroupid, statusbox, labelfile, messagecount, languagefile, orgtype, flash_msg, email, themes, formview, assign_followuplimit, insurelearn, reset_password, status)
values (1, "Cleveland Cavaliers.", "King James Street", "Cleveland", "OH", 42123, "", 1, "", "en", 200, "en", 1, 1, "Active", 1, 1, 10, 0, 0, "Active");    

insert into avatars (id, gamelevel, username, password, name, role, orgid, email, emailnotify, sentinel, status, alertsacknowledged, pollsacknowledged, statusbox, cluster, open_new_tab, listtoggle, defaultmatrixid, lastactivity, locked, org_role_id, in_game, mission_link, timezone, inmail_label)
values (1, "", "bharatg", "1619d7adc23f4f633f11014d2f22b7d8", "Bharat", "admin", 1, "", "Active", "On", "Active", 1, 1, "Matrix|Leaderboard|Alerts", 0, 0, 0, 0, 0, 0, 1, 0, "", "United States/New York", "2=>Comment|3=>Observer|4=>Personal");
insert into avatars (id, gamelevel, username, password, name, role, orgid, email, emailnotify, sentinel, status, alertsacknowledged, pollsacknowledged, statusbox, cluster, open_new_tab, listtoggle, defaultmatrixid, lastactivity, locked, org_role_id, in_game, mission_link, timezone, inmail_label)
values (2, "", "karan", "1619d7adc23f4f633f11014d2f22b7d8", "Karan", "employee", 1, "", "Active", "On", "Active", 1, 1, "Matrix|Leaderboard|Alerts", 0, 0, 0, 0, 0, 0, 1, 0, "", "Asia/Kolkata", "2=>Comment|3=>Observer|4=>Personal");

insert into avatars (id, gamelevel, username, password, name, role, orgid, email, emailnotify, sentinel, status, alertsacknowledged, pollsacknowledged, statusbox, cluster, open_new_tab, listtoggle, defaultmatrixid, lastactivity, locked, org_role_id, in_game, mission_link, timezone, inmail_label)
values (2, "", "rakshith", "1619d7adc23f4f633f11014d2f22b7d8", "Rakshith", "manager", 1, "", "Active", "On", "Active", 1, 1, "Matrix|Leaderboard|Alerts", 0, 0, 0, 0, 0, 0, 1, 0, "", "Asia/Kolkata", "2=>Comment|3=>Observer|4=>Personal");

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