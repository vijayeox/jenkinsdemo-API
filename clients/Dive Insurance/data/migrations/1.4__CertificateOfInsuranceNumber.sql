CREATE TABLE IF NOT EXISTS `certificate_of_insurance_number` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `product` varchar(255) NOT NULL,
              `year` INT(4) NOT NULL,
              `sequence` INT(11) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;