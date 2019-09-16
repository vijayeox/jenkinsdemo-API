CREATE TABLE IF NOT EXISTS `carrier_policy` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `product` varchar(255) NOT NULL,
              `carrier` varchar(255) NOT NULL,
              `policy_number` varchar(50) NOT NULL,
              `start_date` datetime NOT NULL,
              `end_date` datetime NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `carrier_policy` (`product`,`carrier`,`policy_number`,`start_date`,`end_date`) VALUES ('Individual Professional Liability', 'Tokio Marine Specialty Insurance Company', 'PPK1992899',"2019-06-01 00:00:00","2020-06-30 11:59:59"); 
INSERT INTO `carrier_policy` (`product`,`carrier`,`policy_number`,`start_date`,`end_date`) VALUES ('Dive Boat', 'U.S. SPECIALTY INSURANCE COMPANY', 'CUL11137.079',"2019-07-01 00:00:00","2020-07-30 11:59:59");        
INSERT INTO `carrier_policy` (`product`,`carrier`,`policy_number`,`start_date`,`end_date`) VALUES ('Dive Store','Tokio Marine Specialty Insurance Company','PPK1992907',"2019-06-01 00:00:00","2020-06-30 11:59:59"); 
