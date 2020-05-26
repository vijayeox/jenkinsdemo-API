UPDATE premium_rate_card SET premium = 592, total = 592 WHERE `coverage` = '9M Excess' and product = 'Individual Professional Liability' and is_upgrade = 0;

UPDATE premium_rate_card SET premium = 312,total = 312 WHERE `coverage` = 'Equipment Liability Coverage' and product = 'Individual Professional Liability' and is_upgrade = 0 and start_date = '2019-06-30' and end_date = '2019-07-31';
UPDATE premium_rate_card SET premium = 287,total = 287 WHERE `coverage` = 'Equipment Liability Coverage' and product = 'Individual Professional Liability' and is_upgrade = 0 and start_date = '2019-08-01' and end_date = '2019-08-31';
UPDATE premium_rate_card SET premium = 261,total = 261 WHERE `coverage` = 'Equipment Liability Coverage' and product = 'Individual Professional Liability' and is_upgrade = 0 and start_date = '2019-09-01' and end_date = '2019-09-30';
UPDATE premium_rate_card SET premium = 235,total = 235 WHERE `coverage` = 'Equipment Liability Coverage' and product = 'Individual Professional Liability' and is_upgrade = 0 and start_date = '2019-10-01' and end_date = '2019-10-31';
UPDATE premium_rate_card SET premium = 209,total = 209 WHERE `coverage` = 'Equipment Liability Coverage' and product = 'Individual Professional Liability' and is_upgrade = 0 and start_date = '2019-11-01' and end_date = '2019-11-30';
UPDATE premium_rate_card SET premium = 183,total = 183 WHERE `coverage` = 'Equipment Liability Coverage' and product = 'Individual Professional Liability' and is_upgrade = 0 and start_date = '2019-12-01' and end_date = '2019-12-31';
UPDATE premium_rate_card SET premium = 156,total = 156 WHERE `coverage` = 'Equipment Liability Coverage' and product = 'Individual Professional Liability' and is_upgrade = 0 and start_date = '2020-01-01' and end_date = '2020-01-31';
UPDATE premium_rate_card SET premium = 130,total = 130 WHERE `coverage` = 'Equipment Liability Coverage' and product = 'Individual Professional Liability' and is_upgrade = 0 and start_date = '2020-02-01' and end_date = '2020-02-29';
UPDATE premium_rate_card SET premium = 104,total = 104 WHERE `coverage` = 'Equipment Liability Coverage' and product = 'Individual Professional Liability' and is_upgrade = 0 and start_date = '2020-03-01' and end_date = '2020-03-31';
UPDATE premium_rate_card SET premium = 78,total = 78 WHERE `coverage` = 'Equipment Liability Coverage' and product = 'Individual Professional Liability' and is_upgrade = 0 and start_date = '2020-04-01' and end_date = '2020-04-30';
UPDATE premium_rate_card SET premium = 52,total = 52 WHERE `coverage` = 'Equipment Liability Coverage' and product = 'Individual Professional Liability' and is_upgrade = 0 and start_date = '2020-05-01' and end_date = '2020-05-31';
UPDATE premium_rate_card SET premium = 26,total = 26 WHERE `coverage` = 'Equipment Liability Coverage' and product = 'Individual Professional Liability' and is_upgrade = 0 and start_date = '2020-06-01' and end_date = '2020-06-30';

UPDATE premium_rate_card SET start_date = DATE_SUB(start_date,INTERVAL 1 Year) WHERE `coverage` = '9M Excess' and product = 'Individual Professional Liability' and is_upgrade = 1 and premium = 3068 and `year` = 2020;
UPDATE premium_rate_card SET end_date = DATE_SUB(end_date,INTERVAL 1 Year) WHERE `coverage` = '9M Excess' and product = 'Individual Professional Liability' and is_upgrade = 1 and premium = 255 and `year` = 2020;

UPDATE premium_rate_card SET premium = 312,total = 312 WHERE `coverage` = 'Equipment Liability Coverage' and product = 'Individual Professional Liability' and is_upgrade = 0 and start_date = '2019-06-30' and end_date = '2019-07-31';
