DROP TABLE `ic_driver_mapping`;
DROP TABLE `ic_info`;

CREATE TABLE IF NOT EXISTS `ic_driver_mapping` (
`id` int (11) NOT NULL AUTO_INCREMENT,
`ic_id` int (11) ,
`driver_id` int (11) ,
PRIMARY KEY (`id`)
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `ic_info` (
`id` int (11) NOT NULL AUTO_INCREMENT,
`ic_name` varchar (100) NOT NULL ,
`email` varchar(128) NOT NULL,
`ph_number` varchar(50) NOT NULL,
`uuid` varchar(128) NOT NULL,
`zendrive_fleet_api_key` varchar(100) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8;