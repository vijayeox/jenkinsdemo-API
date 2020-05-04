DELETE FROM premium_rate_card WHERE premium = 0 AND previous_key = 'divemaster' AND premium_rate_card.product IN ('Individual Professional Liability') AND premium_rate_card.is_upgrade=1 key <> 'divemaster';

DELETE FROM premium_rate_card WHERE premium = 0 AND previous_key = 'assistantInstructor' AND premium_rate_card.product IN ('Individual Professional Liability') AND premium_rate_card.is_upgrade=1 key <> 'assistantInstructor';

DELETE FROM premium_rate_card WHERE premium = 0 AND previous_key = 'freediveInstructor' AND premium_rate_card.product IN ('Individual Professional Liability') AND premium_rate_card.is_upgrade=1 key <> 'freediveInstructor';

DELETE FROM premium_rate_card WHERE previous_key = 'assistantInstructor' AND premium_rate_card.product IN ('Individual Professional Liability') AND premium_rate_card.is_upgrade=1 key = 'instructor' and start_date = '2020-05-01 00:00:00';

INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,ox_app_org_id,coverage_category,`year`) VALUES 
('Individual Professional Liability','Instructor','instructor','2020-05-01 00:00:00','2020-05-31 00:00:00',42.00000,'VALUE',NULL,NULL,NULL,1,'assistantInstructor',0,'',2020)
;