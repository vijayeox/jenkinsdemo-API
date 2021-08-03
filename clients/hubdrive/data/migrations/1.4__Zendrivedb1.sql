CREATE TABLE IF NOT EXISTS `ic_driver_mapping` (
`id` int (11) NOT NULL AUTO_INCREMENT,
`driver_name` varchar (100) NOT NULL ,
`uuid` varchar(128) NOT NULL,
`fleet_id` varchar(128) NOT NULL,
`email` varchar(50) NOT NULL,
`zendrive_driver_id` varchar(128) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8;

-- CREATE TABLE IF NOT EXISTS `ic_driver_mapping` (
-- `driver_name` int (11) NOT NULL ,
-- `uuid` varchar(128) NOT NULL,
-- `fleet_id` varchar(50) DEFAULT NULL,
-- `email` varchar(50) DEFAULT NULL,
-- `zendrive_driver_id` INT(20) DEFAULT NULL,
-- UNIQUE KEY `uuid` (`uuid`)
-- ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8;


-- //create a table called ic_driver_mapping in hubdrive db and save driver name, email, uuid, fleet_id,zendrive_driver_id