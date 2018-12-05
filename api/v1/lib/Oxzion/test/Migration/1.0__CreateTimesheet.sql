CREATE TABLE IF NOT EXISTS `ox_timesheet` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(10000) NOT NULL,
              `client_id` varchar(1000) NOT NULL,
              `date_created` datetime NOT NULL,
              `date_modified` datetime NOT NULL,
              `description` varchar(10000),
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;