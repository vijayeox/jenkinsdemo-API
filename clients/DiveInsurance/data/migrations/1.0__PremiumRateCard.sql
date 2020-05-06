CREATE TABLE IF NOT EXISTS `premium_rate_card` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `product` varchar(255) NOT NULL,
              `coverage` varchar(500) NOT NULL,
              `key` varchar(500) NOT NULL,
              `start_date` datetime NULL,
              `end_date` datetime NULL,
              `premium` DECIMAL(8,2),
              `type` enum('VALUE', 'PERCENT') NOT NULL DEFAULT 'VALUE',
              `tax` DECIMAL(8,2),
              `padi_fee` DECIMAL(8,2),
              `total` DECIMAL(8,2),
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Instructor','instructor','2019-06-30', '2019-07-31',645,28,26,659);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Instructor','instructor','2019-08-01', '2019-08-31',592,25,26,607);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2019-09-01', '2019-09-30',538,23,26,554);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2019-10-01', '2019-10-31',484,21,26,502);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2019-11-01', '2019-11-30',430,18,26,448);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2019-12-01', '2019-12-31',377,16,26,396);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2020-01-01', '2020-01-31',363,14,26,343);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2020-02-01', '2020-02-29',269,12,26,290);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2020-03-01', '2020-03-31',215,9,26,238);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2020-04-01', '2020-04-30',162,7,26,185);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2020-05-01', '2020-05-31',108,5,26,132);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2020-06-01', '2020-06-30',54,2,26,79);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Instructor','instructor','2020-06-30', '2020-07-31',645,28,26,699);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Instructor','instructor','2020-08-01', '2020-08-31',592,25,26,643);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2020-09-01', '2020-09-30',538,23,26,587);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2020-10-01', '2020-10-31',484,21,26,531);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2020-11-01', '2020-11-30',430,18,26,474);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2020-12-01', '2020-12-31',377,16,26,419);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2021-01-01', '2021-01-31',363,14,26,363);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2021-02-01', '2021-02-28',269,12,26,307);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2021-03-01', '2021-03-31',215,9,26,250);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2021-04-01', '2021-04-30',162,7,26,195);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2021-05-01', '2021-05-31',108,5,26,139);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2021-06-01', '2021-06-30',54,2,26,82);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2019-06-30', '2019-07-31',404,17,16,390);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2019-08-01', '2019-08-31',371,16,26,370);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2019-09-01', '2019-09-30',337,14,26,339);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2019-10-01', '2019-10-31',303,13,26,308);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2019-11-01', '2019-11-30',270,12,26,276);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2019-12-01', '2019-12-31',236,10,26,245);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2020-01-01', '2020-01-31',202,9,26,214);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2020-02-01', '2020-02-29',169,7,26,182);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2020-03-01', '2020-03-31',135,6,26,151);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2020-04-01', '2020-04-30',101,4,26,120);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2020-05-01', '2020-05-31',68,3,26,89);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2020-06-01', '2020-06-30',34,1,26,57);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2020-06-30', '2020-07-31',404,17,16,437);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2020-08-01', '2020-08-31',371,16,26,413);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2020-09-01', '2020-09-30',337,14,26,377);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2020-10-01', '2020-10-31',303,13,26,342);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2020-11-01', '2020-11-30',270,12,26,308);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2020-12-01', '2020-12-31',236,10,26,272);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2021-01-01', '2021-01-31',202,9,26,237);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2021-02-01', '2021-02-28',169,7,26,202);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2021-03-01', '2021-03-31',135,6,26,167);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2021-04-01', '2021-04-30',101,4,26,131);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2021-05-01', '2021-05-31',68,3,26,97);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2021-06-01', '2021-06-30',34,1,26,61);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2019-06-30', '2019-07-31',404,17,16,390);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2019-08-01', '2019-08-31',371,16,26,370);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2019-09-01', '2019-09-30',337,14,26,339);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2019-10-01', '2019-10-31',303,13,26,308);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2019-11-01', '2019-11-30',270,12,26,276);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2019-12-01', '2019-12-31',236,10,26,245);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2020-01-01', '2020-01-31',202,9,26,214);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2020-02-01', '2020-02-29',169,7,26,182);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2020-03-01', '2020-03-31',135,6,26,151);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2020-04-01', '2020-04-30',101,4,26,120);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2020-05-01', '2020-05-31',68,3,26,89);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2020-06-01', '2020-06-30',34,1,26,57);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2020-06-30', '2020-07-31',404,17,16,437);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2020-08-01', '2020-08-31',371,16,26,413);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2020-09-01', '2020-09-30',337,14,26,377);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2020-10-01', '2020-10-31',303,13,26,342);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2020-11-01', '2020-11-30',270,12,26,308);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2020-12-01', '2020-12-31',236,10,26,272);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2021-01-01', '2021-01-31',202,9,26,236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2021-02-01', '2021-02-28',169,7,26,202);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2021-03-01', '2021-03-31',135,6,26,167);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2021-04-01', '2021-04-30',101,4,26,131);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2021-05-01', '2021-05-31',68,3,26,97);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2021-06-01', '2021-06-30',34,1,26,61);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2019-06-30', '2019-07-31',404,17,16,390);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2019-08-01', '2019-08-31',371,16,26,370);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2019-09-01', '2019-09-30',337,14,26,339);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2019-10-01', '2019-10-31',303,13,26,308);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2019-11-01', '2019-11-30',270,12,26,276);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2019-12-01', '2019-12-31',236,10,26,245);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2020-01-01', '2020-01-31',202,9,26,214);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2020-02-01', '2020-02-29',169,7,26,182);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2020-03-01', '2020-03-31',135,6,26,151);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2020-04-01', '2020-04-30',101,4,26,120);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2020-05-01', '2020-05-31',68,3,26,89);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2020-06-01', '2020-06-30',34,1,26,57);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2020-06-30', '2020-07-31',437,17,16,437);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2020-08-01', '2020-08-31',413,16,26,413);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2020-09-01', '2020-09-30',377,14,26,377);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2020-10-01', '2020-10-31',342,13,26,342);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2020-11-01', '2020-11-30',308,12,26,308);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2020-12-01', '2020-12-31',272,10,26,272);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2021-01-01', '2021-01-31',237,9,26,236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2021-02-01', '2021-02-28',202,7,26,202);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2021-03-01', '2021-03-31',167,6,26,167);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2021-04-01', '2021-04-30',131,4,26,131);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2021-05-01', '2021-05-31',97,3,26,97);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2021-06-01', '2021-06-30',61,1,26,61);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly','2019-06-30', '2019-07-31',275,12,16,270);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly','2019-08-01', '2019-08-31',253,11,26,260);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2019-09-01', '2019-09-30',230,10,26,239);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2019-10-01', '2019-10-31',207,9,26,217);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2019-11-01', '2019-11-30',184,8,26,196);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2019-12-01', '2019-12-31',161,7,26,175);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2020-01-01', '2020-01-31',138,1,26,153);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2020-02-01', '2020-02-29',115,5,26,132);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2020-03-01', '2020-03-31',92,4,26,112);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2020-04-01', '2020-04-30',69,3,26,90);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2020-05-01', '2020-05-31',46,2,26,69);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2020-06-01', '2020-06-30',23,1,26,48);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly','2020-06-30', '2020-07-31',275,12,16,303);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly','2020-08-01', '2020-08-31',253,11,26,290);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2020-09-01', '2020-09-30',230,10,26,266);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2020-10-01', '2020-10-31',207,9,26,242);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2020-11-01', '2020-11-30',184,8,26,218);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2020-12-01', '2020-12-31',161,7,26,194);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2021-01-01', '2021-01-31',138,1,26,170);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2021-02-01', '2021-02-28',115,5,26,146);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2021-03-01', '2021-03-31',92,4,26,122);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2021-04-01', '2021-04-30',69,3,26,98);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2021-05-01', '2021-05-31',46,2,26,74);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2021-06-01', '2021-06-30',23,1,26,50);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2019-06-30', '2019-07-31',404,17,16,390);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2019-08-01', '2019-08-31',371,16,26,370);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2019-09-01', '2019-09-30',337,14,26,339);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2019-10-01', '2019-10-31',303,13,26,308);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2019-11-01', '2019-11-30',270,12,26,276);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2019-12-01', '2019-12-31',236,10,26,245);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2020-01-01', '2020-01-31',202,9,26,214);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2020-02-01', '2020-02-29',169,7,26,182);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2020-03-01', '2020-03-31',135,6,26,151);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2020-04-01', '2020-04-30',101,4,26,120);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2020-05-01', '2020-05-31',68,3,26,89);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2020-06-01', '2020-06-30',34,1,26,57);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2020-06-30', '2020-07-31',404,17,16,437);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2020-08-01', '2020-08-31',371,16,26,413);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2020-09-01', '2020-09-30',337,14,26,377);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2020-10-01', '2020-10-31',303,13,26,342);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2020-11-01', '2020-11-30',270,12,26,308);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2020-12-01', '2020-12-31',236,10,26,272);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2021-01-01', '2021-01-31',202,9,26,237);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2021-02-01', '2021-02-28',169,7,26,202);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2021-03-01', '2021-03-31',135,6,26,167);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2021-04-01', '2021-04-30',101,4,26,131);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2021-05-01', '2021-05-31',68,3,26,97);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2021-06-01', '2021-06-30',34,1,26,61);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2019-06-30', '2019-07-31',404,17,16,390);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2019-08-01', '2019-08-31',371,16,26,370);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2019-09-01', '2019-09-30',337,14,26,339);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2019-10-01', '2019-10-31',303,13,26,308);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2019-11-01', '2019-11-30',270,12,26,276);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2019-12-01', '2019-12-31',236,10,26,245);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2020-01-01', '2020-01-31',202,9,26,214);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2020-02-01', '2020-02-29',169,7,26,182);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2020-03-01', '2020-03-31',135,6,26,151);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2020-04-01', '2020-04-30',101,4,26,120);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2020-05-01', '2020-05-31',68,3,26,89);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2020-06-01', '2020-06-30',34,1,26,57);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2020-06-30', '2020-07-31',404,17,16,437);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2020-08-01', '2020-08-31',371,16,26,413);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2020-09-01', '2020-09-30',337,14,26,377);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2020-10-01', '2020-10-31',303,13,26,342);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2020-11-01', '2020-11-30',270,12,26,308);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2020-12-01', '2020-12-31',236,10,26,272);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2021-01-01', '2021-01-31',202,9,26,237);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2021-02-01', '2021-02-28',169,7,26,202);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2021-03-01', '2021-03-31',135,6,26,167);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2021-04-01', '2021-04-30',101,4,26,131);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2021-05-01', '2021-05-31',68,3,26,97);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2021-06-01', '2021-06-30',34,1,26,61);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2019-06-30', '2019-07-31',371,0,26,356);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2019-08-01', '2019-08-31',341,0,26,329);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2019-09-01', '2019-09-30',310,0,26,301);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2019-10-01', '2019-10-31',279,0,26,274);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2019-11-01', '2019-11-30',248,0,26,246);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2019-12-01', '2019-12-31',217,0,26,219);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2020-01-01', '2020-01-31',186,0,26,191);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2020-02-01', '2020-02-29',155,0,26,164);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2020-03-01', '2020-03-31',124,0,26,136);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2020-04-01', '2020-04-30',93,0,26,109);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2020-05-01', '2020-05-31',62,0,26,81);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2020-06-01', '2020-06-30',31,0,26,54);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2020-06-30', '2020-07-31',371,0,26,397);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2020-08-01', '2020-08-31',341,0,26,367);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2020-09-01', '2020-09-30',310,0,26,336);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2020-10-01', '2020-10-31',279,0,26,305);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2020-11-01', '2020-11-30',248,0,26,274);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2020-12-01', '2020-12-31',217,0,26,243);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2021-01-01', '2021-01-31',186,0,26,212);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2021-02-01', '2021-02-28',155,0,26,181);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2021-03-01', '2021-03-31',124,0,26,150);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2021-04-01', '2021-04-30',93,0,26,119);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2021-05-01', '2021-05-31',62,0,26,88);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2021-06-01', '2021-06-30',31,0,26,57);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2019-06-30', '2019-07-31',237,0,26,237);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2019-08-01', '2019-08-31',218,0,26,220);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2019-09-01', '2019-09-30',198,0,26,202);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2019-10-01', '2019-10-31',178,0,26,185);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2019-11-01', '2019-11-30',158,0,26,167);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2019-12-01', '2019-12-31',139,0,26,150);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2020-01-01', '2020-01-31',119,0,26,132);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2020-02-01', '2020-02-29',99,0,26,114);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2020-03-01', '2020-03-31',79,0,26,97);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2020-04-01', '2020-04-30',60,0,26,79);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2020-05-01', '2020-05-31',40,0,26,62);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2020-06-01', '2020-06-30',20,0,26,44);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2020-06-30', '2020-07-31',237,0,26,263);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2020-08-01', '2020-08-31',218,0,26,244);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2020-09-01', '2020-09-30',198,0,26,224);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2020-10-01', '2020-10-31',178,0,26,204);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2020-11-01', '2020-11-30',158,0,26,184);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2020-12-01', '2020-12-31',139,0,26,165);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2021-01-01', '2021-01-31',119,0,26,145);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2021-02-01', '2021-02-28',99,0,26,125);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2021-03-01', '2021-03-31',79,0,26,105);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2021-04-01', '2021-04-30',60,0,26,86);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2021-05-01', '2021-05-31',40,0,26,66);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2021-06-01', '2021-06-30',20,0,26,46);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2019-06-30', '2019-07-31',237,0,26,237);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2019-08-01', '2019-08-31',218,0,26,220);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2019-10-01', '2019-10-31',198,0,26,202);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2019-09-01', '2019-09-30',178,0,26,185);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2019-11-01', '2019-11-30',158,0,26,167);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2019-12-01', '2019-12-31',139,0,26,150);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2020-01-01', '2020-01-31',119,0,26,132);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2020-02-01', '2020-02-29',99,0,26,114);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2020-03-01', '2020-03-31',79,0,26,97);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2020-04-01', '2020-04-30',60,0,26,79);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2020-05-01', '2020-05-31',40,0,26,62);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2020-06-01', '2020-06-30',20,0,26,44);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2020-06-30', '2020-07-31',237,0,26,263);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2020-08-01', '2020-08-31',218,0,26,244);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2020-10-01', '2020-10-31',198,0,26,224);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2020-09-01', '2020-09-30',178,0,26,204);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2020-11-01', '2020-11-30',158,0,26,184);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2020-12-01', '2020-12-31',139,0,26,165);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2021-01-01', '2021-01-31',119,0,26,145);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2021-02-01', '2021-02-28',99,0,26,125);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2021-03-01', '2021-03-31',79,0,26,105);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2021-04-01', '2021-04-30',60,0,26,86);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2021-05-01', '2021-05-31',40,0,26,66);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2021-06-01', '2021-06-30',20,0,26,46);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2019-06-30', '2019-07-31',237,0,26,237);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2019-08-01', '2019-07-31',218,0,26,220);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2019-09-01', '2019-09-30',198,0,26,202);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2019-10-01', '2019-10-31',178,0,26,185);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2019-11-01', '2019-11-30',158,0,26,167);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2019-12-01', '2019-12-31',139,0,26,150);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2020-01-01', '2020-01-31',119,0,26,132);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2020-02-01', '2020-02-29',99,0,26,114);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2020-03-01', '2020-03-31',79,0,26,97);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2020-04-01', '2020-04-30',60,0,26,79);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2020-05-01', '2020-05-31',40,0,26,62);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2020-06-01', '2020-06-30',20,0,26,44);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2020-06-30', '2020-07-31',237,0,26,263);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2020-08-01', '2020-07-31',218,0,26,244);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2020-09-01', '2020-09-30',198,0,26,224);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2020-10-01', '2020-10-31',178,0,26,204);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2020-11-01', '2020-11-30',158,0,26,184);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2020-12-01', '2020-12-31',139,0,26,165);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2021-01-01', '2021-01-31',119,0,26,145);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2021-02-01', '2021-02-28',99,0,26,125);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2021-03-01', '2021-03-31',79,0,26,105);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2021-04-01', '2021-04-30',60,0,26,86);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2021-05-01', '2021-05-31',40,0,26,66);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2021-06-01', '2021-06-30',20,0,26,46);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2019-06-30', '2019-07-31',42,2,11,337);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2019-08-01', '2019-08-31',39,2,11,310);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2019-09-01', '2019-09-30',35,2,11,283);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2019-10-01', '2019-10-31',32,1,11,256);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2019-11-01', '2019-11-30',28,1,11,229);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2019-12-01', '2019-12-31',25,1,11,202);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2020-01-01', '2020-01-31',21,1,11,175);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2020-02-01', '2020-02-29',18,1,11,148);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2020-03-01', '2020-03-31',14,1,11,121);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2020-04-01', '2020-04-30',11,0,11,93);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2020-05-01', '2020-05-31',7,0,11,66);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2020-06-01', '2020-06-30',4,0,11,39);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2020-06-30', '2020-07-31',42,2,11,378);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2020-08-01', '2020-08-31',39,2,11,348);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2020-09-01', '2020-09-30',35,2,11,318);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2020-10-01', '2020-10-31',32,1,11,286);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2020-11-01', '2020-11-30',28,1,11,256);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2020-12-01', '2020-12-31',25,1,11,226);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2021-01-01', '2021-01-31',21,1,11,195);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2021-02-01', '2021-02-28',18,1,11,164);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2021-03-01', '2021-03-31',14,1,11,134);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2021-04-01', '2021-04-30',11,0,11,103);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2021-05-01', '2021-05-31',7,0,11,73);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2021-06-01', '2021-06-30',4,0,11,42);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Scuba Fit Instructor','scubaFitInstructor', '2019-06-30', '2019-07-31',60,0,0,60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Scuba Fit Instructor','scubaFitInstructor', '2019-08-01', '2019-08-31',60,0,0,60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Scuba Fit Instructor','scubaFitInstructor', '2019-09-01', '2019-09-30',60,0,0,60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Scuba Fit Instructor','scubaFitInstructor', '2019-10-01', '2019-10-31',60,0,0,60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Scuba Fit Instructor','scubaFitInstructor', '2019-11-01', '2019-11-30',60,0,0,60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Scuba Fit Instructor','scubaFitInstructor', '2019-12-01', '2019-12-31',60,0,0,60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Scuba Fit Instructor','scubaFitInstructor', '2020-01-01', '2020-01-31',60,0,0,60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Scuba Fit Instructor','scubaFitInstructor', '2020-02-01', '2020-02-29',60,0,0,60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Scuba Fit Instructor','scubaFitInstructor', '2020-03-01', '2020-03-31',60,0,0,60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Scuba Fit Instructor','scubaFitInstructor', '2020-04-01', '2020-04-30',60,0,0,60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Scuba Fit Instructor','scubaFitInstructor', '2020-05-01', '2020-05-31',60,0,0,60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`) VALUES ('Individual Professional Liability', 'Scuba Fit Instructor','scubaFitInstructor', '2020-06-01', '2020-06-30',60,0,0,60);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Inspector & Instructor','cylinderInspectorAndInstructor','2019-06-30', '2019-07-31',281,12,293);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Inspector & Instructor','cylinderInspectorAndInstructor','2019-08-01', '2019-08-31',258,11,269);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Inspector & Instructor','cylinderInspectorAndInstructor', '2019-09-01', '2019-09-30',235,10,245);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Inspector & Instructor','cylinderInspectorAndInstructor', '2019-10-01', '2019-10-31',211,9,220);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Inspector & Instructor','cylinderInspectorAndInstructor', '2019-11-01', '2019-11-30',188,8,196);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Inspector & Instructor','cylinderInspectorAndInstructor', '2019-12-01', '2019-12-31',164,7,171);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Inspector & Instructor','cylinderInspectorAndInstructor', '2020-01-01', '2020-01-31',141,6,147);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Inspector & Instructor','cylinderInspectorAndInstructor', '2020-02-01', '2020-02-29',118,5,123);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Inspector & Instructor','cylinderInspectorAndInstructor', '2020-03-01', '2020-03-31',94,4,98);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Inspector & Instructor','cylinderInspectorAndInstructor', '2020-04-01', '2020-04-30',71,3,74);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Inspector & Instructor','cylinderInspectorAndInstructor', '2020-05-01', '2020-05-31',47,2,49);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Inspector & Instructor','cylinderInspectorAndInstructor', '2020-06-01', '2020-06-30',24,1,25);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Instructor','cylinderInstructor', '2019-06-30', '2019-07-31',118,5,123);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Instructor','cylinderInstructor', '2019-08-01', '2019-08-31',109,5,114);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Instructor','cylinderInstructor', '2019-09-01', '2019-09-30',99,4,103);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Instructor','cylinderInstructor', '2019-10-01', '2019-10-31',89,4,93);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Instructor','cylinderInstructor', '2019-11-01', '2019-11-30',79,3,82);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Instructor','cylinderInstructor', '2019-12-01', '2019-12-31',69,3,72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Instructor','cylinderInstructor', '2020-01-01', '2020-01-31',59,3,62);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Instructor','cylinderInstructor', '2020-02-01', '2020-02-29',50,2,52);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Instructor','cylinderInstructor', '2020-03-01', '2020-03-31',40,2,42);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Instructor','cylinderInstructor', '2020-04-01', '2020-04-30',30,1,31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Instructor','cylinderInstructor', '2020-05-01', '2020-05-31',20,1,21);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Instructor','cylinderInstructor', '2020-06-01', '2020-06-30',10,1,11);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage','equipmentLiabilityCoverage','2019-06-30', '2019-07-31',299);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage','equipmentLiabilityCoverage','2019-08-01', '2019-08-31',275);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage','equipmentLiabilityCoverage', '2019-09-01', '2019-09-30',250);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage','equipmentLiabilityCoverage', '2019-10-01', '2019-10-31',225);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage','equipmentLiabilityCoverage', '2019-11-01', '2019-11-30',200);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage','equipmentLiabilityCoverage', '2019-12-01', '2019-12-31',175);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage','equipmentLiabilityCoverage', '2020-01-01', '2020-01-31',150);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage','equipmentLiabilityCoverage', '2020-02-01', '2020-02-29',125);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage','equipmentLiabilityCoverage', '2020-03-01', '2020-03-31',100);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage','equipmentLiabilityCoverage', '2020-04-01', '2020-04-30',75);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage','equipmentLiabilityCoverage', '2020-05-01', '2020-05-31',50);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage','equipmentLiabilityCoverage', '2020-06-01', '2020-06-30',25);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined','2019-06-30', '2019-07-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined','2019-08-01', '2019-08-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined', '2019-09-01', '2019-09-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined', '2019-11-01', '2019-11-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined', '2019-12-01', '2019-12-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined', '2020-01-01', '2020-01-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined', '2020-02-01', '2020-02-29',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined', '2020-03-01', '2020-03-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined', '2020-04-01', '2020-04-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined', '2020-05-01', '2020-05-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined', '2020-06-01', '2020-06-30',0);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '0 Excess','excessLiabilityCoverageDeclined', '2019-06-30', '2019-09-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '0 Excess','excessLiabilityCoverageDeclined','2019-08-01', '2019-08-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '0 Excess','excessLiabilityCoverageDeclined', '2019-09-01', '2019-09-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '0 Excess','excessLiabilityCoverageDeclined', '2019-10-01', '2019-10-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '0 Excess','excessLiabilityCoverageDeclined', '2019-11-01', '2019-11-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '0 Excess','excessLiabilityCoverageDeclined', '2020-01-01', '2020-01-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '0 Excess','excessLiabilityCoverageDeclined', '2020-02-01', '2020-02-29',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '0 Excess','excessLiabilityCoverageDeclined', '2020-04-01', '2020-04-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '0 Excess','excessLiabilityCoverageDeclined', '2020-05-01', '2020-06-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '0 Excess','excessLiabilityCoverageDeclined', '2020-06-01', '2020-06-30',0);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000','2019-06-30', '2019-07-31',467,20,487);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000','2019-08-01', '2019-08-31',429,18,447);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000', '2019-09-01', '2019-09-30',390,17,407);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000', '2019-10-01', '2019-10-31',351,15,366);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000', '2019-11-01', '2019-11-30',312,13,325);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000', '2019-12-01', '2019-12-31',273,12,285);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000', '2020-01-01', '2020-01-31',234,10,244);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000', '2020-02-01', '2020-02-29',195,8,203);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000', '2020-03-01', '2020-03-31',156,7,163);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000', '2020-04-01', '2020-04-30',117,5,122);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000', '2020-05-01', '2020-05-31',78,3,81);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000', '2020-06-01', '2020-06-30',39,2,41);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '2M Excess','excessLiabilityCoverage2000000','2019-06-30', '2019-07-31',936,40,976);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '2M Excess','excessLiabilityCoverage2000000','2019-08-01', '2019-08-31',858,37,895);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '2M Excess','excessLiabilityCoverage2000000', '2019-09-01', '2019-09-30',780,34,814);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '2M Excess','excessLiabilityCoverage2000000', '2019-10-01', '2019-10-31',702,30,732);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '2M Excess','excessLiabilityCoverage2000000', '2019-11-01', '2019-11-30',624,27,651);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '2M Excess','excessLiabilityCoverage2000000', '2019-12-01', '2019-12-31',546,23,569);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '2M Excess','excessLiabilityCoverage2000000', '2020-01-01', '2020-01-31',468,20,488);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '2M Excess','excessLiabilityCoverage2000000', '2020-02-01', '2020-02-29',390,17,407);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '2M Excess','excessLiabilityCoverage2000000', '2020-03-01', '2020-03-31',312,13,325);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '2M Excess','excessLiabilityCoverage2000000', '2020-04-01', '2020-04-30',234,10,244);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '2M Excess','excessLiabilityCoverage2000000', '2020-05-01', '2020-05-31',156,7,163);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '2M Excess','excessLiabilityCoverage2000000', '2020-06-01', '2020-06-30',78,3,81);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '3M Excess','excessLiabilityCoverage3000000', '2019-06-30', '2019-07-31',1215,52,1267);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '3M Excess','excessLiabilityCoverage3000000', '2019-08-01', '2019-08-31',1114,48,1162);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '3M Excess','excessLiabilityCoverage3000000', '2019-09-01', '2019-09-30',1013,44,1057);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '3M Excess','excessLiabilityCoverage3000000', '2019-10-01', '2019-10-31',912,39,951);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '3M Excess','excessLiabilityCoverage3000000', '2019-11-01', '2019-11-30',810,35,845);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '3M Excess','excessLiabilityCoverage3000000', '2019-12-01', '2019-12-31',709,30,739);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '3M Excess','excessLiabilityCoverage3000000', '2020-01-01', '2020-01-31',608,26,634);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '3M Excess','excessLiabilityCoverage3000000', '2020-02-01', '2020-02-29',507,22,529);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '3M Excess','excessLiabilityCoverage3000000', '2020-03-01', '2020-03-31',405,17,422);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '3M Excess','excessLiabilityCoverage3000000', '2020-04-01', '2020-04-30',304,13,317);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '3M Excess','excessLiabilityCoverage3000000', '2020-05-01', '2020-05-31',203,9,212);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '3M Excess','excessLiabilityCoverage3000000', '2020-06-01', '2020-06-30',102,4,106);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '4M Excess','excessLiabilityCoverage4000000','2019-06-30', '2019-07-31',1526,66,1592);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '4M Excess','excessLiabilityCoverage4000000','2019-08-01', '2019-08-31',1399,60,1459);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '4M Excess','excessLiabilityCoverage4000000', '2019-09-01', '2019-09-30',1272,55,1327);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '4M Excess','excessLiabilityCoverage4000000', '2019-10-01', '2019-10-31',1145,49,1194);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '4M Excess','excessLiabilityCoverage4000000', '2019-11-01', '2019-11-30',1018,44,1062);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '4M Excess','excessLiabilityCoverage4000000', '2019-12-01', '2019-12-31',891,38,929);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '4M Excess','excessLiabilityCoverage4000000', '2020-01-01', '2020-01-31',763,33,796);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '4M Excess','excessLiabilityCoverage4000000', '2020-02-01', '2020-02-29',636,27,663);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '4M Excess','excessLiabilityCoverage4000000', '2020-03-01', '2020-03-31',509,22,529);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '4M Excess','excessLiabilityCoverage4000000', '2020-04-01', '2020-04-30',382,16,398);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '4M Excess','excessLiabilityCoverage4000000', '2020-05-01', '2020-05-31',255,11,266);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '4M Excess','excessLiabilityCoverage4000000', '2020-06-01', '2020-06-30',128,6,134);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '9M Excess','excessLiabilityCoverage9000000', '2019-06-30', '2019-07-31',3408,147,3555);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '9M Excess','excessLiabilityCoverage9000000','2019-08-01', '2019-08-31',3124,134,3258);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '9M Excess','excessLiabilityCoverage9000000', '2019-09-01', '2019-09-30',2840,122,2962);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '9M Excess','excessLiabilityCoverage9000000', '2019-10-01', '2019-10-31',2556,110,2666);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '9M Excess','excessLiabilityCoverage9000000', '2019-11-01', '2019-11-30',2272,98,2370);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '9M Excess','excessLiabilityCoverage9000000', '2019-12-01', '2019-12-31',1988,85,2073);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '9M Excess','excessLiabilityCoverage9000000', '2020-01-01', '2020-01-31',1704,73,1777);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '9M Excess','excessLiabilityCoverage9000000', '2020-02-01', '2020-02-29',1420,61,1481);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '9M Excess','excessLiabilityCoverage9000000', '2020-03-01', '2020-03-31',1136,49,1185);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '9M Excess','excessLiabilityCoverage9000000', '2020-04-01', '2020-04-30',852,37,889);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '9M Excess','excessLiabilityCoverage9000000', '2020-05-01', '2020-05-31',568,24,492);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', '9M Excess','excessLiabilityCoverage9000000', '2020-06-01', '2020-06-30',284,12,296);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2019-12-01', '2019-12-31',149);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly','2019-06-30', '2019-07-31',139);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly','2019-08-01', '2019-08-31',129);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2019-09-01', '2019-09-30',119);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2019-10-01', '2019-10-31',108);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2019-11-01', '2019-11-30',98);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2020-01-01', '2020-01-31',88);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2020-02-01', '2020-02-29',78);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2020-03-01', '2020-03-31',67);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2020-04-01', '2020-04-30',57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2020-05-01', '2020-05-31',47);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2020-06-01', '2020-06-30',37);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly','2020-06-30', '2020-07-31',164);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly','2020-08-01', '2020-08-31',153);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2020-09-01', '2020-09-30',141);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2020-10-01', '2020-10-31',130);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2020-11-01', '2020-11-30',118);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2020-12-01', '2020-12-30',107);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2021-01-01', '2021-01-31',95);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2021-02-01', '2021-02-28',84);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2021-03-01', '2021-03-31',72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2021-04-01', '2021-04-30',61);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2021-05-01', '2021-05-31',49);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2021-06-01', '2021-06-30',38);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'ScubaFit – Declined','scubaFitInstructorDeclined', '2019-06-30', '2019-07-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'ScubaFit – Declined','scubaFitInstructorDeclined', '2019-08-01', '2019-08-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'ScubaFit – Declined','scubaFitInstructorDeclined', '2019-09-01', '2019-09-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'ScubaFit – Declined','scubaFitInstructorDeclined', '2019-10-01', '2019-10-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'ScubaFit – Declined','scubaFitInstructorDeclined', '2019-11-01', '2019-11-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'ScubaFit – Declined','scubaFitInstructorDeclined', '2019-12-01', '2019-12-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'ScubaFit – Declined','scubaFitInstructorDeclined', '2020-01-01', '2020-01-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'ScubaFit – Declined','scubaFitInstructorDeclined', '2020-02-01', '2020-02-29',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'ScubaFit – Declined','scubaFitInstructorDeclined', '2020-03-01', '2020-03-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'ScubaFit – Declined','scubaFitInstructorDeclined','2020-04-01', '2020-04-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'ScubaFit – Declined','scubaFitInstructorDeclined', '2020-05-01', '2020-05-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'ScubaFit – Declined','scubaFitInstructorDeclined', '2020-06-01', '2020-06-30',0);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Inspector','cylinderInspector','2019-06-30', '2019-07-31',225,10,235);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Inspector','cylinderInspector','2019-08-01', '2019-08-31',207,9,216);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Inspector','cylinderInspector', '2019-09-01', '2019-09-30',188,8,196);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Inspector','cylinderInspector', '2019-10-01', '2019-10-31',169,7,176);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Inspector','cylinderInspector', '2019-11-01', '2019-11-30',150,6,156);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Inspector','cylinderInspector', '2019-12-01', '2019-12-31',132,6,138);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Inspector','cylinderInspector', '2020-01-01', '2020-01-31',113,5,118);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Inspector','cylinderInspector', '2020-02-01', '2020-02-29',94,4,98);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Inspector','cylinderInspector', '2020-03-01', '2020-03-31',75,3,78);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Inspector','cylinderInspector', '2020-04-01', '2020-04-30',57,2,59);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Inspector','cylinderInspector', '2020-05-01', '2020-05-31',38,2,40);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`total`) VALUES ('Individual Professional Liability', 'Cylinder Inspector','cylinderInspector', '2020-06-01', '2020-06-30',19,1,20);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector Or Instructor - Declined','cylinderInspectorOrInstructorDeclined','2019-06-30', '2019-07-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector Or Instructor - Declined','cylinderInspectorOrInstructorDeclined','2019-08-01', '2019-08-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector Or Instructor - Declined','cylinderInspectorOrInstructorDeclined', '2019-09-01', '2019-09-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector Or Instructor - Declined','cylinderInspectorOrInstructorDeclined', '2019-10-01', '2019-10-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector Or Instructor - Declined','cylinderInspectorOrInstructorDeclined', '2019-11-01', '2019-11-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector Or Instructor - Declined','cylinderInspectorOrInstructorDeclined', '2020-01-01', '2020-01-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector Or Instructor - Declined','cylinderInspectorOrInstructorDeclined', '2020-02-01', '2020-02-29',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector Or Instructor - Declined','cylinderInspectorOrInstructorDeclined', '2020-03-01', '2020-03-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector Or Instructor - Declined','cylinderInspectorOrInstructorDeclined', '2020-04-01', '2020-04-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector Or Instructor - Declined','cylinderInspectorOrInstructorDeclined', '2020-05-01', '2020-05-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector Or Instructor - Declined','cylinderInspectorOrInstructorDeclined', '2020-06-01', '2020-06-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector Or Instructor - Declined','cylinderInspectorOrInstructorDeclined', '2019-12-01', '2019-12-31',0);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', 'Up to $25,000','', '2019-06-30', '2019-07-31',1386);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$25,001 to $50,000','', '2019-06-30', '2019-07-31',2286);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$50,001 to $100,000','', '2019-06-30', '2019-07-31',2910);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$100,001 to $150,000','', '2019-06-30', '2019-07-31',3534);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$150,001 to $200,000','', '2019-06-30', '2019-07-31',4572);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$200,001 to $250,000','', '2019-06-30', '2019-07-31',4854);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$250,001 to $350,000','', '2019-06-30', '2019-07-31',5820);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$350,001 to $500,000','', '2019-06-30', '2019-07-31',6240);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', 'Over $500,000','', '2019-06-30', '2019-07-31',6930);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', 'Up to $25,000','', '2019-08-01', '2019-08-31',1271);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$25,001 to $50,000','', '2019-08-01', '2019-08-31',2096);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$50,001 to $100,000','', '2019-08-01', '2019-08-31',2668);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$100,001 to $150,000','', '2019-08-01', '2019-08-31',3240);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$150,001 to $200,000','', '2019-08-01', '2019-08-31',4191);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$200,001 to $250,000','', '2019-08-01', '2019-08-31',4450);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$250,001 to $350,000','', '2019-08-01', '2019-08-31',5335);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$350,001 to $500,000','', '2019-08-01', '2019-08-31',5720);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', 'Over $500,000','', '2019-08-01', '2019-08-31',6353);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', 'Up to $25,000','', '2019-09-01', '2019-09-30',1155);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$25,001 to $50,000','', '2019-09-01', '2019-09-30',1905);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$50,001 to $100,000','', '2019-09-01', '2019-09-30',2425);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$100,001 to $150,000','', '2019-09-01', '2019-09-30',2945);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$150,001 to $200,000','', '2019-09-01', '2019-09-30',3810);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$200,001 to $250,000','', '2019-09-01', '2019-09-30',4045);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$250,001 to $350,000','', '2019-09-01', '2019-09-30',4850);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$350,001 to $500,000','', '2019-09-01', '2019-09-30',5200);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', 'Over $500,000','', '2019-09-01', '2019-09-30',5775);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', 'Up to $25,000','', '2019-10-01', '2019-10-31',1040);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$25,001 to $50,000','', '2019-10-01', '2019-10-31',1715);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$50,001 to $100,000','', '2019-10-01', '2019-10-31',2183);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$100,001 to $150,000','', '2019-10-01', '2019-10-31',2651);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$150,001 to $200,000','', '2019-10-01', '2019-10-31',3429);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$200,001 to $250,000','', '2019-10-01', '2019-10-31',3641);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$250,001 to $350,000','', '2019-10-01', '2019-10-31',4365);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$350,001 to $500,000','', '2019-10-01', '2019-10-31',4680);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', 'Over $500,000','', '2019-10-01', '2019-10-31',5198);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', 'Up to $25,000','', '2019-11-01', '2019-11-30',924);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$25,001 to $50,000','', '2019-11-01', '2019-11-30',1524);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$50,001 to $100,000','', '2019-11-01', '2019-11-30',1940);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$100,001 to $150,000','', '2019-11-01', '2019-11-30',2356);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$150,001 to $200,000','', '2019-11-01', '2019-11-30',3048);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$200,001 to $250,000','', '2019-11-01', '2019-11-30',3236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$250,001 to $350,000','', '2019-11-01', '2019-11-30',3880);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$350,001 to $500,000','', '2019-11-01', '2019-11-30',4160);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', 'Over $500,000','', '2019-11-01', '2019-11-30',4620);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', 'Up to $25,000','', '2019-12-01', '2019-12-31',809);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$25,001 to $50,000','', '2019-12-01', '2019-12-31',1334);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$50,001 to $100,000','', '2019-12-01', '2019-12-31',1698);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$100,001 to $150,000','', '2019-12-01', '2019-12-31',2062);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$150,001 to $200,000','', '2019-12-01', '2019-12-31',2667);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$200,001 to $250,000','', '2019-12-01', '2019-12-31',2832);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$250,001 to $350,000','', '2019-12-01', '2019-12-31',3395);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$350,001 to $500,000','', '2019-12-01', '2019-12-31',3640);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', 'Over $500,000','',  '2019-12-01', '2019-12-31',4043);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', 'Up to $25,000','',  '2020-01-01', '2020-01-31',693);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$25,001 to $50,000','',  '2020-01-01', '2020-01-31',1143);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$50,001 to $100,000','',  '2020-01-01', '2020-01-31',1455);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$100,001 to $150,000','',  '2020-01-01', '2020-01-31',1767);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$150,001 to $200,000','',  '2020-01-01', '2020-01-31',2286);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$200,001 to $250,000','',  '2020-01-01', '2020-01-31',2427);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$250,001 to $350,000','',  '2020-01-01', '2020-01-31',2910);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$350,001 to $500,000','',  '2020-01-01', '2020-01-31',3120);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', 'Over $500,000','',  '2020-01-01', '2020-01-31',3465);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', 'Up to $25,000','',  '2020-02-01', '2020-02-29',578);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$25,001 to $50,000','',  '2020-02-01', '2020-02-29',953);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$50,001 to $100,000','', '2020-02-01', '2020-02-29',1213);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$100,001 to $150,000','', '2020-02-01', '2020-02-29',1473);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$150,001 to $200,000','', '2020-02-01', '2020-02-29',1905);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$200,001 to $250,000','', '2020-02-01', '2020-02-29',2023);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$250,001 to $350,000','', '2020-02-01', '2020-02-29',2425);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$350,001 to $500,000','', '2020-02-01', '2020-02-29',2600);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', 'Over $500,000','', '2020-02-01', '2020-02-29',2888);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', 'Up to $25,000','', '2020-03-01', '2020-03-31',462);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$25,001 to $50,000','', '2020-03-01', '2020-03-31',762);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$50,001 to $100,000','', '2020-03-01', '2020-03-31',970);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$100,001 to $150,000','', '2020-03-01', '2020-03-31',1178);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$150,001 to $200,000', '', '2020-03-01', '2020-03-31',1524);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$200,001 to $250,000','', '2020-03-01', '2020-03-31',1618);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$250,001 to $350,000','', '2020-03-01', '2020-03-31',1940);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$350,001 to $500,000','', '2020-03-01', '2020-03-31',2080);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', 'Over $500,000','', '2020-03-01', '2020-03-31',2310);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', 'Up to $25,000','', '2020-04-01', '2020-04-30',347);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$25,001 to $50,000','',  '2020-04-01', '2020-04-30',572);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$50,001 to $100,000','', '2020-04-01', '2020-04-30',728);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$100,001 to $150,000','', '2020-04-01', '2020-04-30',884);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$150,001 to $200,000', '', '2020-04-01', '2020-04-30',1143);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$200,001 to $250,000','', '2020-04-01', '2020-04-30',1214);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$250,001 to $350,000','', '2020-04-01', '2020-04-30',1455);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$350,001 to $500,000','', '2020-04-01', '2020-04-30',1560);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', 'Over $500,000','', '2020-04-01', '2020-04-30',1733);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', 'Up to $25,000','', '2020-05-01', '2020-05-31',231);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$25,001 to $50,000','', '2020-05-01', '2020-05-31',381);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$50,001 to $100,000','', '2020-05-01', '2020-05-31',485);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$100,001 to $150,000','', '2020-05-01', '2020-05-31',589);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$150,001 to $200,000', '', '2020-05-01', '2020-05-31',762);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$200,001 to $250,000','', '2020-05-01', '2020-05-31',809);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$250,001 to $350,000','', '2020-05-01', '2020-05-31',970);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$350,001 to $500,000','', '2020-05-01', '2020-05-31',1040);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', 'Over $500,000','', '2020-05-01', '2020-05-31',1155);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', 'Up to $25,000','', '2020-06-01', '2020-06-30',116);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$25,001 to $50,000','', '2020-06-01', '2020-06-30',191);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$50,001 to $100,000','', '2020-06-01', '2020-06-30',243);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$100,001 to $150,000','', '2020-06-01', '2020-06-30',295);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$150,001 to $200,000', '', '2020-06-01', '2020-06-30',381);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$200,001 to $250,000','', '2020-06-01', '2020-06-30',405);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$250,001 to $350,000','', '2020-06-01', '2020-06-30',485);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', '$350,001 to $500,000','', '2020-06-01', '2020-06-30',520);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Group Professional Liability', 'Over $500,000','', '2020-06-01', '2020-06-30',578);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`type`) VALUES ('Group Professional Liability', '1M XS', '', '2019-06-30', '2020-06-30',0.8,'PERCENT');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`type`) VALUES ('Group Professional Liability', '2M XS', '', '2019-06-30', '2020-06-30',1.55,'PERCENT');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`type`) VALUES ('Group Professional Liability', '3M XS', '', '2019-06-30', '2020-06-30',2,'PERCENT');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`type`) VALUES ('Group Professional Liability', '4M XS', '', '2019-06-30', '2020-06-30',2.5,'PERCENT');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`type`) VALUES ('Group Professional Liability', '9M XS', '', '2019-06-30', '2020-06-30',4.35,'PERCENT');

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan5','hull25000LessThan5','2019-06-30', '2020-07-31',3.12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan5','hull50000LessThan5','2019-06-30', '2020-07-31',1.87);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan5','hull100000LessThan5','2019-06-30', '2020-07-31',1.73);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan5','hull150000LessThan5','2019-06-30', '2020-07-31',1.51);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan5','hull200000LessThan5','2019-06-30', '2020-07-31',1.43);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan5','hull250000LessThan5','2019-06-30', '2020-07-31',1.16);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan5','hull300000LessThan5','2019-06-30', '2020-07-31',1.02);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan5','hull350000LessThan5','2019-06-30', '2020-07-31',0.98);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan5','hull400000LessThan5','2019-06-30', '2020-07-31',0.89);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan5','hull500000LessThan5','2019-06-30', '2020-07-31',0.84);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan5','hull600000LessThan5','2019-06-30', '2020-07-31',0.75);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan5','hull25000LessThan5','2019-08-01', '2020-07-31',3.12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan5','hull50000LessThan5','2019-08-01', '2020-07-31',1.87);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan5','hull100000LessThan5','2019-08-01', '2020-07-31',1.73);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan5','hull150000LessThan5','2019-08-01', '2020-07-31',1.51);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan5','hull200000LessThan5','2019-08-01', '2020-07-31',1.43);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan5','hull250000LessThan5','2019-08-01', '2020-07-31',1.16);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan5','hull300000LessThan5','2019-08-01', '2020-07-31',1.02);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan5','hull350000LessThan5','2019-08-01', '2020-07-31',0.98);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan5','hull400000LessThan5','2019-08-01', '2020-07-31',0.89);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan5','hull500000LessThan5','2019-08-01', '2020-07-31',0.84);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan5','hull600000LessThan5','2019-08-01', '2020-07-31',0.75);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan5','hull25000LessThan5','2019-09-01', '2020-07-31',3.12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan5','hull50000LessThan5','2019-09-01', '2020-07-31',1.87);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan5','hull100000LessThan5','2019-09-01', '2020-07-31',1.73);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan5','hull150000LessThan5','2019-09-01', '2020-07-31',1.51);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan5','hull200000LessThan5','2019-09-01', '2020-07-31',1.43);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan5','hull250000LessThan5','2019-09-01', '2020-07-31',1.16);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan5','hull300000LessThan5','2019-09-01', '2020-07-31',1.02);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan5','hull350000LessThan5','2019-09-01', '2020-07-31',0.98);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan5','hull400000LessThan5','2019-09-01', '2020-07-31',0.89);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan5','hull500000LessThan5','2019-09-01', '2020-07-31',0.84);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan5','hull600000LessThan5','2019-09-01', '2020-07-31',0.75);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan5','hull25000LessThan5','2019-10-01', '2020-07-31',3.12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan5','hull50000LessThan5','2019-10-01', '2020-07-31',1.87);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan5','hull100000LessThan5','2019-10-01', '2020-07-31',1.73);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan5','hull150000LessThan5','2019-10-01', '2020-07-31',1.51);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan5','hull200000LessThan5','2019-10-01', '2020-07-31',1.43);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan5','hull250000LessThan5','2019-10-01', '2020-07-31',1.16);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan5','hull300000LessThan5','2019-10-01', '2020-07-31',1.02);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan5','hull350000LessThan5','2019-10-01', '2020-07-31',0.98);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan5','hull400000LessThan5','2019-10-01', '2020-07-31',0.89);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan5','hull500000LessThan5','2019-10-01', '2020-07-31',0.84);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan5','hull600000LessThan5','2019-10-01', '2020-07-31',0.75);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan5','hull25000LessThan5','2019-11-01', '2020-07-31',3.12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan5','hull50000LessThan5','2019-11-01', '2020-07-31',1.87);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan5','hull100000LessThan5','2019-11-01', '2020-07-31',1.73);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan5','hull150000LessThan5','2019-11-01', '2020-07-31',1.51);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan5','hull200000LessThan5','2019-11-01', '2020-07-31',1.43);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan5','hull250000LessThan5','2019-11-01', '2020-07-31',1.16);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan5','hull300000LessThan5','2019-11-01', '2020-07-31',1.02);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan5','hull350000LessThan5','2019-11-01', '2020-07-31',0.98);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan5','hull400000LessThan5','2019-11-01', '2020-07-31',0.89);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan5','hull500000LessThan5','2019-11-01', '2020-07-31',0.84);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan5','hull600000LessThan5','2019-11-01', '2020-07-31',0.75);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan5','hull25000LessThan5','2019-12-01', '2020-07-31',3.12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan5','hull50000LessThan5','2019-12-01', '2020-07-31',1.87);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan5','hull100000LessThan5','2019-12-01', '2020-07-31',1.73);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan5','hull150000LessThan5','2019-12-01', '2020-07-31',1.51);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan5','hull200000LessThan5','2019-12-01', '2020-07-31',1.43);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan5','hull250000LessThan5','2019-12-01', '2020-07-31',1.16);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan5','hull300000LessThan5','2019-12-01', '2020-07-31',1.02);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan5','hull350000LessThan5','2019-12-01', '2020-07-31',0.98);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan5','hull400000LessThan5','2019-12-01', '2020-07-31',0.89);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan5','hull500000LessThan5','2019-12-01', '2020-07-31',0.84);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan5','hull600000LessThan5','2019-12-01', '2020-07-31',0.75);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan5','hull25000LessThan5','2020-01-01', '2020-07-31',3.12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan5','hull50000LessThan5','2020-01-01', '2020-07-31',1.87);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan5','hull100000LessThan5','2020-01-01', '2020-07-31',1.73);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan5','hull150000LessThan5','2020-01-01', '2020-07-31',1.51);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan5','hull200000LessThan5','2020-01-01', '2020-07-31',1.43);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan5','hull250000LessThan5','2020-01-01', '2020-07-31',1.16);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan5','hull300000LessThan5','2020-01-01', '2020-07-31',1.02);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan5','hull350000LessThan5','2020-01-01', '2020-07-31',0.98);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan5','hull400000LessThan5','2020-01-01', '2020-07-31',0.89);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan5','hull500000LessThan5','2020-01-01', '2020-07-31',0.84);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan5','hull600000LessThan5','2020-01-01', '2020-07-31',0.75);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan5','hull25000LessThan5','2020-02-01', '2020-07-31',3.12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan5','hull50000LessThan5','2020-02-01', '2020-07-31',1.87);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan5','hull100000LessThan5','2020-02-01', '2020-07-31',1.73);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan5','hull150000LessThan5','2020-02-01', '2020-07-31',1.51);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan5','hull200000LessThan5','2020-02-01', '2020-07-31',1.43);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan5','hull250000LessThan5','2020-02-01', '2020-07-31',1.16);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan5','hull300000LessThan5','2020-02-01', '2020-07-31',1.02);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan5','hull350000LessThan5','2020-02-01', '2020-07-31',0.98);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan5','hull400000LessThan5','2020-02-01', '2020-07-31',0.89);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan5','hull500000LessThan5','2020-02-01', '2020-07-31',0.84);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan5','hull600000LessThan5','2020-02-01', '2020-07-31',0.75);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan5','hull25000LessThan5','2020-03-01', '2020-07-31',3.12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan5','hull50000LessThan5','2020-03-01', '2020-07-31',1.87);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan5','hull100000LessThan5','2020-03-01', '2020-07-31',1.73);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan5','hull150000LessThan5','2020-03-01', '2020-07-31',1.51);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan5','hull200000LessThan5','2020-03-01', '2020-07-31',1.43);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan5','hull250000LessThan5','2020-03-01', '2020-07-31',1.16);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan5','hull300000LessThan5','2020-03-01', '2020-07-31',1.02);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan5','hull350000LessThan5','2020-03-01', '2020-07-31',0.98);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan5','hull400000LessThan5','2020-03-01', '2020-07-31',0.89);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan5','hull500000LessThan5','2020-03-01', '2020-07-31',0.84);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan5','hull600000LessThan5','2020-03-01', '2020-07-31',0.75);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan5','hull25000LessThan5','2020-04-01', '2020-07-31',3.12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan5','hull50000LessThan5','2020-04-01', '2020-07-31',1.87);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan5','hull100000LessThan5','2020-04-01', '2020-07-31',1.73);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan5','hull150000LessThan5','2020-04-01', '2020-07-31',1.51);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan5','hull200000LessThan5','2020-04-01', '2020-07-31',1.43);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan5','hull250000LessThan5','2020-04-01', '2020-07-31',1.16);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan5','hull300000LessThan5','2020-04-01', '2020-07-31',1.02);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan5','hull350000LessThan5','2020-04-01', '2020-07-31',0.98);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan5','hull400000LessThan5','2020-04-01', '2020-07-31',0.89);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan5','hull500000LessThan5','2020-04-01', '2020-07-31',0.84);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan5','hull600000LessThan5','2020-04-01', '2020-07-31',0.75);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan5','hull25000LessThan5','2020-05-01', '2020-07-31',3.12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan5','hull50000LessThan5','2020-05-01', '2020-07-31',1.87);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan5','hull100000LessThan5','2020-05-01', '2020-07-31',1.73);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan5','hull150000LessThan5','2020-05-01', '2020-07-31',1.51);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan5','hull200000LessThan5','2020-05-01', '2020-07-31',1.43);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan5','hull250000LessThan5','2020-05-01', '2020-07-31',1.16);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan5','hull300000LessThan5','2020-05-01', '2020-07-31',1.02);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan5','hull350000LessThan5','2020-05-01', '2020-07-31',0.98);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan5','hull400000LessThan5','2020-05-01', '2020-07-31',0.89);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan5','hull500000LessThan5','2020-05-01', '2020-07-31',0.84);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan5','hull600000LessThan5','2020-05-01', '2020-07-31',0.75);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan5','hull25000LessThan5','2020-06-01', '2020-07-31',3.12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan5','hull50000LessThan5','2020-06-01', '2020-07-31',1.87);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan5','hull100000LessThan5','2020-06-01', '2020-07-31',1.73);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan5','hull150000LessThan5','2020-06-01', '2020-07-31',1.51);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan5','hull200000LessThan5','2020-06-01', '2020-07-31',1.43);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan5','hull250000LessThan5','2020-06-01', '2020-07-31',1.16);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan5','hull300000LessThan5','2020-06-01', '2020-07-31',1.02);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan5','hull350000LessThan5','2020-06-01', '2020-07-31',0.98);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan5','hull400000LessThan5','2020-06-01', '2020-07-31',0.89);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan5','hull500000LessThan5','2020-06-01', '2020-07-31',0.84);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan5','hull600000LessThan5','2020-06-01', '2020-07-31',0.75);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan5','hull25000LessThan5','2020-07-01', '2020-07-31',3.12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan5','hull50000LessThan5','2020-07-01', '2020-07-31',1.87);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan5','hull100000LessThan5','2020-07-01', '2020-07-31',1.73);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan5','hull150000LessThan5','2020-07-01', '2020-07-31',1.51);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan5','hull200000LessThan5','2020-07-01', '2020-07-31',1.43);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan5','hull250000LessThan5','2020-07-01', '2020-07-31',1.16);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan5','hull300000LessThan5','2020-07-01', '2020-07-31',1.02);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan5','hull350000LessThan5','2020-07-01', '2020-07-31',0.98);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan5','hull400000LessThan5','2020-07-01', '2020-07-31',0.89);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan5','hull500000LessThan5','2020-07-01', '2020-07-31',0.84);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan5','hull600000LessThan5','2020-07-01', '2020-07-31',0.75);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan11','hull25000LessThan11','2019-06-30', '2020-07-31',3.67);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan11','hull50000LessThan11','2019-06-30', '2020-07-31',2.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan11','hull100000LessThan11','2019-06-30', '2020-07-31',2.04);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan11','hull150000LessThan11','2019-06-30', '2020-07-31',1.78);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan11','hull200000LessThan11','2019-06-30', '2020-07-31',1.68);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan11','hull250000LessThan11','2019-06-30', '2020-07-31',1.36);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan11','hull300000LessThan11','2019-06-30', '2020-07-31',1.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan11','hull350000LessThan11','2019-06-30', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan11','hull400000LessThan11','2019-06-30', '2020-07-31',1.05);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan11','hull500000LessThan11','2019-06-30', '2020-07-31',0.99);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan11','hull600000LessThan11','2019-06-30', '2020-07-31',0.89);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan11','hull25000LessThan11','2019-07-01', '2020-07-31',3.67);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan11','hull50000LessThan11','2019-07-01', '2020-07-31',2.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan11','hull100000LessThan11','2019-07-01', '2020-07-31',2.04);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan11','hull150000LessThan11','2019-07-01', '2020-07-31',1.78);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan11','hull200000LessThan11','2019-07-01', '2020-07-31',1.68);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan11','hull250000LessThan11','2019-07-01', '2020-07-31',1.36);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan11','hull300000LessThan11','2019-07-01', '2020-07-31',1.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan11','hull350000LessThan11','2019-07-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan11','hull400000LessThan11','2019-07-01', '2020-07-31',1.05);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan11','hull500000LessThan11','2019-07-01', '2020-07-31',0.99);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan11','hull600000LessThan11','2019-07-01', '2020-07-31',0.89);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan11','hull25000LessThan11','2019-08-01', '2020-07-31',3.67);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan11','hull50000LessThan11','2019-08-01', '2020-07-31',2.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan11','hull100000LessThan11','2019-08-01', '2020-07-31',2.04);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan11','hull150000LessThan11','2019-08-01', '2020-07-31',1.78);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan11','hull200000LessThan11','2019-08-01', '2020-07-31',1.68);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan11','hull250000LessThan11','2019-08-01', '2020-07-31',1.36);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan11','hull300000LessThan11','2019-08-01', '2020-07-31',1.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan11','hull350000LessThan11','2019-08-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan11','hull400000LessThan11','2019-08-01', '2020-07-31',1.05);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan11','hull500000LessThan11','2019-08-01', '2020-07-31',0.99);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan11','hull600000LessThan11','2019-08-01', '2020-07-31',0.89);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan11','hull25000LessThan11','2019-09-01', '2020-07-31',3.67);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan11','hull50000LessThan11','2019-09-01', '2020-07-31',2.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan11','hull100000LessThan11','2019-09-01', '2020-07-31',2.04);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan11','hull150000LessThan11','2019-09-01', '2020-07-31',1.78);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan11','hull200000LessThan11','2019-09-01', '2020-07-31',1.68);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan11','hull250000LessThan11','2019-09-01', '2020-07-31',1.36);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan11','hull300000LessThan11','2019-09-01', '2020-07-31',1.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan11','hull350000LessThan11','2019-09-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan11','hull400000LessThan11','2019-09-01', '2020-07-31',1.05);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan11','hull500000LessThan11','2019-09-01', '2020-07-31',0.99);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan11','hull600000LessThan11','2019-09-01', '2020-07-31',0.89);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan11','hull25000LessThan11','2019-10-01', '2020-07-31',3.67);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan11','hull50000LessThan11','2019-10-01', '2020-07-31',2.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan11','hull100000LessThan11','2019-10-01', '2020-07-31',2.04);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan11','hull150000LessThan11','2019-10-01', '2020-07-31',1.78);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan11','hull200000LessThan11','2019-10-01', '2020-07-31',1.68);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan11','hull250000LessThan11','2019-10-01', '2020-07-31',1.36);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan11','hull300000LessThan11','2019-10-01', '2020-07-31',1.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan11','hull350000LessThan11','2019-10-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan11','hull400000LessThan11','2019-10-01', '2020-07-31',1.05);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan11','hull500000LessThan11','2019-10-01', '2020-07-31',0.99);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan11','hull600000LessThan11','2019-10-01', '2020-07-31',0.89);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan11','hull25000LessThan11','2019-11-01', '2020-07-31',3.67);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan11','hull50000LessThan11','2019-11-01', '2020-07-31',2.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan11','hull100000LessThan11','2019-11-01', '2020-07-31',2.04);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan11','hull150000LessThan11','2019-11-01', '2020-07-31',1.78);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan11','hull200000LessThan11','2019-11-01', '2020-07-31',1.68);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan11','hull250000LessThan11','2019-11-01', '2020-07-31',1.36);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan11','hull300000LessThan11','2019-11-01', '2020-07-31',1.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan11','hull350000LessThan11','2019-11-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan11','hull400000LessThan11','2019-11-01', '2020-07-31',1.05);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan11','hull500000LessThan11','2019-11-01', '2020-07-31',0.99);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan11','hull600000LessThan11','2019-11-01', '2020-07-31',0.89);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan11','hull25000LessThan11','2019-12-01', '2020-07-31',3.67);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan11','hull50000LessThan11','2019-12-01', '2020-07-31',2.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan11','hull100000LessThan11','2019-12-01', '2020-07-31',2.04);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan11','hull150000LessThan11','2019-12-01', '2020-07-31',1.78);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan11','hull200000LessThan11','2019-12-01', '2020-07-31',1.68);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan11','hull250000LessThan11','2019-12-01', '2020-07-31',1.36);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan11','hull300000LessThan11','2019-12-01', '2020-07-31',1.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan11','hull350000LessThan11','2019-12-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan11','hull400000LessThan11','2019-12-01', '2020-07-31',1.05);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan11','hull500000LessThan11','2019-12-01', '2020-07-31',0.99);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan11','hull600000LessThan11','2019-12-01', '2020-07-31',0.89);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan11','hull25000LessThan11','2020-01-01', '2020-07-31',3.67);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan11','hull50000LessThan11','2020-01-01', '2020-07-31',2.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan11','hull100000LessThan11','2020-01-01', '2020-07-31',2.04);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan11','hull150000LessThan11','2020-01-01', '2020-07-31',1.78);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan11','hull200000LessThan11','2020-01-01', '2020-07-31',1.68);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan11','hull250000LessThan11','2020-01-01', '2020-07-31',1.36);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan11','hull300000LessThan11','2020-01-01', '2020-07-31',1.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan11','hull350000LessThan11','2020-01-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan11','hull400000LessThan11','2020-01-01', '2020-07-31',1.05);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan11','hull500000LessThan11','2020-01-01', '2020-07-31',0.99);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan11','hull600000LessThan11','2020-01-01', '2020-07-31',0.89);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan11','hull25000LessThan11','2020-02-01', '2020-07-31',3.67);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan11','hull50000LessThan11','2020-02-01', '2020-07-31',2.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan11','hull100000LessThan11','2020-02-01', '2020-07-31',2.04);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan11','hull150000LessThan11','2020-02-01', '2020-07-31',1.78);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan11','hull200000LessThan11','2020-02-01', '2020-07-31',1.68);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan11','hull250000LessThan11','2020-02-01', '2020-07-31',1.36);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan11','hull300000LessThan11','2020-02-01', '2020-07-31',1.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan11','hull350000LessThan11','2020-02-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan11','hull400000LessThan11','2020-02-01', '2020-07-31',1.05);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan11','hull500000LessThan11','2020-02-01', '2020-07-31',0.99);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan11','hull600000LessThan11','2020-02-01', '2020-07-31',0.89);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan11','hull25000LessThan11','2019-03-01', '2020-07-31',3.67);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan11','hull50000LessThan11','2019-03-01', '2020-07-31',2.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan11','hull100000LessThan11','2019-03-01', '2020-07-31',2.04);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan11','hull150000LessThan11','2019-03-01', '2020-07-31',1.78);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan11','hull200000LessThan11','2019-03-01', '2020-07-31',1.68);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan11','hull250000LessThan11','2019-03-01', '2020-07-31',1.36);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan11','hull300000LessThan11','2019-03-01', '2020-07-31',1.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan11','hull350000LessThan11','2019-03-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan11','hull400000LessThan11','2019-03-01', '2020-07-31',1.05);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan11','hull500000LessThan11','2019-03-01', '2020-07-31',0.99);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan11','hull600000LessThan11','2019-03-01', '2020-07-31',0.89);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan11','hull25000LessThan11','2019-04-01', '2020-07-31',3.67);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan11','hull50000LessThan11','2019-04-01', '2020-07-31',2.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan11','hull100000LessThan11','2019-04-01', '2020-07-31',2.04);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan11','hull150000LessThan11','2019-04-01', '2020-07-31',1.78);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan11','hull200000LessThan11','2019-04-01', '2020-07-31',1.68);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan11','hull250000LessThan11','2019-04-01', '2020-07-31',1.36);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan11','hull300000LessThan11','2019-04-01', '2020-07-31',1.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan11','hull350000LessThan11','2019-04-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan11','hull400000LessThan11','2019-04-01', '2020-07-31',1.05);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan11','hull500000LessThan11','2019-04-01', '2020-07-31',0.99);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan11','hull600000LessThan11','2019-04-01', '2020-07-31',0.89);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan11','hull25000LessThan11','2019-05-01', '2020-07-31',3.67);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan11','hull50000LessThan11','2019-05-01', '2020-07-31',2.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan11','hull100000LessThan11','2019-05-01', '2020-07-31',2.04);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan11','hull150000LessThan11','2019-05-01', '2020-07-31',1.78);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan11','hull200000LessThan11','2019-05-01', '2020-07-31',1.68);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan11','hull250000LessThan11','2019-05-01', '2020-07-31',1.36);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan11','hull300000LessThan11','2019-05-01', '2020-07-31',1.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan11','hull350000LessThan11','2019-05-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan11','hull400000LessThan11','2019-05-01', '2020-07-31',1.05);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan11','hull500000LessThan11','2019-05-01', '2020-07-31',0.99);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan11','hull600000LessThan11','2019-05-01', '2020-07-31',0.89);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan11','hull25000LessThan11','2019-06-01', '2020-07-31',3.67);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan11','hull50000LessThan11','2019-06-01', '2020-07-31',2.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan11','hull100000LessThan11','2019-06-01', '2020-07-31',2.04);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan11','hull150000LessThan11','2019-06-01', '2020-07-31',1.78);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan11','hull200000LessThan11','2019-06-01', '2020-07-31',1.68);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan11','hull250000LessThan11','2019-06-01', '2020-07-31',1.36);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan11','hull300000LessThan11','2019-06-01', '2020-07-31',1.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan11','hull350000LessThan11','2019-06-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan11','hull400000LessThan11','2019-06-01', '2020-07-31',1.05);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan11','hull500000LessThan11','2019-06-01', '2020-07-31',0.99);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan11','hull600000LessThan11','2019-06-01', '2020-07-31',0.89);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan11','hull25000LessThan11','2019-07-01', '2020-07-31',3.67);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan11','hull50000LessThan11','2019-07-01', '2020-07-31',2.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan11','hull100000LessThan11','2019-07-01', '2020-07-31',2.04);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan11','hull150000LessThan11','2019-07-01', '2020-07-31',1.78);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan11','hull200000LessThan11','2019-07-01', '2020-07-31',1.68);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan11','hull250000LessThan11','2019-07-01', '2020-07-31',1.36);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan11','hull300000LessThan11','2019-07-01', '2020-07-31',1.2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan11','hull350000LessThan11','2019-07-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan11','hull400000LessThan11','2019-07-01', '2020-07-31',1.05);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan11','hull500000LessThan11','2019-07-01', '2020-07-31',0.99);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan11','hull600000LessThan11','2019-07-01', '2020-07-31',0.89);





INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan25','hull25000LessThan25','2019-07-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan25','hull50000LessThan25','2019-07-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan25','hull100000LessThan25','2019-07-01', '2020-07-31',2.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan25','hull150000LessThan25','2019-07-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan25','hull200000LessThan25','2019-07-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan25','hull250000LessThan25','2019-07-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan25','hull300000LessThan25','2019-07-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan25','hull350000LessThan25','2019-07-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan25','hull400000LessThan25','2019-07-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan25','hull500000LessThan25','2019-07-01', '2020-07-31',1.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan25','hull600000LessThan25','2019-07-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan25','hull25000LessThan25','2019-08-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan25','hull50000LessThan25','2019-08-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan25','hull100000LessThan25','2019-08-01', '2020-07-31',2.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan25','hull150000LessThan25','2019-08-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan25','hull200000LessThan25','2019-08-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan25','hull250000LessThan25','2019-08-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan25','hull300000LessThan25','2019-08-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan25','hull350000LessThan25','2019-08-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan25','hull400000LessThan25','2019-08-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan25','hull500000LessThan25','2019-08-01', '2020-07-31',1.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan25','hull600000LessThan25','2019-08-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan25','hull25000LessThan25','2019-09-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan25','hull50000LessThan25','2019-09-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan25','hull100000LessThan25','2019-09-01', '2020-07-31',2.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan25','hull150000LessThan25','2019-09-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan25','hull200000LessThan25','2019-09-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan25','hull250000LessThan25','2019-09-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan25','hull300000LessThan25','2019-09-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan25','hull350000LessThan25','2019-09-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan25','hull400000LessThan25','2019-09-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan25','hull500000LessThan25','2019-09-01', '2020-07-31',1.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan25','hull600000LessThan25','2019-09-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan25','hull25000LessThan25','2019-10-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan25','hull50000LessThan25','2019-10-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan25','hull100000LessThan25','2019-10-01', '2020-07-31',2.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan25','hull150000LessThan25','2019-10-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan25','hull200000LessThan25','2019-10-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan25','hull250000LessThan25','2019-10-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan25','hull300000LessThan25','2019-10-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan25','hull350000LessThan25','2019-10-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan25','hull400000LessThan25','2019-10-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan25','hull500000LessThan25','2019-10-01', '2020-07-31',1.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan25','hull600000LessThan25','2019-10-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan25','hull25000LessThan25','2019-11-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan25','hull50000LessThan25','2019-11-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan25','hull100000LessThan25','2019-11-01', '2020-07-31',2.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan25','hull150000LessThan25','2019-11-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan25','hull200000LessThan25','2019-11-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan25','hull250000LessThan25','2019-11-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan25','hull300000LessThan25','2019-11-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan25','hull350000LessThan25','2019-11-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan25','hull400000LessThan25','2019-11-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan25','hull500000LessThan25','2019-11-01', '2020-07-31',1.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan25','hull600000LessThan25','2019-11-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan25','hull25000LessThan25','2019-12-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan25','hull50000LessThan25','2019-12-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan25','hull100000LessThan25','2019-12-01', '2020-07-31',2.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan25','hull150000LessThan25','2019-12-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan25','hull200000LessThan25','2019-12-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan25','hull250000LessThan25','2019-12-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan25','hull300000LessThan25','2019-12-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan25','hull350000LessThan25','2019-12-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan25','hull400000LessThan25','2019-12-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan25','hull500000LessThan25','2019-12-01', '2020-07-31',1.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan25','hull600000LessThan25','2019-12-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan25','hull25000LessThan25','2020-01-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan25','hull50000LessThan25','2020-01-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan25','hull100000LessThan25','2020-01-01', '2020-07-31',2.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan25','hull150000LessThan25','2020-01-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan25','hull200000LessThan25','2020-01-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan25','hull250000LessThan25','2020-01-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan25','hull300000LessThan25','2020-01-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan25','hull350000LessThan25','2020-01-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan25','hull400000LessThan25','2020-01-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan25','hull500000LessThan25','2020-01-01', '2020-07-31',1.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan25','hull600000LessThan25','2020-01-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan25','hull25000LessThan25','2020-02-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan25','hull50000LessThan25','2020-02-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan25','hull100000LessThan25','2020-02-01', '2020-07-31',2.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan25','hull150000LessThan25','2020-02-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan25','hull200000LessThan25','2020-02-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan25','hull250000LessThan25','2020-02-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan25','hull300000LessThan25','2020-02-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan25','hull350000LessThan25','2020-02-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan25','hull400000LessThan25','2020-02-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan25','hull500000LessThan25','2020-02-01', '2020-07-31',1.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan25','hull600000LessThan25','2020-02-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan25','hull25000LessThan25','2020-03-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan25','hull50000LessThan25','2020-03-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan25','hull100000LessThan25','2020-03-01', '2020-07-31',2.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan25','hull150000LessThan25','2020-03-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan25','hull200000LessThan25','2020-03-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan25','hull250000LessThan25','2020-03-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan25','hull300000LessThan25','2020-03-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan25','hull350000LessThan25','2020-03-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan25','hull400000LessThan25','2020-03-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan25','hull500000LessThan25','2020-03-01', '2020-07-31',1.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan25','hull600000LessThan25','2020-03-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan25','hull25000LessThan25','2020-04-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan25','hull50000LessThan25','2020-04-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan25','hull100000LessThan25','2020-04-01', '2020-07-31',2.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan25','hull150000LessThan25','2020-04-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan25','hull200000LessThan25','2020-04-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan25','hull250000LessThan25','2020-04-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan25','hull300000LessThan25','2020-04-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan25','hull350000LessThan25','2020-04-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan25','hull400000LessThan25','2020-04-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan25','hull500000LessThan25','2020-04-01', '2020-07-31',1.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan25','hull600000LessThan25','2020-04-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan25','hull25000LessThan25','2020-05-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan25','hull50000LessThan25','2020-05-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan25','hull100000LessThan25','2020-05-01', '2020-07-31',2.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan25','hull150000LessThan25','2020-05-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan25','hull200000LessThan25','2020-05-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan25','hull250000LessThan25','2020-05-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan25','hull300000LessThan25','2020-05-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan25','hull350000LessThan25','2020-05-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan25','hull400000LessThan25','2020-05-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan25','hull500000LessThan25','2020-05-01', '2020-07-31',1.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan25','hull600000LessThan25','2020-05-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan25','hull25000LessThan25','2020-06-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan25','hull50000LessThan25','2020-06-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan25','hull100000LessThan25','2020-06-01', '2020-07-31',2.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan25','hull150000LessThan25','2020-06-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan25','hull200000LessThan25','2020-06-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan25','hull250000LessThan25','2020-06-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan25','hull300000LessThan25','2020-06-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan25','hull350000LessThan25','2020-06-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan25','hull400000LessThan25','2020-06-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan25','hull500000LessThan25','2020-06-01', '2020-07-31',1.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan25','hull600000LessThan25','2020-06-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan25','hull25000LessThan25','2020-07-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan25','hull50000LessThan25','2020-07-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan25','hull100000LessThan25','2020-07-01', '2020-07-31',2.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan25','hull150000LessThan25','2020-07-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan25','hull200000LessThan25','2020-07-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan25','hull250000LessThan25','2020-07-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan25','hull300000LessThan25','2020-07-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan25','hull350000LessThan25','2020-07-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan25','hull400000LessThan25','2020-07-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan25','hull500000LessThan25','2020-07-01', '2020-07-31',1.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan25','hull600000LessThan25','2020-07-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000LessThan25','hull25000LessThan25','2020-07-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000LessThan25','hull50000LessThan25','2020-07-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000LessThan25','hull100000LessThan25','2020-07-01', '2020-07-31',2.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000LessThan25','hull150000LessThan25','2020-07-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000LessThan25','hull200000LessThan25','2020-07-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000LessThan25','hull250000LessThan25','2020-07-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000LessThan25','hull300000LessThan25','2020-07-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000LessThan25','hull350000LessThan25','2020-07-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000LessThan25','hull400000LessThan25','2020-07-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000LessThan25','hull500000LessThan25','2020-07-01', '2020-07-31',1.1);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000LessThan25','hull600000LessThan25','2020-07-01', '2020-07-31',0.99);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000GreaterThan25','hull25000GreaterThan25','2019-07-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000GreaterThan25','hull50000GreaterThan25','2019-07-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000GreaterThan25','hull100000GreaterThan25','2019-07-01', '2020-07-31',2.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000GreaterThan25','hull150000GreaterThan25','2019-07-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000GreaterThan25','hull200000GreaterThan25','2019-07-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000GreaterThan25','hull250000GreaterThan25','2019-07-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000GreaterThan25','hull300000GreaterThan25','2019-07-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000GreaterThan25','hull350000GreaterThan25','2019-07-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000GreaterThan25','hull400000GreaterThan25','2019-07-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000GreaterThan25','hull500000GreaterThan25','2019-07-01', '2020-07-31',1.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000GreaterThan25','hull600000GreaterThan25','2019-07-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000GreaterThan25','hull25000GreaterThan25','2019-08-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000GreaterThan25','hull50000GreaterThan25','2019-08-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000GreaterThan25','hull100000GreaterThan25','2019-08-01', '2020-07-31',2.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000GreaterThan25','hull150000GreaterThan25','2019-08-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000GreaterThan25','hull200000GreaterThan25','2019-08-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000GreaterThan25','hull250000GreaterThan25','2019-08-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000GreaterThan25','hull300000GreaterThan25','2019-08-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000GreaterThan25','hull350000GreaterThan25','2019-08-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000GreaterThan25','hull400000GreaterThan25','2019-08-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000GreaterThan25','hull500000GreaterThan25','2019-08-01', '2020-07-31',1.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000GreaterThan25','hull600000GreaterThan25','2019-08-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000GreaterThan25','hull25000GreaterThan25','2019-09-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000GreaterThan25','hull50000GreaterThan25','2019-09-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000GreaterThan25','hull100000GreaterThan25','2019-09-01', '2020-07-31',2.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000GreaterThan25','hull150000GreaterThan25','2019-09-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000GreaterThan25','hull200000GreaterThan25','2019-09-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000GreaterThan25','hull250000GreaterThan25','2019-09-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000GreaterThan25','hull300000GreaterThan25','2019-09-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000GreaterThan25','hull350000GreaterThan25','2019-09-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000GreaterThan25','hull400000GreaterThan25','2019-09-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000GreaterThan25','hull500000GreaterThan25','2019-09-01', '2020-07-31',1.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000GreaterThan25','hull600000GreaterThan25','2019-09-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000GreaterThan25','hull25000GreaterThan25','2019-10-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000GreaterThan25','hull50000GreaterThan25','2019-10-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000GreaterThan25','hull100000GreaterThan25','2019-10-01', '2020-07-31',2.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000GreaterThan25','hull150000GreaterThan25','2019-10-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000GreaterThan25','hull200000GreaterThan25','2019-10-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000GreaterThan25','hull250000GreaterThan25','2019-10-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000GreaterThan25','hull300000GreaterThan25','2019-10-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000GreaterThan25','hull350000GreaterThan25','2019-10-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000GreaterThan25','hull400000GreaterThan25','2019-10-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000GreaterThan25','hull500000GreaterThan25','2019-10-01', '2020-07-31',1.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000GreaterThan25','hull600000GreaterThan25','2019-10-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000GreaterThan25','hull25000GreaterThan25','2019-11-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000GreaterThan25','hull50000GreaterThan25','2019-11-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000GreaterThan25','hull100000GreaterThan25','2019-11-01', '2020-07-31',2.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000GreaterThan25','hull150000GreaterThan25','2019-11-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000GreaterThan25','hull200000GreaterThan25','2019-11-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000GreaterThan25','hull250000GreaterThan25','2019-11-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000GreaterThan25','hull300000GreaterThan25','2019-11-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000GreaterThan25','hull350000GreaterThan25','2019-11-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000GreaterThan25','hull400000GreaterThan25','2019-11-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000GreaterThan25','hull500000GreaterThan25','2019-11-01', '2020-07-31',1.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000GreaterThan25','hull600000GreaterThan25','2019-11-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000GreaterThan25','hull25000GreaterThan25','2019-12-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000GreaterThan25','hull50000GreaterThan25','2019-12-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000GreaterThan25','hull100000GreaterThan25','2019-12-01', '2020-07-31',2.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000GreaterThan25','hull150000GreaterThan25','2019-12-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000GreaterThan25','hull200000GreaterThan25','2019-12-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000GreaterThan25','hull250000GreaterThan25','2019-12-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000GreaterThan25','hull300000GreaterThan25','2019-12-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000GreaterThan25','hull350000GreaterThan25','2019-12-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000GreaterThan25','hull400000GreaterThan25','2019-12-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000GreaterThan25','hull500000GreaterThan25','2019-12-01', '2020-07-31',1.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000GreaterThan25','hull600000GreaterThan25','2019-12-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000GreaterThan25','hull25000GreaterThan25','2020-01-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000GreaterThan25','hull50000GreaterThan25','2020-01-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000GreaterThan25','hull100000GreaterThan25','2020-01-01', '2020-07-31',2.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000GreaterThan25','hull150000GreaterThan25','2020-01-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000GreaterThan25','hull200000GreaterThan25','2020-01-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000GreaterThan25','hull250000GreaterThan25','2020-01-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000GreaterThan25','hull300000GreaterThan25','2020-01-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000GreaterThan25','hull350000GreaterThan25','2020-01-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000GreaterThan25','hull400000GreaterThan25','2020-01-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000GreaterThan25','hull500000GreaterThan25','2020-01-01', '2020-07-31',1.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000GreaterThan25','hull600000GreaterThan25','2020-01-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000GreaterThan25','hull25000GreaterThan25','2020-02-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000GreaterThan25','hull50000GreaterThan25','2020-02-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000GreaterThan25','hull100000GreaterThan25','2020-02-01', '2020-07-31',2.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000GreaterThan25','hull150000GreaterThan25','2020-02-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000GreaterThan25','hull200000GreaterThan25','2020-02-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000GreaterThan25','hull250000GreaterThan25','2020-02-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000GreaterThan25','hull300000GreaterThan25','2020-02-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000GreaterThan25','hull350000GreaterThan25','2020-02-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000GreaterThan25','hull400000GreaterThan25','2020-02-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000GreaterThan25','hull500000GreaterThan25','2020-02-01', '2020-07-31',1.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000GreaterThan25','hull600000GreaterThan25','2020-02-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000GreaterThan25','hull25000GreaterThan25','2020-03-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000GreaterThan25','hull50000GreaterThan25','2020-03-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000GreaterThan25','hull100000GreaterThan25','2020-03-01', '2020-07-31',2.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000GreaterThan25','hull150000GreaterThan25','2020-03-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000GreaterThan25','hull200000GreaterThan25','2020-03-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000GreaterThan25','hull250000GreaterThan25','2020-03-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000GreaterThan25','hull300000GreaterThan25','2020-03-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000GreaterThan25','hull350000GreaterThan25','2020-03-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000GreaterThan25','hull400000GreaterThan25','2020-03-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000GreaterThan25','hull500000GreaterThan25','2020-03-01', '2020-07-31',1.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000GreaterThan25','hull600000GreaterThan25','2020-03-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000GreaterThan25','hull25000GreaterThan25','2020-04-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000GreaterThan25','hull50000GreaterThan25','2020-04-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000GreaterThan25','hull100000GreaterThan25','2020-04-01', '2020-07-31',2.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000GreaterThan25','hull150000GreaterThan25','2020-04-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000GreaterThan25','hull200000GreaterThan25','2020-04-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000GreaterThan25','hull250000GreaterThan25','2020-04-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000GreaterThan25','hull300000GreaterThan25','2020-04-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000GreaterThan25','hull350000GreaterThan25','2020-04-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000GreaterThan25','hull400000GreaterThan25','2020-04-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000GreaterThan25','hull500000GreaterThan25','2020-04-01', '2020-07-31',1.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000GreaterThan25','hull600000GreaterThan25','2020-04-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000GreaterThan25','hull25000GreaterThan25','2020-05-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000GreaterThan25','hull50000GreaterThan25','2020-05-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000GreaterThan25','hull100000GreaterThan25','2020-05-01', '2020-07-31',2.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000GreaterThan25','hull150000GreaterThan25','2020-05-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000GreaterThan25','hull200000GreaterThan25','2020-05-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000GreaterThan25','hull250000GreaterThan25','2020-05-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000GreaterThan25','hull300000GreaterThan25','2020-05-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000GreaterThan25','hull350000GreaterThan25','2020-05-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000GreaterThan25','hull400000GreaterThan25','2020-05-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000GreaterThan25','hull500000GreaterThan25','2020-05-01', '2020-07-31',1.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000GreaterThan25','hull600000GreaterThan25','2020-05-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000GreaterThan25','hull25000GreaterThan25','2020-06-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000GreaterThan25','hull50000GreaterThan25','2020-06-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000GreaterThan25','hull100000GreaterThan25','2020-06-01', '2020-07-31',2.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000GreaterThan25','hull150000GreaterThan25','2020-06-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000GreaterThan25','hull200000GreaterThan25','2020-06-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000GreaterThan25','hull250000GreaterThan25','2020-06-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000GreaterThan25','hull300000GreaterThan25','2020-06-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000GreaterThan25','hull350000GreaterThan25','2020-06-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000GreaterThan25','hull400000GreaterThan25','2020-06-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000GreaterThan25','hull500000GreaterThan25','2020-06-01', '2020-07-31',1.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000GreaterThan25','hull600000GreaterThan25','2020-06-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000GreaterThan25','hull25000GreaterThan25','2020-07-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000GreaterThan25','hull50000GreaterThan25','2020-07-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000GreaterThan25','hull100000GreaterThan25','2020-07-01', '2020-07-31',2.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000GreaterThan25','hull150000GreaterThan25','2020-07-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000GreaterThan25','hull200000GreaterThan25','2020-07-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000GreaterThan25','hull250000GreaterThan25','2020-07-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000GreaterThan25','hull300000GreaterThan25','2020-07-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000GreaterThan25','hull350000GreaterThan25','2020-07-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000GreaterThan25','hull400000GreaterThan25','2020-07-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000GreaterThan25','hull500000GreaterThan25','2020-07-01', '2020-07-31',1.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000GreaterThan25','hull600000GreaterThan25','2020-07-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull25000GreaterThan25','hull25000GreaterThan25','2020-07-01', '2020-07-31',4.72);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull50000GreaterThan25','hull50000GreaterThan25','2020-07-01', '2020-07-31',2.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull100000GreaterThan25','hull100000GreaterThan25','2020-07-01', '2020-07-31',2.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull150000GreaterThan25','hull150000GreaterThan25','2020-07-01', '2020-07-31',1.94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull200000GreaterThan25','hull200000GreaterThan25','2020-07-01', '2020-07-31',1.83);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull250000GreaterThan25','hull250000GreaterThan25','2020-07-01', '2020-07-31',1.57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull300000GreaterThan25','hull300000GreaterThan25','2020-07-01', '2020-07-31',1.41);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull350000GreaterThan25','hull350000GreaterThan25','2020-07-01', '2020-07-31',1.31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull400000GreaterThan25','hull400000GreaterThan25','2020-07-01', '2020-07-31',1.15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull500000GreaterThan25','hull500000LessThan25','2020-07-01', '2020-07-31',1.10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'hull600000GreaterThan25','hull600000LessThan25','2020-07-01', '2020-07-31',0.99);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'NavWaterSurcharge','NavWaterSurcharge','2019-07-01', '2020-07-31',50);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'NavWaterSurcharge','NavWaterSurcharge','2019-08-01', '2020-07-31',50);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'NavWaterSurcharge','NavWaterSurcharge','2019-09-01', '2020-07-31',50);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'NavWaterSurcharge','NavWaterSurcharge','2019-10-01', '2020-07-31',50);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'NavWaterSurcharge','NavWaterSurcharge','2019-11-01', '2020-07-31',50);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'NavWaterSurcharge','NavWaterSurcharge','2019-12-01', '2020-07-31',50);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'NavWaterSurcharge','NavWaterSurcharge','2020-01-01', '2020-07-31',50);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'NavWaterSurcharge','NavWaterSurcharge','2020-02-01', '2020-07-31',50);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'NavWaterSurcharge','NavWaterSurcharge','2020-03-01', '2020-07-31',50);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'NavWaterSurcharge','NavWaterSurcharge','2020-04-01', '2020-07-31',50);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'NavWaterSurcharge','NavWaterSurcharge','2020-05-01', '2020-07-31',50);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'NavWaterSurcharge','NavWaterSurcharge','2020-06-01', '2020-07-31',50);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'NavWaterSurcharge','NavWaterSurcharge','2020-07-01', '2020-07-31',50);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'NavWaterSurcharge','NavWaterSurcharge','2020-07-01', '2020-07-31',50);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInWater','CrewInWater','2019-07-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInWater','CrewInWater','2019-08-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInWater','CrewInWater','2019-09-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInWater','CrewInWater','2019-10-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInWater','CrewInWater','2019-11-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInWater','CrewInWater','2019-12-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInWater','CrewInWater','2020-01-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInWater','CrewInWater','2020-02-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInWater','CrewInWater','2020-03-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInWater','CrewInWater','2020-04-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInWater','CrewInWater','2020-05-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInWater','CrewInWater','2020-06-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInWater','CrewInWater','2020-07-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInWater','CrewInWater','2020-07-01', '2020-07-31',1050);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','LiabilityPremium1M','2019-07-01', '2020-07-31',950);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','LiabilityPremium1M','2019-08-01', '2020-07-31',950);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','LiabilityPremium1M','2019-09-01', '2020-07-31',950);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','LiabilityPremium1M','2019-10-01', '2020-07-31',950);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','LiabilityPremium1M','2019-11-01', '2020-07-31',950);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','LiabilityPremium1M','2019-12-01', '2020-07-31',950);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','LiabilityPremium1M','2020-01-01', '2020-07-31',950);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','LiabilityPremium1M','2020-02-01', '2020-07-31',950);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','LiabilityPremium1M','2020-03-01', '2020-07-31',950);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','LiabilityPremium1M','2020-04-01', '2020-07-31',950);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','LiabilityPremium1M','2020-05-01', '2020-07-31',950);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','LiabilityPremium1M','2020-06-01', '2020-07-31',950);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','LiabilityPremium1M','2020-07-01', '2020-07-31',950);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','LiabilityPremium1M','2020-07-01', '2020-07-31',950);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','DingyLiabilityPremium','2019-07-01', '2020-07-31',263);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','DingyLiabilityPremium','2019-08-01', '2020-07-31',263);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','DingyLiabilityPremium','2019-09-01', '2020-07-31',263);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','DingyLiabilityPremium','2019-10-01', '2020-07-31',263);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','DingyLiabilityPremium','2019-11-01', '2020-07-31',263);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','DingyLiabilityPremium','2019-12-01', '2020-07-31',263);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','DingyLiabilityPremium','2020-01-01', '2020-07-31',263);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','DingyLiabilityPremium','2020-02-01', '2020-07-31',263);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','DingyLiabilityPremium','2020-03-01', '2020-07-31',263);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','DingyLiabilityPremium','2020-04-01', '2020-07-31',263);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','DingyLiabilityPremium','2020-05-01', '2020-07-31',263);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','DingyLiabilityPremium','2020-06-01', '2020-07-31',263);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','DingyLiabilityPremium','2020-07-01', '2020-07-31',263);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Protection & Indemnity','DingyLiabilityPremium','2020-07-01', '2020-07-31',263);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInBoat','CrewInBoat','2019-07-01', '2020-07-31',895);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInBoat','CrewInBoat','2019-08-01', '2020-07-31',895);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInBoat','CrewInBoat','2019-09-01', '2020-07-31',895);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInBoat','CrewInBoat','2019-10-01', '2020-07-31',895);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInBoat','CrewInBoat','2019-11-01', '2020-07-31',895);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInBoat','CrewInBoat','2019-12-01', '2020-07-31',895);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInBoat','CrewInBoat','2020-01-01', '2020-07-31',895);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInBoat','CrewInBoat','2020-02-01', '2020-07-31',895);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInBoat','CrewInBoat','2020-03-01', '2020-07-31',895);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInBoat','CrewInBoat','2020-04-01', '2020-07-31',895);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInBoat','CrewInBoat','2020-05-01', '2020-07-31',895);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInBoat','CrewInBoat','2020-06-01', '2020-07-31',895);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInBoat','CrewInBoat','2020-07-01', '2020-07-31',895);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'CrewInBoat','CrewInBoat','2020-07-01', '2020-07-31',895);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'FL-HISurcharge','FL-HISurcharge','2019-07-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'FL-HISurcharge','FL-HISurcharge','2019-08-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'FL-HISurcharge','FL-HISurcharge','2019-09-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'FL-HISurcharge','FL-HISurcharge','2019-10-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'FL-HISurcharge','FL-HISurcharge','2019-11-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'FL-HISurcharge','FL-HISurcharge','2019-12-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'FL-HISurcharge','FL-HISurcharge','2020-01-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'FL-HISurcharge','FL-HISurcharge','2020-02-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'FL-HISurcharge','FL-HISurcharge','2020-03-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'FL-HISurcharge','FL-HISurcharge','2020-04-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'FL-HISurcharge','FL-HISurcharge','2020-05-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'FL-HISurcharge','FL-HISurcharge','2020-06-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'FL-HISurcharge','FL-HISurcharge','2020-07-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'FL-HISurcharge','FL-HISurcharge','2020-07-01', '2020-07-31',25);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Navigation','Navigation','2019-07-01', '2020-07-31',12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Navigation','Navigation','2019-08-01', '2020-07-31',12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Navigation','Navigation','2019-09-01', '2020-07-31',12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Navigation','Navigation','2019-10-01', '2020-07-31',12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Navigation','Navigation','2019-11-01', '2020-07-31',12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Navigation','Navigation','2019-12-01', '2020-07-31',12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Navigation','Navigation','2020-01-01', '2020-07-31',12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Navigation','Navigation','2020-02-01', '2020-07-31',12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Navigation','Navigation','2020-03-01', '2020-07-31',12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Navigation','Navigation','2020-04-01', '2020-07-31',12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Navigation','Navigation','2020-05-01', '2020-07-31',12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Navigation','Navigation','2020-06-01', '2020-07-31',12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Navigation','Navigation','2020-07-01', '2020-07-31',12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Navigation','Navigation','2020-07-01', '2020-07-31',12);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PortRisk','PortRisk','2019-07-01', '2020-07-31',40);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PortRisk','PortRisk','2019-08-01', '2020-07-31',40);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PortRisk','PortRisk','2019-09-01', '2020-07-31',40);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PortRisk','PortRisk','2019-10-01', '2020-07-31',40);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PortRisk','PortRisk','2019-11-01', '2020-07-31',40);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PortRisk','PortRisk','2019-12-01', '2020-07-31',40);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PortRisk','PortRisk','2020-01-01', '2020-07-31',40);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PortRisk','PortRisk','2020-02-01', '2020-07-31',40);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PortRisk','PortRisk','2020-03-01', '2020-07-31',40);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PortRisk','PortRisk','2020-04-01', '2020-07-31',40);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PortRisk','PortRisk','2020-05-01', '2020-07-31',40);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PortRisk','PortRisk','2020-06-01', '2020-07-31',40);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PortRisk','PortRisk','2020-07-01', '2020-07-31',40);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PortRisk','PortRisk','2020-07-01', '2020-07-31',40);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'SuperiorRisk','SuperiorRisk','2019-07-01', '2020-07-31',10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'SuperiorRisk','SuperiorRisk','2019-08-01', '2020-07-31',10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'SuperiorRisk','SuperiorRisk','2019-09-01', '2020-07-31',10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'SuperiorRisk','SuperiorRisk','2019-10-01', '2020-07-31',10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'SuperiorRisk','SuperiorRisk','2019-11-01', '2020-07-31',10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'SuperiorRisk','SuperiorRisk','2019-12-01', '2020-07-31',10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'SuperiorRisk','SuperiorRisk','2020-01-01', '2020-07-31',10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'SuperiorRisk','SuperiorRisk','2020-02-01', '2020-07-31',10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'SuperiorRisk','SuperiorRisk','2020-03-01', '2020-07-31',10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'SuperiorRisk','SuperiorRisk','2020-04-01', '2020-07-31',10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'SuperiorRisk','SuperiorRisk','2020-05-01', '2020-07-31',10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'SuperiorRisk','SuperiorRisk','2020-06-01', '2020-07-31',10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'SuperiorRisk','SuperiorRisk','2020-07-01', '2020-07-31',10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'SuperiorRisk','SuperiorRisk','2020-07-01', '2020-07-31',10);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'LayupA','LayupA','2019-07-01', '2020-07-31',15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'LayupA','LayupA','2019-08-01', '2020-07-31',15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'LayupA','LayupA','2019-09-01', '2020-07-31',15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'LayupA','LayupA','2019-10-01', '2020-07-31',15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'LayupA','LayupA','2019-11-01', '2020-07-31',15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'LayupA','LayupA','2019-12-01', '2020-07-31',15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'LayupA','LayupA','2020-01-01', '2020-07-31',15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'LayupA','LayupA','2020-02-01', '2020-07-31',15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'LayupA','LayupA','2020-03-01', '2020-07-31',15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'LayupA','LayupA','2020-04-01', '2020-07-31',15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'LayupA','LayupA','2020-05-01', '2020-07-31',15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'LayupA','LayupA','2020-06-01', '2020-07-31',15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'LayupA','LayupA','2020-07-01', '2020-07-31',15);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'LayupA','LayupA','2020-07-01', '2020-07-31',15);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup1','Layup1','2019-07-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup1','Layup1','2019-08-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup1','Layup1','2019-09-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup1','Layup1','2019-10-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup1','Layup1','2019-11-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup1','Layup1','2019-12-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup1','Layup1','2020-01-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup1','Layup1','2020-02-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup1','Layup1','2020-03-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup1','Layup1','2020-04-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup1','Layup1','2020-05-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup1','Layup1','2020-06-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup1','Layup1','2020-07-01', '2020-07-31',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup1','Layup1','2020-07-01', '2020-07-31',25);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup2','Layup2','2019-07-01', '2020-07-31',35);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup2','Layup2','2019-08-01', '2020-07-31',35);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup2','Layup2','2019-09-01', '2020-07-31',35);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup2','Layup2','2019-10-01', '2020-07-31',35);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup2','Layup2','2019-11-01', '2020-07-31',35);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup2','Layup2','2019-12-01', '2020-07-31',35);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup2','Layup2','2020-01-01', '2020-07-31',35);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup2','Layup2','2020-02-01', '2020-07-31',35);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup2','Layup2','2020-03-01', '2020-07-31',35);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup2','Layup2','2020-04-01', '2020-07-31',35);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup2','Layup2','2020-05-01', '2020-07-31',35);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup2','Layup2','2020-06-01', '2020-07-31',35);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup2','Layup2','2020-07-01', '2020-07-31',35);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Layup2','Layup2','2020-07-01', '2020-07-31',35);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleLessthan25','DeductibleLessthan25','2019-07-01', '2020-07-31',2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleLessthan25','DeductibleLessthan25','2019-08-01', '2020-07-31',2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleLessthan25','DeductibleLessthan25','2019-09-01', '2020-07-31',2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleLessthan25','DeductibleLessthan25','2019-10-01', '2020-07-31',2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleLessthan25','DeductibleLessthan25','2019-11-01', '2020-07-31',2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleLessthan25','DeductibleLessthan25','2019-12-01', '2020-07-31',2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleLessthan25','DeductibleLessthan25','2020-01-01', '2020-07-31',2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleLessthan25','DeductibleLessthan25','2020-02-01', '2020-07-31',2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleLessthan25','DeductibleLessthan25','2020-03-01', '2020-07-31',2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleLessthan25','DeductibleLessthan25','2020-04-01', '2020-07-31',2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleLessthan25','DeductibleLessthan25','2020-05-01', '2020-07-31',2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleLessthan25','DeductibleLessthan25','2020-06-01', '2020-07-31',2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleLessthan25','DeductibleLessthan25','2020-07-01', '2020-07-31',2);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleLessthan25','DeductibleLessthan25','2020-07-01', '2020-07-31',2);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleGreaterthan24','DeductibleGreaterthan24','2019-07-01', '2020-07-31',3);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleGreaterthan24','DeductibleGreaterthan24','2019-08-01', '2020-07-31',3);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleGreaterthan24','DeductibleGreaterthan24','2019-09-01', '2020-07-31',3);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleGreaterthan24','DeductibleGreaterthan24','2019-10-01', '2020-07-31',3);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleGreaterthan24','DeductibleGreaterthan24','2019-11-01', '2020-07-31',3);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleGreaterthan24','DeductibleGreaterthan24','2019-12-01', '2020-07-31',3);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleGreaterthan24','DeductibleGreaterthan24','2020-01-01', '2020-07-31',3);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleGreaterthan24','DeductibleGreaterthan24','2020-02-01', '2020-07-31',3);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleGreaterthan24','DeductibleGreaterthan24','2020-03-01', '2020-07-31',3);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleGreaterthan24','DeductibleGreaterthan24','2020-04-01', '2020-07-31',3);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleGreaterthan24','DeductibleGreaterthan24','2020-05-01', '2020-07-31',3);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleGreaterthan24','DeductibleGreaterthan24','2020-06-01', '2020-07-31',3);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleGreaterthan24','DeductibleGreaterthan24','2020-07-01', '2020-07-31',3);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'DeductibleGreaterthan24','DeductibleGreaterthan24','2020-07-01', '2020-07-31',3);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PassengerPremium','PassengerPremium','2019-07-01', '2020-07-31',47);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PassengerPremium','PassengerPremium','2019-08-01', '2020-07-31',47);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PassengerPremium','PassengerPremium','2019-09-01', '2020-07-31',47);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PassengerPremium','PassengerPremium','2019-10-01', '2020-07-31',47);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PassengerPremium','PassengerPremium','2019-11-01', '2020-07-31',47);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PassengerPremium','PassengerPremium','2019-12-01', '2020-07-31',47);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PassengerPremium','PassengerPremium','2020-01-01', '2020-07-31',47);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PassengerPremium','PassengerPremium','2020-02-01', '2020-07-31',47);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PassengerPremium','PassengerPremium','2020-03-01', '2020-07-31',47);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PassengerPremium','PassengerPremium','2020-04-01', '2020-07-31',47);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PassengerPremium','PassengerPremium','2020-05-01', '2020-07-31',47);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PassengerPremium','PassengerPremium','2020-06-01', '2020-07-31',47);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PassengerPremium','PassengerPremium','2020-07-01', '2020-07-31',47);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'PassengerPremium','PassengerPremium','2020-07-01', '2020-07-31',47);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '0 Excess', 'excessLiabilityCoverageDeclined', '2019-07-01', '2020-07-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '1M Excess','excessLiabilityCoverage1000000','2019-07-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '2M Excess','excessLiabilityCoverage2000000','2019-07-01', '2020-07-31',1575);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '3M Excess','excessLiabilityCoverage3000000','2019-07-01', '2020-07-31',2100);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '4M Excess','excessLiabilityCoverage4000000','2019-07-01', '2020-07-31',2625);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '9M Excess','excessLiabilityCoverage9000000', '2019-07-01', '2020-07-31',12250);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '0 Excess', 'excessLiabilityCoverageDeclined', '2019-08-01', '2020-07-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '1M Excess','excessLiabilityCoverage1000000','2019-08-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '2M Excess','excessLiabilityCoverage2000000','2019-08-01', '2020-07-31',1575);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '3M Excess','excessLiabilityCoverage3000000','2019-08-01', '2020-07-31',2100);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '4M Excess','excessLiabilityCoverage4000000','2019-08-01', '2020-07-31',2625);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '9M Excess','excessLiabilityCoverage9000000', '2019-08-01', '2020-07-31',12250);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '0 Excess', 'excessLiabilityCoverageDeclined', '2019-09-01', '2020-07-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '1M Excess','excessLiabilityCoverage1000000','2019-09-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '2M Excess','excessLiabilityCoverage2000000','2019-09-01', '2020-07-31',1575);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '3M Excess','excessLiabilityCoverage3000000','2019-09-01', '2020-07-31',2100);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '4M Excess','excessLiabilityCoverage4000000','2019-09-01', '2020-07-31',2625);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '9M Excess','excessLiabilityCoverage9000000', '2019-09-01', '2020-07-31',12250);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '0 Excess', 'excessLiabilityCoverageDeclined', '2019-10-01', '2020-07-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '1M Excess','excessLiabilityCoverage1000000','2019-10-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '2M Excess','excessLiabilityCoverage2000000','2019-10-01', '2020-07-31',1575);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '3M Excess','excessLiabilityCoverage3000000','2019-10-01', '2020-07-31',2100);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '4M Excess','excessLiabilityCoverage4000000','2019-10-01', '2020-07-31',2625);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '9M Excess','excessLiabilityCoverage9000000', '2019-10-01', '2020-07-31',12250);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '0 Excess', 'excessLiabilityCoverageDeclined', '2019-11-01', '2020-07-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '1M Excess','excessLiabilityCoverage1000000','2019-11-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '2M Excess','excessLiabilityCoverage2000000','2019-11-01', '2020-07-31',1575);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '3M Excess','excessLiabilityCoverage3000000','2019-11-01', '2020-07-31',2100);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '4M Excess','excessLiabilityCoverage4000000','2019-11-01', '2020-07-31',2625);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '9M Excess','excessLiabilityCoverage9000000', '2019-11-01', '2020-07-31',12250);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '0 Excess', 'excessLiabilityCoverageDeclined', '2019-12-01', '2020-07-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '1M Excess','excessLiabilityCoverage1000000','2019-12-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '2M Excess','excessLiabilityCoverage2000000','2019-12-01', '2020-07-31',1575);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '3M Excess','excessLiabilityCoverage3000000','2019-12-01', '2020-07-31',2100);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '4M Excess','excessLiabilityCoverage4000000','2019-12-01', '2020-07-31',2625);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '9M Excess','excessLiabilityCoverage9000000', '2019-12-01', '2020-07-31',12250);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '0 Excess', 'excessLiabilityCoverageDeclined', '2019-12-01', '2020-07-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '1M Excess','excessLiabilityCoverage1000000','2019-12-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '2M Excess','excessLiabilityCoverage2000000','2019-12-01', '2020-07-31',1575);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '3M Excess','excessLiabilityCoverage3000000','2019-12-01', '2020-07-31',2100);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '4M Excess','excessLiabilityCoverage4000000','2019-12-01', '2020-07-31',2625);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '9M Excess','excessLiabilityCoverage9000000', '2019-12-01', '2020-07-31',12250);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '0 Excess', 'excessLiabilityCoverageDeclined', '2020-01-01', '2020-07-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '1M Excess','excessLiabilityCoverage1000000','2020-01-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '2M Excess','excessLiabilityCoverage2000000','2020-01-01', '2020-07-31',1575);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '3M Excess','excessLiabilityCoverage3000000','2020-01-01', '2020-07-31',2100);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '4M Excess','excessLiabilityCoverage4000000','2020-01-01', '2020-07-31',2625);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '9M Excess','excessLiabilityCoverage9000000', '2020-01-01', '2020-07-31',12250);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '0 Excess', 'excessLiabilityCoverageDeclined', '2020-02-01', '2020-07-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '1M Excess','excessLiabilityCoverage1000000','2020-02-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '2M Excess','excessLiabilityCoverage2000000','2020-02-01', '2020-07-31',1575);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '3M Excess','excessLiabilityCoverage3000000','2020-02-01', '2020-07-31',2100);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '4M Excess','excessLiabilityCoverage4000000','2020-02-01', '2020-07-31',2625);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '9M Excess','excessLiabilityCoverage9000000', '2020-02-01', '2020-07-31',12250);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '0 Excess', 'excessLiabilityCoverageDeclined', '2020-03-01', '2020-07-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '1M Excess','excessLiabilityCoverage1000000','2020-03-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '2M Excess','excessLiabilityCoverage2000000','2020-03-01', '2020-07-31',1575);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '3M Excess','excessLiabilityCoverage3000000','2020-03-01', '2020-07-31',2100);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '4M Excess','excessLiabilityCoverage4000000','2020-03-01', '2020-07-31',2625);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '9M Excess','excessLiabilityCoverage9000000', '2020-03-01', '2020-07-31',12250);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '0 Excess', 'excessLiabilityCoverageDeclined', '2020-04-01', '2020-07-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '1M Excess','excessLiabilityCoverage1000000','2020-04-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '2M Excess','excessLiabilityCoverage2000000','2020-04-01', '2020-07-31',1575);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '3M Excess','excessLiabilityCoverage3000000','2020-04-01', '2020-07-31',2100);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '4M Excess','excessLiabilityCoverage4000000','2020-04-01', '2020-07-31',2625);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '9M Excess','excessLiabilityCoverage9000000', '2020-04-01', '2020-07-31',12250);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '0 Excess', 'excessLiabilityCoverageDeclined', '2020-05-01', '2020-07-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '1M Excess','excessLiabilityCoverage1000000','2020-05-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '2M Excess','excessLiabilityCoverage2000000','2020-05-01', '2020-07-31',1575);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '3M Excess','excessLiabilityCoverage3000000','2020-05-01', '2020-07-31',2100);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '4M Excess','excessLiabilityCoverage4000000','2020-05-01', '2020-07-31',2625);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '9M Excess','excessLiabilityCoverage9000000', '2020-05-01', '2020-07-31',12250);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '0 Excess', 'excessLiabilityCoverageDeclined', '2020-06-01', '2020-07-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '1M Excess','excessLiabilityCoverage1000000','2020-06-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '2M Excess','excessLiabilityCoverage2000000','2020-06-01', '2020-07-31',1575);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '3M Excess','excessLiabilityCoverage3000000','2020-06-01', '2020-07-31',2100);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '4M Excess','excessLiabilityCoverage4000000','2020-06-01', '2020-07-31',2625);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '9M Excess','excessLiabilityCoverage9000000', '2020-06-01', '2020-07-31',12250);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '0 Excess', 'excessLiabilityCoverageDeclined', '2020-07-01', '2020-07-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '1M Excess','excessLiabilityCoverage1000000','2020-07-01', '2020-07-31',1050);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '2M Excess','excessLiabilityCoverage2000000','2020-07-01', '2020-07-31',1575);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '3M Excess','excessLiabilityCoverage3000000','2020-07-01', '2020-07-31',2100);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '4M Excess','excessLiabilityCoverage4000000','2020-07-01', '2020-07-31',2625);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '9M Excess','excessLiabilityCoverage9000000', '2020-07-01', '2020-07-31',12250);






INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2019-06-30', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2019-06-30', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2019-06-30', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2019-06-30', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2019-06-30', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2019-06-30', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2019-06-30', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2019-06-30', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2019-06-30', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2019-06-30', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2019-06-30', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2019-06-30', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2019-06-30', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2019-06-30', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2019-06-30', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2019-06-30', '2020-07-31',0.00997272);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2019-07-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2019-07-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2019-07-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2019-07-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2019-07-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2019-07-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2019-07-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2019-07-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2019-07-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2019-07-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2019-07-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2019-07-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2019-07-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2019-07-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2019-07-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2019-07-01', '2020-07-31',0.00997272);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2019-08-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2019-08-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2019-08-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2019-08-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2019-08-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2019-08-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2019-08-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2019-08-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2019-08-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2019-08-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2019-08-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2019-08-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2019-08-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2019-08-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2019-08-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2019-08-01', '2020-07-31',0.00997272);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2019-09-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2019-09-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2019-09-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2019-09-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2019-09-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2019-09-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2019-09-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2019-09-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2019-09-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2019-09-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2019-09-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2019-09-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2019-09-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2019-09-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2019-09-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2019-09-01', '2020-07-31',0.00997272);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2019-10-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2019-10-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2019-10-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2019-10-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2019-10-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2019-10-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2019-10-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2019-10-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2019-10-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2019-10-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2019-10-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2019-10-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2019-10-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2019-10-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2019-10-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2019-10-01', '2020-07-31',0.00997272);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2019-11-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2019-11-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2019-11-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2019-11-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2019-11-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2019-11-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2019-11-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2019-11-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2019-11-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2019-11-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2019-11-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2019-11-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2019-11-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2019-11-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2019-11-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2019-11-01', '2020-07-31',0.00997272);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2019-12-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2019-12-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2019-12-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2019-12-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2019-12-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2019-12-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2019-12-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2019-12-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2019-12-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2019-12-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2019-12-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2019-12-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2019-12-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2019-12-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2019-12-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2019-12-01', '2020-07-31',0.00997272);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2020-01-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2020-01-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2020-01-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2020-01-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2020-01-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2020-01-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2020-01-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2020-01-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2020-01-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2020-01-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2020-01-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2020-01-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2020-01-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2020-01-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2020-01-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2020-01-01', '2020-07-31',0.00997272);




INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2020-02-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2020-02-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2020-02-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2020-02-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2020-02-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2020-02-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2020-02-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2020-02-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2020-02-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2020-02-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2020-02-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2020-02-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2020-02-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2020-02-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2020-02-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2020-02-01', '2020-07-31',0.00997272);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2020-03-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2020-03-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2020-03-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2020-03-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2020-03-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2020-03-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2020-03-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2020-03-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2020-03-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2020-03-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2020-03-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2020-03-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2020-03-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2020-03-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2020-03-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2020-03-01', '2020-07-31',0.00997272);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2020-04-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2020-04-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2020-04-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2020-04-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2020-04-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2020-04-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2020-04-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2020-04-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2020-04-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2020-04-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2020-04-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2020-04-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2020-04-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2020-04-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2020-04-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2020-04-01', '2020-07-31',0.00997272);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2020-05-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2020-05-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2020-05-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2020-05-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2020-05-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2020-05-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2020-05-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2020-05-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2020-05-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2020-05-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2020-05-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2020-05-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2020-05-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2020-05-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2020-05-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2020-05-01', '2020-07-31',0.00997272);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2020-06-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2020-06-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2020-06-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2020-06-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2020-06-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2020-06-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2020-06-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2020-06-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2020-06-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2020-06-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2020-06-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2020-06-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2020-06-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2020-06-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2020-06-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2020-06-01', '2020-07-31',0.00997272);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingCat','limitOver500000CoverBuildingCat','2020-07-01', '2020-07-31',0.010773);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingCat','limitOver250000CoverBuildingCat','2020-07-01', '2020-07-31',0.0118503);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingCat','limitOver100000CoverBuildingCat','2020-07-01', '2020-07-31',0.015561);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingCat','limitOver0CoverBuildingCat','2020-07-01', '2020-07-31',0.0172368);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBuildingNonCat','limitOver500000CoverBuildingNonCat','2020-07-01', '2020-07-31',0.0090804);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBuildingNonCat','limitOver250000CoverBuildingNonCat','2020-07-01', '2020-07-31',0.0095412);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBuildingNonCat','limitOver100000CoverBuildingNonCat','2020-07-01', '2020-07-31',0.0100548);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBuildingNonCat','limitOver0CoverBuildingNonCat','2020-07-01', '2020-07-31',0.0110808);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeCat','limitOver500000CoverBusIncomeCat','2020-07-01', '2020-07-31',0.0096957);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeCat','limitOver250000CoverBusIncomeCat','2020-07-01', '2020-07-31',0.01066527);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeCat','limitOver100000CoverBusIncomeCat','2020-07-01', '2020-07-31',0.0140049);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeCat','limitOver0CoverBusIncomeCat','2020-07-01', '2020-07-31',0.01551312);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver500000CoverBusIncomeNonCat','limitOver500000CoverBusIncomeNonCat','2020-07-01', '2020-07-31',0.00817236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver250000CoverBusIncomeNonCat','limitOver250000CoverBusIncomeNonCat','2020-07-01', '2020-07-31',0.00858708);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver100000CoverBusIncomeNonCat','limitOver100000CoverBusIncomeNonCat','2020-07-01', '2020-07-31',0.00904932);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'limitOver0CoverBusIncomeNonCat','limitOver0CoverBusIncomeNonCat','2020-07-01', '2020-07-31',0.00997272);




INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'standardCoverageUpTo50000','standardCoverageUpTo50000','2019-06-30', '2020-06-30',1631);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'standardCoverage50001To100000','standardCoverage50001To100000','2019-06-30', '2020-06-30',1874);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'standardCoverage100001To200000','standardCoverage100001To200000','2019-06-30', '2020-06-30',2153);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'standardCoverage200001To350000','standardCoverage200001To350000','2019-06-30', '2020-06-30',2470);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'standardCoverage350001To500000','standardCoverage350001To500000','2019-06-30', '2020-06-30',2939);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'standardCoverage500001To1M','standardCoverage500001To1M','2019-06-30', '2020-06-30',3141);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'standardCoverage1MAndOver','standardCoverage1MAndOver','2019-06-30', '2020-06-30',3359);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'liabilityOnlyUpTo50000','liabilityOnlyUpTo50000','2019-06-30', '2020-06-30',1631);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'liabilityOnly50001To100000','liabilityOnly50001To100000','2019-06-30', '2020-06-30',1874);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'liabilityOnly100001To200000','liabilityOnly100001To200000','2019-06-30', '2020-06-30',2153);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'liabilityOnly200001To350000','liabilityOnly200001To350000','2019-06-30', '2020-06-30',2470);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'liabilityOnly350001To500000','liabilityOnly350001To500000','2019-06-30', '2020-06-30',2939);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'liabilityOnly500001To1M','liabilityOnly500001To1M','2019-06-30', '2020-06-30',3141);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'liabilityOnly1MAndOver','liabilityOnly1MAndOver','2019-06-30', '2020-06-30',3359);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'discontinuedOperation','discontinuedOperation','2019-06-30', '2020-06-30',816);




INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'medicalExpense','medicalExpense','2019-06-30', '2020-06-30',55);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'nonOwnedAutoLiability100K','nonOwnedAutoLiability100K','2019-06-30', '2020-06-30',113);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'nonOwnedAutoLiability1M','nonOwnedAutoLiability1M','2019-06-30', '2020-06-30',849);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'excessLiabilityCoverage1M','excessLiabilityCoverage1M','2019-06-30', '2020-06-30',567);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'excessLiabilityCoverage2M','excessLiabilityCoverage2M','2019-06-30', '2020-06-30',1133);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'excessLiabilityCoverage3M','excessLiabilityCoverage3M','2019-06-30', '2020-06-30',1700);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'excessLiabilityCoverage4M','excessLiabilityCoverage4M','2019-06-30', '2020-06-30',2266);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'excessLiabilityCoverage9M','excessLiabilityCoverage9M','2019-06-30', '2020-06-30',5381);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'TAEOunder100k','TAEOunder100k','2019-06-30', '2020-06-30',283);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'TAEO100kTo500k','TAEO100kTo500k','2019-06-30', '2020-06-30',340);




INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'poolLiabilityOver50k','poolLiabilityOver50k','2019-06-30', '2020-06-30',1189);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'poolLiabilityOver20k','poolLiabilityOver20k','2019-06-30', '2020-06-30',793);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'poolLiabilityOver0','poolLiabilityOver0','2019-06-30', '2020-06-30',396);




INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'proRataFactorsJan','proRataFactorsJan','2020-01-01', '2020-12-31',0.493);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'proRataFactorsFeb','proRataFactorsFeb','2020-01-01', '2020-12-31',0.408);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'proRataFactorsMar','proRataFactorsMar','2020-01-01', '2020-12-31',0.332);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'proRataFactorsApr','proRataFactorsApr','2020-01-01', '2020-12-31',0.247);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'proRataFactorsMay','proRataFactorsMay','2020-01-01', '2020-12-31',0.164);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'proRataFactorsJun','proRataFactorsJun','2020-01-01', '2020-12-31',0.079);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'proRataFactorsJul','proRataFactorsJul','2020-01-01', '2020-12-31',1.000);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'proRataFactorsAug','proRataFactorsAug','2020-01-01', '2020-12-31',0.912);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'proRataFactorsSep','proRataFactorsSep','2020-01-01', '2020-12-31',0.827);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'proRataFactorsOct','proRataFactorsOct','2020-01-01', '2020-12-31',0.745);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'proRataFactorsNov','proRataFactorsNov','2020-01-01', '2020-12-31',0.660);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'proRataFactorsDec','proRataFactorsDec','2020-01-01', '2020-12-31',0.578);




INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'padiFee','padiFee','2020-01-01', '2020-12-31',50);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-01-01', '2020-01-31',693);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-01-01', '2020-01-31',1143);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-01-01', '2020-01-31',1455);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-01-01', '2020-01-31',1767);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-01-01', '2020-01-31',2286);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-01-01', '2020-01-31',2427);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-01-01', '2020-01-31',2910);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-01-01', '2020-01-31',3120);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-01-01', '2020-01-31',3465);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-02-01', '2020-02-29',578);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-02-01', '2020-02-29',953);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-02-01', '2020-02-29',1213);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-02-01', '2020-02-29',1473);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-02-01', '2020-02-29',1905);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-02-01', '2020-02-29',2023);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-02-01', '2020-02-29',2425);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-02-01', '2020-02-29',2600);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-02-01', '2020-02-29',2888);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-03-01', '2020-03-31',462);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-03-01', '2020-03-31',762);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-03-01', '2020-03-31',970);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-03-01', '2020-03-31',1178);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-03-01', '2020-03-31',1524);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-03-01', '2020-03-31',1618);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-03-01', '2020-03-31',1940);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-03-01', '2020-03-31',2080);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-03-01', '2020-03-31',2310);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-04-01', '2020-04-30',347);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-04-01', '2020-04-30',572);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-04-01', '2020-04-30',728);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-04-01', '2020-04-30',884);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-04-01', '2020-04-30',1143);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-04-01', '2020-04-30',1214);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-04-01', '2020-04-30',1455);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-04-01', '2020-04-30',1560);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-04-01', '2020-04-30',1733);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-05-01', '2020-05-31',231);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-05-01', '2020-05-31',381);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-05-01', '2020-05-31',485);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-05-01', '2020-05-31',589);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-05-01', '2020-05-31',762);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-05-01', '2020-05-31',809);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-05-01', '2020-05-31',970);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-05-01', '2020-05-31',1040);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-05-01', '2020-05-31',1155);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-06-01', '2020-06-30',116);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-06-01', '2020-06-30',191);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-06-01', '2020-06-30',243);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-06-01', '2020-06-30',295);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-06-01', '2020-06-30',381);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-06-01', '2020-06-30',405);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-06-01', '2020-06-30',485);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-06-01', '2020-06-30',520);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-06-01', '2020-06-30',578);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-07-01', '2020-07-31',1271);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-07-01', '2020-07-31',2286);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-07-01', '2020-07-31',2910);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-07-01', '2020-07-31',3534);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-07-01', '2020-07-31',4572);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-07-01', '2020-07-31',4854);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-07-01', '2020-07-31',5820);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-07-01', '2020-07-31',6240);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-07-01', '2020-07-31',6930);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-08-01', '2020-08-31',1155);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-08-01', '2020-08-31',2096);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-08-01', '2020-08-31',2668);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-08-01', '2020-08-31',3240);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-08-01', '2020-08-31',4191);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-08-01', '2020-08-31',4450);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-08-01', '2020-08-31',5335);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-08-01', '2020-08-31',5720);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-08-01', '2020-08-31',6353);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-09-01', '2020-09-30',1040);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-09-01', '2020-09-30',1905);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-09-01', '2020-09-30',2425);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-09-01', '2020-09-30',2945);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-09-01', '2020-09-30',3810);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-09-01', '2020-09-30',4045);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-09-01', '2020-09-30',4850);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-09-01', '2020-09-30',5200);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-09-01', '2020-09-30',5775);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-10-01', '2020-10-31',924);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-10-01', '2020-10-31',1715);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-10-01', '2020-10-31',2183);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-10-01', '2020-10-31',2651);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-10-01', '2020-10-31',3429);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-10-01', '2020-10-31',3641);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-10-01', '2020-10-31',4365);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-10-01', '2020-10-31',4680);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-10-01', '2020-10-31',5198);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-11-01', '2020-11-30',809);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-11-01', '2020-11-30',1524);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-11-01', '2020-11-30',1940);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-11-01', '2020-11-30',2356);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-11-01', '2020-11-30',3048);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-11-01', '2020-11-30',3236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-11-01', '2020-11-30',3880);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-11-01', '2020-11-30',4160);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-11-01', '2020-11-30',4620);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-12-01', '2020-12-31',674);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-12-01', '2020-12-31',1334);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-12-01', '2020-12-31',1698);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-12-01', '2020-12-31',2062);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-12-01', '2020-12-31',2667);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-12-01', '2020-12-31',2832);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-12-01', '2020-12-31',3395);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-12-01', '2020-12-31',3640);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-12-01', '2020-12-31',4043);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupExcessLiability1M','groupExcessLiability1M','2020-01-01', '2020-12-31',65);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupExcessLiability2M','groupExcessLiability2M','2020-01-01', '2020-12-31',125);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupExcessLiability3M','groupExcessLiability3M','2020-01-01', '2020-12-31',160);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupExcessLiability4M','groupExcessLiability4M','2020-01-01', '2020-12-31',200);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupExcessLiability9M','groupExcessLiability9M','2020-01-01', '2020-12-31',350);




INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'groupPadiFee','groupPadiFee','2020-01-01', '2020-12-31',175);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'propertyDeductibles1000','propertyDeductibles1000','2020-01-01', '2020-12-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'propertyDeductibles2500','propertyDeductibles2500','2020-01-01', '2020-12-31',4.37);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Store', 'propertyDeductibles5000','propertyDeductibles5000','2020-01-01', '2020-12-31',8.93);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-01-01', '2020-01-31',693);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-01-01', '2020-01-31',1143);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-01-01', '2020-01-31',1455);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-01-01', '2020-01-31',1767);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-01-01', '2020-01-31',2286);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-01-01', '2020-01-31',2427);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-01-01', '2020-01-31',2910);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-01-01', '2020-01-31',3120);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-01-01', '2020-01-31',3465);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-02-01', '2020-02-29',578);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-02-01', '2020-02-29',953);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-02-01', '2020-02-29',1213);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-02-01', '2020-02-29',1473);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-02-01', '2020-02-29',1905);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-02-01', '2020-02-29',2023);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-02-01', '2020-02-29',2425);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-02-01', '2020-02-29',2600);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-02-01', '2020-02-29',2888);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-03-01', '2020-03-31',462);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-03-01', '2020-03-31',762);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-03-01', '2020-03-31',970);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-03-01', '2020-03-31',1178);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-03-01', '2020-03-31',1524);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-03-01', '2020-03-31',1618);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-03-01', '2020-03-31',1940);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-03-01', '2020-03-31',2080);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-03-01', '2020-03-31',2310);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-04-01', '2020-04-30',347);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-04-01', '2020-04-30',572);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-04-01', '2020-04-30',728);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-04-01', '2020-04-30',884);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-04-01', '2020-04-30',1143);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-04-01', '2020-04-30',1214);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-04-01', '2020-04-30',1455);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-04-01', '2020-04-30',1560);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-04-01', '2020-04-30',1733);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-05-01', '2020-05-31',231);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-05-01', '2020-05-31',381);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-05-01', '2020-05-31',485);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-05-01', '2020-05-31',589);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-05-01', '2020-05-31',762);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-05-01', '2020-05-31',809);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-05-01', '2020-05-31',970);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-05-01', '2020-05-31',1040);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-05-01', '2020-05-31',1155);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-06-01', '2020-06-30',116);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-06-01', '2020-06-30',191);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-06-01', '2020-06-30',243);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-06-01', '2020-06-30',295);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-06-01', '2020-06-30',381);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-06-01', '2020-06-30',405);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-06-01', '2020-06-30',485);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-06-01', '2020-06-30',520);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-06-01', '2020-06-30',578);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-07-01', '2020-07-31',1271);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-07-01', '2020-07-31',2286);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-07-01', '2020-07-31',2910);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-07-01', '2020-07-31',3534);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-07-01', '2020-07-31',4572);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-07-01', '2020-07-31',4854);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-07-01', '2020-07-31',5820);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-07-01', '2020-07-31',6240);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-07-01', '2020-07-31',6930);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-08-01', '2020-08-31',1155);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-08-01', '2020-08-31',2096);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-08-01', '2020-08-31',2668);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-08-01', '2020-08-31',3240);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-08-01', '2020-08-31',4191);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-08-01', '2020-08-31',4450);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-08-01', '2020-08-31',5335);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-08-01', '2020-08-31',5720);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-08-01', '2020-08-31',6353);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-09-01', '2020-09-30',1040);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-09-01', '2020-09-30',1905);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-09-01', '2020-09-30',2425);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-09-01', '2020-09-30',2945);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-09-01', '2020-09-30',3810);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-09-01', '2020-09-30',4045);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-09-01', '2020-09-30',4850);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-09-01', '2020-09-30',5200);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-09-01', '2020-09-30',5775);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-10-01', '2020-10-31',924);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-10-01', '2020-10-31',1715);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-10-01', '2020-10-31',2183);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-10-01', '2020-10-31',2651);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-10-01', '2020-10-31',3429);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-10-01', '2020-10-31',3641);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-10-01', '2020-10-31',4365);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-10-01', '2020-10-31',4680);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-10-01', '2020-10-31',5198);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-11-01', '2020-11-30',809);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-11-01', '2020-11-30',1524);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-11-01', '2020-11-30',1940);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-11-01', '2020-11-30',2356);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-11-01', '2020-11-30',3048);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-11-01', '2020-11-30',3236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-11-01', '2020-11-30',3880);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-11-01', '2020-11-30',4160);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-11-01', '2020-11-30',4620);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan0','groupCoverageMoreThan0','2020-12-01', '2020-12-31',674);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan25000','groupCoverageMoreThan25000','2020-12-01', '2020-12-31',1334);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan50000','groupCoverageMoreThan50000','2020-12-01', '2020-12-31',1698);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan100000','groupCoverageMoreThan100000','2020-12-01', '2020-12-31',2062);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan150000','groupCoverageMoreThan150000','2020-12-01', '2020-12-31',2667);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan200000','groupCoverageMoreThan200000','2020-12-01', '2020-12-31',2832);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan250000','groupCoverageMoreThan250000','2020-12-01', '2020-12-31',3395);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan350000','groupCoverageMoreThan350000','2020-12-01', '2020-12-31',3640);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupCoverageMoreThan500000','groupCoverageMoreThan500000','2020-12-01', '2020-12-31',4043);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupExcessLiability1M','groupExcessLiability1M','2020-01-01', '2020-12-31',65);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupExcessLiability2M','groupExcessLiability2M','2020-01-01', '2020-12-31',125);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupExcessLiability3M','groupExcessLiability3M','2020-01-01', '2020-12-31',160);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupExcessLiability4M','groupExcessLiability4M','2020-01-01', '2020-12-31',200);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupExcessLiability9M','groupExcessLiability9M','2020-01-01', '2020-12-31',350);




INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'groupPadiFee','groupPadiFee','2020-01-01', '2020-12-31',175);



