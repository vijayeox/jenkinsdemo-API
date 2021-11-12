CREATE TABLE IF NOT EXISTS `genre_info` (
    `id` int (11) NOT NULL AUTO_INCREMENT,
    `state_in_short` varchar(10)  NOT NULL,
    `hubstate` varchar(128)  NOT NULL,
    `office` varchar(128)  NOT NULL,
    `primary_genre` varchar(128)  NOT NULL,
  	`primary_email` varchar(128)  NOT NULL,
    `primary_phone` varchar(128)  DEFAULT NULL,
    `secondary_phone` varchar(128) DEFAULT NULL,
  	`backup_genre` varchar(128) DEFAULT NULL,
  	`backup_email` varchar(128) DEFAULT NULL,
  	`backup_primary_phone` varchar(128)  DEFAULT NULL,
    `backup_secondary_phone` varchar(128) DEFAULT NULL,
    `additional_cc` varchar(128) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8;



INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('AK','Alaska','Los Angeles','Kim Mulier','kim.mulier@genre.com','213 630-2416',NULL,'David Berlin','david.berlin@genre.com',NULL,NULL,'jmsulliv@genre.com');
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('AL','Alabama','Atlanta','Kiambu Robinson','krobins@genre.com','404-405-0992','404 365-6966','Kenny Hosp','kenny.hosp@genre.com','404-617-3840','404 365-6847',NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('AR','Arkansas','Mid States (Dallas)','Steve Moran','smoran@genre.com','913 558-6831',NULL,"Tom O'Hara",'tohara@genre.com','614 561-3374',NULL,NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('AZ','Arizona','Los Angeles','Kim Mulier','kim.mulier@genre.com','213 630-2416',NULL,NULL,NULL,NULL,NULL,'jmsulliv@genre.com');
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('CA','California','Los Angeles','Kim Mulier','kim.mulier@genre.com','213 630-2416',NULL,NULL,NULL,NULL,NULL,'jmsulliv@genre.com');
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('CO','Colorado','Mid States (Dallas)','Steve Moran','smoran@genre.com','913 558-6831',NULL,"Tom O'Hara",'tohara@genre.com','614 561-3374',NULL,NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('CT','Connecticut','Northeast (Hartford)','Emily Carr','emily.carr@genre.com','617 728-3826',NULL,'Jackie DiMatteo','jdimatt@genre.com','203 570-3504',NULL,'lsirois@genre.com');
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('DC','District Of Columbia','Philedelphia','Zach Balk','zach.balk@genre.com','614 403-9806',NULL,'Brandon Yez','byez@genre.com','215 988-7116',NULL,NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('DE','Delaware','Philedelphia','Zach Balk','zach.balk@genre.com','614 403-9806',NULL,'Brandon Yez','byez@genre.com','215 988-7116',NULL,NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('FL','Florida','Atlanta','Kiambu Robinson','krobins@genre.com','404-405-0992','404 365-6966','Kenny Hosp','kenny.hosp@genre.com','404-617-3840','404 365-6847',NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('GA','Georgia','Atlanta','Kiambu Robinson','krobins@genre.com','404-405-0992','404 365-6966','Kenny Hosp','kenny.hosp@genre.com','404-617-3840','404 365-6847',NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('HI','Hawaii','Los Angeles','Kim Mulier','kim.mulier@genre.com','213 630-2416',NULL,NULL,NULL,NULL,NULL,'jmsulliv@genre.com');
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('IA','Iowa','Mid States (Dallas)','Steve Moran','smoran@genre.com','913 558-6831',NULL,"Tom O'Hara",'tohara@genre.com','614 561-3374',NULL,NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('ID','Idaho','Los Angeles','Kim Mulier','kim.mulier@genre.com','213 630-2416',NULL,NULL,NULL,NULL,NULL,'jmsulliv@genre.com');
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('IL','Illinois','Chicago','Alissa Larson','alissa.larson@genre.com','312 292-1485',NULL,'Jimmy Gulick','james.gulick@genre.com','312 207-5318',NULL,'craig.wadman@genre.com');
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('IN','Indiana','Chicago','Alissa Larson','alissa.larson@genre.com','312 292-1485',NULL,'Jimmy Gulick','james.gulick@genre.com','312 207-5318',NULL,'craig.wadman@genre.com');
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('KS','Kansas','Mid States (Dallas)','Steve Moran','smoran@genre.com','913 558-6831',NULL,"Tom O'Hara",'tohara@genre.com','614 561-3374',NULL,NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('KY','Kentucky','Philedelphia','Zach Balk','zach.balk@genre.com','614 403-9806',NULL,'Brandon Yez','byez@genre.com','215 988-7116',NULL,NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('LA','Louisiana','Mid States (Dallas)','Steve Moran','smoran@genre.com','913 558-6831',NULL,"Tom O'Hara",'tohara@genre.com','614 561-3374',NULL,NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('MA','Massachusetts','Northeast (Hartford)','Emily Carr','emily.carr@genre.com','617 728-3826',NULL,'Jackie DiMatteo','jdimatt@genre.com','203 570-3504',NULL,'lsirois@genre.com');
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('MD','Maryland','Philedelphia','Zach Balk','zach.balk@genre.com','614 403-9806',NULL,'Brandon Yez','byez@genre.com','215 988-7116',NULL,NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('ME','Maine','Northeast (Hartford)','Emily Carr','emily.carr@genre.com','617 728-3826',NULL,'Jackie DiMatteo','jdimatt@genre.com','203 570-3504',NULL,'lsirois@genre.com');
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('MI','Michigan','Chicago','Alissa Larson','alissa.larson@genre.com','312 292-1485',NULL,'Jimmy Gulick','james.gulick@genre.com','312 207-5318',NULL,'craig.wadman@genre.com');
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('MN','Minnesota','Chicago','Alissa Larson','alissa.larson@genre.com','312 292-1485',NULL,'Jimmy Gulick','james.gulick@genre.com','312 207-5318',NULL,'craig.wadman@genre.com');
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('MO','Missouri','Mid States (Dallas)','Steve Moran','smoran@genre.com','913 558-6831',NULL,"Tom O'Hara",'tohara@genre.com','614 561-3374',NULL,NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('MS','Mississippi','Atlanta','Kiambu Robinson','krobins@genre.com','404-405-0992','404 365-6966','Kenny Hosp','kenny.hosp@genre.com','404-617-3840','404 365-6847',NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('MT','Montana','Los Angeles','Kim Mulier','kim.mulier@genre.com','213 630-2416',NULL,NULL,NULL,NULL,NULL,'jmsulliv@genre.com');
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('NC','North Carolina','Atlanta','Kiambu Robinson','krobins@genre.com','404-405-0992','404 365-6966','Kenny Hosp','kenny.hosp@genre.com','404-617-3840','404 365-6847',NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('ND','North Dakota','Chicago','Alissa Larson','alissa.larson@genre.com','312 292-1485',NULL,'Jimmy Gulick','james.gulick@genre.com','312 207-5318',NULL,'craig.wadman@genre.com');
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('NE','Nebraska','Mid States (Dallas)','Steve Moran','smoran@genre.com','913 558-6831',NULL,"Tom O'Hara",'tohara@genre.com','614 561-3374',NULL,NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('NH','New Hampshire','Northeast (Hartford)','Emily Carr','emily.carr@genre.com','617 728-3826',NULL,'Jackie DiMatteo','jdimatt@genre.com','203 570-3504',NULL,'lsirois@genre.com');
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('NJ','New Jersey','Philedelphia','Zach Balk','zach.balk@genre.com','614 403-9806',NULL,'Brandon Yez','byez@genre.com','215 988-7116',NULL,NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('NM','New Mexico','Mid States (Dallas)','Steve Moran','smoran@genre.com','913 558-6831',NULL,"Tom O'Hara",'tohara@genre.com','614 561-3374',NULL,NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('NV','Nevada','Los Angeles','Kim Mulier','kim.mulier@genre.com','213 630-2416',NULL,NULL,NULL,NULL,NULL,'jmsulliv@genre.com');
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('NY','New York','New York','Mark Morrison','mark.morrison@genre.com','203 914-6342',NULL,'Bryan May','bryan.may@genre.com','212 341-8011',NULL,NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('OH','Ohio','Philedelphia','Zach Balk','zach.balk@genre.com','614 403-9806',NULL,'Brandon Yez','byez@genre.com','215 988-7116',NULL,NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('OK','Oklahoma','Mid States (Dallas)','Steve Moran','smoran@genre.com','913 558-6831',NULL,"Tom O'Hara",'tohara@genre.com','614 561-3374',NULL,NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('OR','Oregon','Los Angeles','Kim Mulier','kim.mulier@genre.com','213 630-2416',NULL,NULL,NULL,NULL,NULL,'jmsulliv@genre.com');
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('PA','Pennsylvania','Philedelphia','Zach Balk','zach.balk@genre.com','614 403-9806',NULL,'Brandon Yez','byez@genre.com','215 988-7116',NULL,NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('RI','Rhode Island','Northeast (Hartford)','Emily Carr','emily.carr@genre.com','617 728-3826',NULL,'Jackie DiMatteo','jdimatt@genre.com','203 570-3504',NULL,'lsirois@genre.com');
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('SC','South Carolina','Atlanta','Kiambu Robinson','krobins@genre.com','404-405-0992','404 365-6966','Kenny Hosp','kenny.hosp@genre.com','404-617-3840','404 365-6847',NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('SD','South Dakota','Chicago','Alissa Larson','alissa.larson@genre.com','312 292-1485',NULL,'Jimmy Gulick','james.gulick@genre.com','312 207-5318',NULL,'craig.wadman@genre.com');
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('TN','Tennessee','Atlanta','Kiambu Robinson','krobins@genre.com','404-405-0992','404 365-6966','Kenny Hosp','kenny.hosp@genre.com','404-617-3840','404 365-6847',NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('TX','Texas','Mid States (Dallas)','Steve Moran','smoran@genre.com','913 558-6831',NULL,"Tom O'Hara",'tohara@genre.com','614 561-3374',NULL,NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('UT','Utah','Los Angeles','Kim Mulier','kim.mulier@genre.com','213 630-2416',NULL,NULL,NULL,NULL,NULL,'jmsulliv@genre.com');
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('VA','Virginia','Philedelphia','Zach Balk','zach.balk@genre.com','614 403-9806',NULL,'Brandon Yez','byez@genre.com','215 988-7116',NULL,NULL);
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('VT','Vermont','Northeast (Hartford)','Emily Carr','emily.carr@genre.com','617 728-3826',NULL,'Jackie DiMatteo','jdimatt@genre.com','203 570-3504',NULL,'lsirois@genre.com');
INSERT INTO genre_info(`state_in_short`,`hubstate`,`office`,`primary_genre`,`primary_email`,`primary_phone`,`secondary_phone`,`backup_genre`,`backup_email`,`backup_primary_phone`,`backup_secondary_phone`,`additional_cc`) VALUES ('WA','Washington','Los Angeles','Kim Mulier','kim.mulier@genre.com','213 630-2416',NULL,NULL,NULL,NULL,NULL,'jmsulliv@genre.com');
