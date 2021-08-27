ALTER TABLE carrier_policy ADD COLUMN `category` VARCHAR(100) DEFAULT NULL AFTER `policy_number`;
ALTER TABLE carrier_policy ADD COLUMN `state` VARCHAR(255) DEFAULT NULL AFTER `category`;


DELETE FROM `carrier_policy` WHERE `product` = 'Dive Store';
INSERT INTO `carrier_policy` (`product`,`carrier`,`policy_number`,`category`,`start_date`,`end_date`) VALUES ('Dive Store','Tokio Marine Specialty Insurance Company','PPK1992907','LIABILITY',"2019-06-01 00:00:00","2020-06-30 11:59:59"); 
INSERT INTO `carrier_policy` (`product`,`carrier`,`policy_number`,`category`,`start_date`,`end_date`) VALUES ('Dive Store','Tokio Marine Specialty Insurance Company','PPK1992907','PROPERTY',"2019-06-01 00:00:00","2020-06-30 11:59:59"); 
INSERT INTO `carrier_policy` (`product`,`carrier`,`policy_number`,`state`,`start_date`,`end_date`) VALUES ('Individual Professional Liability', 'Tokio Marine Specialty Insurance Company', 'PPK1992899','Guam',"2019-06-01 00:00:00","2020-06-30 11:59:59"); 
