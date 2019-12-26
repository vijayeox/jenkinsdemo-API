CREATE TABLE IF NOT EXISTS `padi_data` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `member_number` varchar(6) NOT NULL,
            `firstname` varchar(16) NULL,
            `MI` varchar(1) NOT NULL,
            `lastname` varchar(21) NULL,
            `address1` varchar(100) NULL,
            `address2` varchar(100) NULL,
            `address_international` varchar(200) NULL,
            `city` varchar(50) NULL,
            `state` varchar(50) NULL,
            `zip` varchar(10) NULL,
            `country_code` varchar(4) NULL,
            `home_phone` varchar(16) NULL,
            `work_phone` varchar(16) NULL,
            `insurance_type` varchar(6) NULL,
            `date_expire` DATETIME NULL,
            `rating` varchar(4) NULL,
            `email` varchar(100) NULL,
            `num` varchar(100) NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB COLLATE=utf8_general_ci DEFAULT CHARSET=utf8;

INSERT INTO `padi_data` (`member_number`,`firstname`,`MI`,`country_code`, `state`) VALUES ('2141', 'Rakshith','G','AF', 'FL');
