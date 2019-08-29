CREATE TABLE IF NOT EXISTS `carrier_policy` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `product` varchar(255) NOT NULL,
              `carrier` varchar(255) NOT NULL,
              `policy_number` varchar(50) NOT NULL,
              `start_date` datetime NULL,
              `end_date` datetime NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO `carrier_policy` (`product`,`carrier`,`policy_number`) VALUES ('Individual Professional Liability', 'Tokio Marine Specialty Insurance Company', 'PPK1992899');                      