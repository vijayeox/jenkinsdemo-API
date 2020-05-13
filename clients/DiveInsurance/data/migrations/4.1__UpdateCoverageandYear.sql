ALTER TABLE `premium_rate_card` DROP COLUMN `year`;

DELETE FROM `premium_rate_card` WHERE product = 'Group Professional Liability';

UPDATE premium_rate_card SET coverage = 'None' WHERE product = 'Dive Boat' and `key` = 'excessLiabilityCoverageDeclined';
UPDATE premium_rate_card SET coverage = '1M' WHERE product = 'Dive Boat' and `key` = 'excessLiabilityCoverage1000000';
UPDATE premium_rate_card SET coverage = '2M' WHERE product = 'Dive Boat' and `key` = 'excessLiabilityCoverage2000000';
UPDATE premium_rate_card SET coverage = '3M' WHERE product = 'Dive Boat' and `key` = 'excessLiabilityCoverage3000000';
UPDATE premium_rate_card SET coverage = '4M' WHERE product = 'Dive Boat' and `key` = 'excessLiabilityCoverage4000000';
UPDATE premium_rate_card SET coverage = '9M' WHERE product = 'Dive Boat' and `key` = 'excessLiabilityCoverage9000000';


DELETE FROM premium_rate_card WHERE product = 'Dive Boat' and `key` IN ('groupPadiFee','groupExcessLiability1M','groupExcessLiability2M','groupExcessLiability3M','groupExcessLiability4M','groupExcessLiability9M');


UPDATE premium_rate_card SET coverage = '0 to $25,000' WHERE product = 'Dive Boat' and `key` = 'groupCoverageMoreThan0';
UPDATE premium_rate_card SET coverage = '$25,001 to $50,000' WHERE product = 'Dive Boat' and `key` = 'groupCoverageMoreThan25000';
UPDATE premium_rate_card SET coverage = '$50,001 to $100,000' WHERE product = 'Dive Boat' and `key` = 'groupCoverageMoreThan50000';
UPDATE premium_rate_card SET coverage = '$100,001 to $150,000' WHERE product = 'Dive Boat' and `key` = 'groupCoverageMoreThan100000';
UPDATE premium_rate_card SET coverage = '$150,001 to $200,000' WHERE product = 'Dive Boat' and `key` = 'groupCoverageMoreThan150000';
UPDATE premium_rate_card SET coverage = '$200,001 to $250,000' WHERE product = 'Dive Boat' and `key` = 'groupCoverageMoreThan200000';
UPDATE premium_rate_card SET coverage = '$250,001 to $350,000' WHERE product = 'Dive Boat' and `key` = 'groupCoverageMoreThan250000';
UPDATE premium_rate_card SET coverage = '$350,001 to $500,000' WHERE product = 'Dive Boat' and `key` = 'groupCoverageMoreThan350000';
UPDATE premium_rate_card SET coverage = '$500,001 and up' WHERE product = 'Dive Boat' and `key` = 'groupCoverageMoreThan500000';




INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Padi Fee','groupPadiFee','2020-01-01', '2020-01-31',175);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Padi Fee','groupPadiFee','2020-02-01', '2020-02-29',175);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Padi Fee','groupPadiFee','2020-03-01', '2020-03-31',175);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Padi Fee','groupPadiFee','2020-04-01', '2020-04-30',175);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Padi Fee','groupPadiFee','2020-05-01', '2020-05-31',175);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Padi Fee','groupPadiFee','2020-06-01', '2020-06-30',175);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', 'Padi Fee','groupPadiFee','2020-07-01', '2020-07-31',175);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '1M','groupExcessLiability1M','2020-01-01', '2020-01-31',65);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '2M','groupExcessLiability2M','2020-01-01', '2020-01-31',125);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-01-01', '2020-01-31',160);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-01-01', '2020-01-31',200);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-01-01', '2020-01-31',350);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '1M','groupExcessLiability1M','2020-02-01', '2020-02-29',65);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '2M','groupExcessLiability2M','2020-02-01', '2020-02-29',125);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-02-01', '2020-02-29',160);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-02-01', '2020-02-29',200);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-02-01', '2020-02-29',350);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '1M','groupExcessLiability1M','2020-03-01', '2020-03-31',65);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '2M','groupExcessLiability2M','2020-03-01', '2020-03-31',125);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-03-01', '2020-03-31',160);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-03-01', '2020-03-31',200);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-03-01', '2020-03-31',350);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '1M','groupExcessLiability1M','2020-04-01', '2020-04-30',65);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '2M','groupExcessLiability2M','2020-04-01', '2020-04-30',125);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-04-01', '2020-04-30',160);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-04-01', '2020-04-30',200);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-04-01', '2020-04-30',350);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '1M','groupExcessLiability1M','2020-05-01', '2020-05-31',65);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '2M','groupExcessLiability2M','2020-05-01', '2020-05-31',125);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-05-01', '2020-05-31',160);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-05-01', '2020-05-31',200);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-05-01', '2020-05-31',350);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '1M','groupExcessLiability1M','2020-06-01', '2020-06-30',65);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '2M','groupExcessLiability2M','2020-06-01', '2020-06-30',125);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-06-01', '2020-06-30',160);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-06-01', '2020-06-30',200);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-06-01', '2020-06-30',350);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '1M','groupExcessLiability1M','2020-07-01', '2020-07-31',65);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '2M','groupExcessLiability2M','2020-07-01', '2020-07-31',125);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-07-01', '2020-07-31',160);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-07-01', '2020-07-31',200);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-07-01', '2020-07-31',350);



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '1M','groupExcessLiability1M','2020-01-01', '2020-01-31',0.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '2M','groupExcessLiability2M','2020-01-01', '2020-01-31',60.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-01-01', '2020-01-31',95.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-01-01', '2020-01-31',135.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-01-01', '2020-01-31',285.00,1,'groupExcessLiability1M');


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '2M','groupExcessLiability2M','2020-01-01', '2020-01-31',0.00,1,'groupExcessLiability2M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-01-01', '2020-01-31',35.00,1,'groupExcessLiability2M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-01-01', '2020-01-31',75.00,1,'groupExcessLiability2M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-01-01', '2020-01-31',225.00,1,'groupExcessLiability2M');

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-01-01', '2020-01-31',0.00,1,'groupExcessLiability3M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-01-01', '2020-01-31',40.00,1,'groupExcessLiability3M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-01-01', '2020-01-31',190.00,1,'groupExcessLiability3M');


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-01-01', '2020-01-31',0.00,1,'groupExcessLiability4M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-01-01', '2020-01-31',150.00,1,'groupExcessLiability4M');


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-01-01', '2020-01-31',150.00,1,'groupExcessLiability9M');



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '1M','groupExcessLiability1M','2020-02-01', '2020-02-29',0.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '2M','groupExcessLiability2M','2020-02-01', '2020-02-29',60.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-02-01', '2020-02-29',95.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-02-01', '2020-02-29',135.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-02-01', '2020-02-29',285.00,1,'groupExcessLiability1M');


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '2M','groupExcessLiability2M','2020-02-01', '2020-02-29',0.00,1,'groupExcessLiability2M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-02-01', '2020-02-29',35.00,1,'groupExcessLiability2M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-02-01', '2020-02-29',75.00,1,'groupExcessLiability2M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-02-01', '2020-02-29',225.00,1,'groupExcessLiability2M');

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-02-01', '2020-02-29',0.00,1,'groupExcessLiability3M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-02-01', '2020-02-29',40.00,1,'groupExcessLiability3M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-02-01', '2020-02-29',190.00,1,'groupExcessLiability3M');


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-02-01', '2020-02-29',0.00,1,'groupExcessLiability4M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-02-01', '2020-02-29',150.00,1,'groupExcessLiability4M');


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-02-01', '2020-02-29',150.00,1,'groupExcessLiability9M');



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '1M','groupExcessLiability1M','2020-03-01', '2020-03-31',0.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '2M','groupExcessLiability2M','2020-03-01', '2020-03-31',60.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-03-01', '2020-03-31',95.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-03-01', '2020-03-31',135.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-03-01', '2020-03-31',285.00,1,'groupExcessLiability1M');


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '2M','groupExcessLiability2M','2020-03-01', '2020-03-31',0.00,1,'groupExcessLiability2M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-03-01', '2020-03-31',35.00,1,'groupExcessLiability2M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-03-01', '2020-03-31',75.00,1,'groupExcessLiability2M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-03-01', '2020-03-31',225.00,1,'groupExcessLiability2M');

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-03-01', '2020-03-31',0.00,1,'groupExcessLiability3M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-03-01', '2020-03-31',40.00,1,'groupExcessLiability3M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-03-01', '2020-03-31',190.00,1,'groupExcessLiability3M');


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-03-01', '2020-03-31',0.00,1,'groupExcessLiability4M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-03-01', '2020-03-31',150.00,1,'groupExcessLiability4M');


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-03-01', '2020-03-31',150.00,1,'groupExcessLiability9M');


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '1M','groupExcessLiability1M','2020-04-01', '2020-04-30',0.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '2M','groupExcessLiability2M','2020-04-01', '2020-04-30',60.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-04-01', '2020-04-30',95.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-04-01', '2020-04-30',135.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-04-01', '2020-04-30',285.00,1,'groupExcessLiability1M');


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '2M','groupExcessLiability2M','2020-04-01', '2020-04-30',0.00,1,'groupExcessLiability2M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-04-01', '2020-04-30',35.00,1,'groupExcessLiability2M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-04-01', '2020-04-30',75.00,1,'groupExcessLiability2M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-04-01', '2020-04-30',225.00,1,'groupExcessLiability2M');

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-04-01', '2020-04-30',0.00,1,'groupExcessLiability3M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-04-01', '2020-04-30',40.00,1,'groupExcessLiability3M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-04-01', '2020-04-30',190.00,1,'groupExcessLiability3M');


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-04-01', '2020-04-30',0.00,1,'groupExcessLiability4M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-04-01', '2020-04-30',150.00,1,'groupExcessLiability4M');


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-04-01', '2020-04-30',150.00,1,'groupExcessLiability9M');



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '1M','groupExcessLiability1M','2020-05-01', '2020-05-31',0.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '2M','groupExcessLiability2M','2020-05-01', '2020-05-31',60.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-05-01', '2020-05-31',95.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-05-01', '2020-05-31',135.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-05-01', '2020-05-31',285.00,1,'groupExcessLiability1M');


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '2M','groupExcessLiability2M','2020-05-01', '2020-05-31',0.00,1,'groupExcessLiability2M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-05-01', '2020-05-31',35.00,1,'groupExcessLiability2M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-05-01', '2020-05-31',75.00,1,'groupExcessLiability2M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-05-01', '2020-05-31',225.00,1,'groupExcessLiability2M');

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-05-01', '2020-05-31',0.00,1,'groupExcessLiability3M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-05-01', '2020-05-31',40.00,1,'groupExcessLiability3M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-05-01', '2020-05-31',190.00,1,'groupExcessLiability3M');


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-05-01', '2020-05-31',0.00,1,'groupExcessLiability4M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-05-01', '2020-05-31',150.00,1,'groupExcessLiability4M');


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-05-01', '2020-05-31',150.00,1,'groupExcessLiability9M');



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '1M','groupExcessLiability1M','2020-06-01', '2020-06-30',0.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '2M','groupExcessLiability2M','2020-06-01', '2020-06-30',60.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-06-01', '2020-06-30',95.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-06-01', '2020-06-30',135.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-06-01', '2020-06-30',285.00,1,'groupExcessLiability1M');


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '2M','groupExcessLiability2M','2020-06-01', '2020-06-30',0.00,1,'groupExcessLiability2M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-06-01', '2020-06-30',35.00,1,'groupExcessLiability2M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-06-01', '2020-06-30',75.00,1,'groupExcessLiability2M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-06-01', '2020-06-30',225.00,1,'groupExcessLiability2M');

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-06-01', '2020-06-30',0.00,1,'groupExcessLiability3M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-06-01', '2020-06-30',40.00,1,'groupExcessLiability3M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-06-01', '2020-06-30',190.00,1,'groupExcessLiability3M');


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-06-01', '2020-06-30',0.00,1,'groupExcessLiability4M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-06-01', '2020-06-30',150.00,1,'groupExcessLiability4M');


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-06-01', '2020-06-30',150.00,1,'groupExcessLiability9M');



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '1M','groupExcessLiability1M','2020-07-01', '2020-07-31',0.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '2M','groupExcessLiability2M','2020-07-01', '2020-07-31',60.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-07-01', '2020-07-31',95.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-07-01', '2020-07-31',135.00,1,'groupExcessLiability1M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-07-01', '2020-07-31',285.00,1,'groupExcessLiability1M');


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '2M','groupExcessLiability2M','2020-07-01', '2020-07-31',0.00,1,'groupExcessLiability2M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-07-01', '2020-07-31',35.00,1,'groupExcessLiability2M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-07-01', '2020-07-31',75.00,1,'groupExcessLiability2M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-07-01', '2020-07-31',225.00,1,'groupExcessLiability2M');

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '3M','groupExcessLiability3M','2020-07-01', '2020-07-31',0.00,1,'groupExcessLiability3M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-07-01', '2020-07-31',40.00,1,'groupExcessLiability3M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-07-01', '2020-07-31',190.00,1,'groupExcessLiability3M');


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '4M','groupExcessLiability4M','2020-07-01', '2020-07-31',0.00,1,'groupExcessLiability4M');
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-07-01', '2020-07-31',150.00,1,'groupExcessLiability4M');


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`is_upgrade`,`previous_key`) VALUES ('Dive Boat', '9M','groupExcessLiability9M','2020-07-01', '2020-07-31',150.00,1,'groupExcessLiability9M');






UPDATE premium_rate_card SET coverage = 'Hull value 25000 (Age less than 5 years)' WHERE product = 'Dive Boat' and `key` = 'hull25000LessThan5';
UPDATE premium_rate_card SET coverage = 'Hull value 50000 (Age less than 5 years)' WHERE product = 'Dive Boat' and `key` = 'hull50000LessThan5';
UPDATE premium_rate_card SET coverage = 'Hull value 100000 (Age less than 5 years)' WHERE product = 'Dive Boat' and `key` = 'hull100000LessThan5';
UPDATE premium_rate_card SET coverage = 'Hull value 150000 (Age less than 5 years)' WHERE product = 'Dive Boat' and `key` = 'hull150000LessThan5';
UPDATE premium_rate_card SET coverage = 'Hull value 200000 (Age less than 5 years)' WHERE product = 'Dive Boat' and `key` = 'hull200000LessThan5';
UPDATE premium_rate_card SET coverage = 'Hull value 250000 (Age less than 5 years)' WHERE product = 'Dive Boat' and `key` = 'hull250000LessThan5';
UPDATE premium_rate_card SET coverage = 'Hull value 300000 (Age less than 5 years)' WHERE product = 'Dive Boat' and `key` = 'hull300000LessThan5';
UPDATE premium_rate_card SET coverage = 'Hull value 350000 (Age less than 5 years)' WHERE product = 'Dive Boat' and `key` = 'hull350000LessThan5';
UPDATE premium_rate_card SET coverage = 'Hull value 400000 (Age less than 5 years)' WHERE product = 'Dive Boat' and `key` = 'hull400000LessThan5';
UPDATE premium_rate_card SET coverage = 'Hull value 500000 (Age less than 5 years)' WHERE product = 'Dive Boat' and `key` = 'hull500000LessThan5';
UPDATE premium_rate_card SET coverage = 'Hull value 600000 (Age less than 5 years)' WHERE product = 'Dive Boat' and `key` = 'hull600000LessThan5';


UPDATE premium_rate_card SET coverage = 'Hull value 25000 (Age less than 11 years)' WHERE product = 'Dive Boat' and `key` = 'hull25000LessThan11';
UPDATE premium_rate_card SET coverage = 'Hull value 50000 (Age less than 11 years)' WHERE product = 'Dive Boat' and `key` = 'hull50000LessThan11';
UPDATE premium_rate_card SET coverage = 'Hull value 100000 (Age less than 11 years)' WHERE product = 'Dive Boat' and `key` = 'hull100000LessThan11';
UPDATE premium_rate_card SET coverage = 'Hull value 150000 (Age less than 11 years)' WHERE product = 'Dive Boat' and `key` = 'hull150000LessThan11';
UPDATE premium_rate_card SET coverage = 'Hull value 200000 (Age less than 11 years)' WHERE product = 'Dive Boat' and `key` = 'hull200000LessThan11';
UPDATE premium_rate_card SET coverage = 'Hull value 250000 (Age less than 11 years)' WHERE product = 'Dive Boat' and `key` = 'hull250000LessThan11';
UPDATE premium_rate_card SET coverage = 'Hull value 300000 (Age less than 11 years)' WHERE product = 'Dive Boat' and `key` = 'hull300000LessThan11';
UPDATE premium_rate_card SET coverage = 'Hull value 350000 (Age less than 11 years)' WHERE product = 'Dive Boat' and `key` = 'hull350000LessThan11';
UPDATE premium_rate_card SET coverage = 'Hull value 400000 (Age less than 11 years)' WHERE product = 'Dive Boat' and `key` = 'hull400000LessThan11';
UPDATE premium_rate_card SET coverage = 'Hull value 500000 (Age less than 11 years)' WHERE product = 'Dive Boat' and `key` = 'hull500000LessThan11';
UPDATE premium_rate_card SET coverage = 'Hull value 600000 (Age less than 11 years)' WHERE product = 'Dive Boat' and `key` = 'hull600000LessThan11';


UPDATE premium_rate_card SET coverage = 'Hull value 25000 (Age less than 25 years)' WHERE product = 'Dive Boat' and `key` = 'hull25000LessThan25';
UPDATE premium_rate_card SET coverage = 'Hull value 50000 (Age less than 25 years)' WHERE product = 'Dive Boat' and `key` = 'hull50000LessThan25';
UPDATE premium_rate_card SET coverage = 'Hull value 100000 (Age less than 25 years)' WHERE product = 'Dive Boat' and `key` = 'hull100000LessThan25';
UPDATE premium_rate_card SET coverage = 'Hull value 150000 (Age less than 25 years)' WHERE product = 'Dive Boat' and `key` = 'hull150000LessThan25';
UPDATE premium_rate_card SET coverage = 'Hull value 200000 (Age less than 25 years)' WHERE product = 'Dive Boat' and `key` = 'hull200000LessThan25';
UPDATE premium_rate_card SET coverage = 'Hull value 250000 (Age less than 25 years)' WHERE product = 'Dive Boat' and `key` = 'hull250000LessThan25';
UPDATE premium_rate_card SET coverage = 'Hull value 300000 (Age less than 25 years)' WHERE product = 'Dive Boat' and `key` = 'hull300000LessThan25';
UPDATE premium_rate_card SET coverage = 'Hull value 350000 (Age less than 25 years)' WHERE product = 'Dive Boat' and `key` = 'hull350000LessThan25';
UPDATE premium_rate_card SET coverage = 'Hull value 400000 (Age less than 25 years)' WHERE product = 'Dive Boat' and `key` = 'hull400000LessThan25';
UPDATE premium_rate_card SET coverage = 'Hull value 500000 (Age less than 25 years)' WHERE product = 'Dive Boat' and `key` = 'hull500000LessThan25';
UPDATE premium_rate_card SET coverage = 'Hull value 600000 (Age less than 25 years)' WHERE product = 'Dive Boat' and `key` = 'hull600000LessThan25';


UPDATE premium_rate_card SET coverage = 'Hull value 25000 (Age greater than 25 years)' WHERE product = 'Dive Boat' and `key` = 'hull25000GreaterThan25';
UPDATE premium_rate_card SET coverage = 'Hull value 50000 (Age greater than25 years)' WHERE product = 'Dive Boat' and `key` = 'hull50000GreaterThan25';
UPDATE premium_rate_card SET coverage = 'Hull value 100000 (Age greater than 25 years)' WHERE product = 'Dive Boat' and `key` = 'hull100000GreaterThan25';
UPDATE premium_rate_card SET coverage = 'Hull value 150000 (Age greater than 25 years)' WHERE product = 'Dive Boat' and `key` = 'hull150000GreaterThan25';
UPDATE premium_rate_card SET coverage = 'Hull value 200000 (Age greater than 25 years)' WHERE product = 'Dive Boat' and `key` = 'hull200000GreaterThan25';
UPDATE premium_rate_card SET coverage = 'Hull value 250000 (Age greater than 25 years)' WHERE product = 'Dive Boat' and `key` = 'hull250000GreaterThan25';
UPDATE premium_rate_card SET coverage = 'Hull value 300000 (Age greater than 25 years)' WHERE product = 'Dive Boat' and `key` = 'hull300000GreaterThan25';
UPDATE premium_rate_card SET coverage = 'Hull value 350000 (Age greater than 25 years)' WHERE product = 'Dive Boat' and `key` = 'hull350000GreaterThan25';
UPDATE premium_rate_card SET coverage = 'Hull value 400000 (Age greater than 25 years)' WHERE product = 'Dive Boat' and `key` = 'hull400000GreaterThan25';
UPDATE premium_rate_card SET coverage = 'Hull value 500000 (Age greater than 25 years)' WHERE product = 'Dive Boat' and `key` = 'hull500000GreaterThan25';
UPDATE premium_rate_card SET coverage = 'Hull value 600000 (Age greater than 25 years)' WHERE product = 'Dive Boat' and `key` = 'hull600000GreaterThan25';



UPDATE premium_rate_card SET coverage = 'Deductible (Age less than 25 years)' WHERE product = 'Dive Boat' and `key` = 'DeductibleLessthan25';
UPDATE premium_rate_card SET coverage = 'Deductible (Age greater than 25 years)' WHERE product = 'Dive Boat' and `key` = 'DeductibleGreaterthan24';



UPDATE premium_rate_card SET end_date = CONCAT(DATE_FORMAT(end_date, '%Y-%m-'), '22') WHERE product = 'Dive Boat' and `key` NOT IN ('groupCoverageMoreThan500000','groupCoverageMoreThan350000','groupCoverageMoreThan250000','groupCoverageMoreThan0','groupCoverageMoreThan25000','groupCoverageMoreThan50000','groupCoverageMoreThan100000','groupCoverageMoreThan150000','groupCoverageMoreThan200000','groupExcessLiability1M','groupExcessLiability2M','groupExcessLiability3M','groupExcessLiability4M','groupExcessLiability9M','groupPadiFee');

ALTER TABLE premium_rate_card ADD COLUMN `year` INT(4);

UPDATE premium_rate_card  SET `year` = CASE WHEN (MONTH(end_date) < 7) THEN
					  	(YEAR(end_date) - 1)
					ELSE 
						YEAR(end_date)
					END WHERE product != 'Dive Boat';

UPDATE premium_rate_card  SET `year` = CASE WHEN (MONTH(end_date) < 8) THEN
					  	(YEAR(end_date)-1)
					ELSE 
						YEAR(end_date)
					END WHERE product = 'Dive Boat';


ALTER TABLE premium_rate_card MODIFY COLUMN `year` INT(4) NOT NULL;


DELETE FROM state_tax WHERE `product` = 'Dive Boat' AND `coverage` = 'group';
ALTER TABLE state_tax DROP COLUMN `product`;
ALTER TABLE state_tax ADD COLUMN `year` INT(4);
UPDATE state_tax SET `year` = YEAR(end_date);
ALTER TABLE state_tax MODIFY COLUMN `year` INT(4) NOT NULL;


ALTER TABLE carrier_policy ADD COLUMN `year` INT(4);
UPDATE carrier_policy SET `year` = YEAR(start_date);
ALTER TABLE carrier_policy MODIFY COLUMN `year` INT(4) NOT NULL;


