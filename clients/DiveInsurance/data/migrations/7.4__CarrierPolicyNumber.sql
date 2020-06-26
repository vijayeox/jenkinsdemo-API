DELETE FROM `carrier_policy` WHERE `product` = 'Dive Store';
INSERT INTO `carrier_policy` (`product`,`carrier`,`policy_number`,`category`,`start_date`,`end_date`,`year`) VALUES ('Dive Store','Tokio Marine Specialty Insurance Company','PPK2148867','LIABILITY',"2019-06-01 00:00:00","2020-06-30 11:59:59",2019); 
INSERT INTO `carrier_policy` (`product`,`carrier`,`policy_number`,`category`,`start_date`,`end_date`,`year`) VALUES ('Dive Store','Tokio Marine Specialty Insurance Company','PPK2148865','PROPERTY',"2019-06-01 00:00:00","2020-06-30 11:59:59",2019); 

UPDATE `carrier_policy` SET `policy_number` = 'PPK2148852' WHERE `product` in ('Individual Professional Liability','Group Professional Liability','Emergency First Response');

DELETE FROM `carrier_policy` WHERE `year` = 2020;
INSERT INTO `carrier_policy` (`product`,`carrier`,`policy_number`,`category`,`start_date`,`end_date`,`year`) VALUES ('Dive Store','Tokio Marine Specialty Insurance Company','PPK2148867','LIABILITY',"2020-06-30 00:00:00","2021-06-30 11:59:59",2020); 
INSERT INTO `carrier_policy` (`product`,`carrier`,`policy_number`,`category`,`start_date`,`end_date`,`year`) VALUES ('Dive Store','Tokio Marine Specialty Insurance Company','PPK2148865','PROPERTY',"2020-06-30 00:00:00","2021-06-30 11:59:59",2020); 


INSERT INTO `carrier_policy` (`product`,`carrier`,`policy_number`,`start_date`,`end_date`,`year`) VALUES ('Individual Professional Liability','Tokio Marine Specialty Insurance Company','PPK2148852',"2020-06-30 00:00:00","2021-06-30 11:59:59",2020); 
INSERT INTO `carrier_policy` (`product`,`carrier`,`policy_number`,`state`,`start_date`,`end_date`,`year`) VALUES ('Individual Professional Liability', 'Tokio Marine Specialty Insurance Company', 'PPK2148852','Guam',"2020-06-30 00:00:00","2021-06-30 11:59:59",2020); 

INSERT INTO `carrier_policy` (`product`,`carrier`,`policy_number`,`start_date`,`end_date`,`year`) VALUES ('Emergency First Response','Tokio Marine Specialty Insurance Company','PPK2148852',"2020-06-30 00:00:00","2021-06-30 11:59:59",2020); 
INSERT INTO `carrier_policy` (`product`,`carrier`,`policy_number`,`start_date`,`end_date`,`year`) VALUES ('Group Professional Liability','Tokio Marine Specialty Insurance Company','PPK2148852',"2020-06-01 00:00:00","2021-06-30 11:59:59",2020); 
INSERT INTO `carrier_policy` (`product`,`carrier`,`policy_number`,`start_date`,`end_date`,`year`) VALUES ('Dive Boat', 'U.S. SPECIALTY INSURANCE COMPANY', 'CUL11137.079',"2020-07-22 00:00:00","2021-07-22 11:59:59",2020);        

