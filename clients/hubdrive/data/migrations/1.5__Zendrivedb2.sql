--   //create a table called ic_info in hubdrive db and save ic name, email, phone, uuid, fleet_api_key
CREATE TABLE IF NOT EXISTS `ic_info` (
`id` int (11) NOT NULL AUTO_INCREMENT,
`ic_name` varchar (100) NOT NULL ,
`email` varchar(128) NOT NULL,
`ph_number` varchar(50) NOT NULL,
`uuid` varchar(128) NOT NULL,
`fleet_api_key` varchar(100) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8;
