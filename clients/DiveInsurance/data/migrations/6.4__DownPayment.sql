ALTER TABLE premium_rate_card ADD downpayment DECIMAL(8,2) NULL;
ALTER TABLE premium_rate_card ADD installment_count INT NULL;
ALTER TABLE premium_rate_card ADD installment_amount DECIMAL(8,2) NULL;

DELETE FROM premium_rate_card where product IN ('Individual Professional Liability') AND start_date IN ('2020-06-30 00:00:00') AND is_upgrade IN (0);

INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,`year`,downpayment,installment_count,installment_amount) VALUES 
('Individual Professional Liability','0 Excess','excessLiabilityCoverageDeclined','2020-06-30 00:00:00','2020-07-31 00:00:00',0.00000,'VALUE',0.00,0.00,NULL,0,'0','EXCESS_LIABILITY',2020,0.00,3,0.00)
,('Individual Professional Liability','1M Excess','excessLiabilityCoverage1000000','2020-06-30 00:00:00','2020-07-31 00:00:00',633.00000,'VALUE',0.00,0.00,633.00,0,'1','EXCESS_LIABILITY',2020,180.00,3,151.00)
,('Individual Professional Liability','2M Excess','excessLiabilityCoverage2000000','2020-06-30 00:00:00','2020-07-31 00:00:00',1269.00000,'VALUE',0.00,0.00,1269.00,0,'2','EXCESS_LIABILITY',2020,357.00,3,304.00)
,('Individual Professional Liability','3M Excess','excessLiabilityCoverage3000000','2020-06-30 00:00:00','2020-07-31 00:00:00',1648.00000,'VALUE',0.00,0.00,1648.00,0,'3','EXCESS_LIABILITY',2020,463.00,3,395.00)
,('Individual Professional Liability','4M Excess','excessLiabilityCoverage4000000','2020-06-30 00:00:00','2020-07-31 00:00:00',2069.00000,'VALUE',0.00,0.00,2069.00,0,'4','EXCESS_LIABILITY',2020,581.00,3,496.00)
,('Individual Professional Liability','9M Excess','excessLiabilityCoverage9000000','2020-06-30 00:00:00','2020-07-31 00:00:00',4620.00000,'VALUE',0.00,0.00,4620.00,0,'5','EXCESS_LIABILITY',2020,1299.00,3,1107.00)
,('Individual Professional Liability','Assistant Instructor','assistantInstructor','2020-06-30 00:00:00','2020-07-31 00:00:00',471.00000,'VALUE',0.00,0.00,471.00,0,NULL,'INSURED_STATUS',2020,132.00,3,113.00)
,('Individual Professional Liability','Assistant Only','divemasterAssistantInstructorAssistingOnly','2020-06-30 00:00:00','2020-07-31 00:00:00',326.00000,'VALUE',0.00,0.00,326.00,0,NULL,'INSURED_STATUS',2020,92.00,3,78.00)
,('Individual Professional Liability','Cylinder Inspector','cylinderInspector','2020-06-30 00:00:00','2020-07-31 00:00:00',306.00000,'VALUE',0.00,0.00,306.00,0,NULL,'CYLINDER',2020,87.00,3,73.00)
,('Individual Professional Liability','Cylinder Inspector & Instructor','cylinderInspectorAndInstructor','2020-06-30 00:00:00','2020-07-31 00:00:00',381.00000,'VALUE',0.00,0.00,381.00,0,NULL,'CYLINDER',2020,108.00,3,91.00)
;
INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,`year`,downpayment,installment_count,installment_amount) VALUES 
('Individual Professional Liability','Cylinder Instructor','cylinderInstructor','2020-06-30 00:00:00','2020-07-31 00:00:00',160.00000,'VALUE',0.00,0.00,160.00,0,NULL,'CYLINDER',2020,46.00,3,38.00)
,('Individual Professional Liability','Dive Master','divemaster','2020-06-30 00:00:00','2020-07-31 00:00:00',471.00000,'VALUE',0.00,0.00,471.00,0,NULL,'INSURED_STATUS',2020,132.00,3,113.00)
,('Individual Professional Liability','Equipment Liability Coverage','equipmentLiabilityCoverage','2020-06-30 00:00:00','2020-07-31 00:00:00',406.00000,'VALUE',0.00,0.00,406.00,0,NULL,'EQUIPMENT_LIABILITY',2020,115.00,3,97.00)
,('Individual Professional Liability','Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined','2020-06-30 00:00:00','2020-07-31 00:00:00',0.00000,'VALUE',0.00,0.00,0.00,0,NULL,'EQUIPMENT_LIABILITY',2020,0.00,3,0.00)
,('Individual Professional Liability','Free Diver Instructor','freediveInstructor','2020-06-30 00:00:00','2020-07-31 00:00:00',471.00000,'VALUE',0.00,0.00,471.00,0,NULL,'INSURED_STATUS',2020,132.00,3,113.00)
,('Individual Professional Liability','Instructor','instructor','2020-06-30 00:00:00','2020-07-31 00:00:00',735.00000,'VALUE',0.00,0.00,735.00,0,NULL,'INSURED_STATUS',2020,198.00,3,179.00)
,('Individual Professional Liability','International AI','internationalAssistantInstructor','2020-06-30 00:00:00','2020-07-31 00:00:00',310.00000,'VALUE',0.00,0.00,310.00,0,NULL,'INSURED_STATUS',2020,79.00,3,77.00)
,('Individual Professional Liability','International AO','internationalDivemasterAssistantInstructorAssistingOnly','2020-06-30 00:00:00','2020-07-31 00:00:00',192.00000,'VALUE',0.00,0.00,192.00,0,NULL,'INSURED_STATUS',2020,48.00,3,48.00)
,('Individual Professional Liability','International DM','internationalDivemaster','2020-06-30 00:00:00','2020-07-31 00:00:00',310.00000,'VALUE',0.00,0.00,310.00,0,NULL,'INSURED_STATUS',2020,79.00,3,77.00)
,('Individual Professional Liability','International Instructor','internationalInstructor','2020-06-30 00:00:00','2020-07-31 00:00:00',471.00000,'VALUE',0.00,0.00,471.00,0,NULL,'INSURED_STATUS',2020,120.00,3,117.00)
;
INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,`year`,downpayment,installment_count,installment_amount) VALUES 
('Individual Professional Liability','International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor','2020-06-30 00:00:00','2020-07-31 00:00:00',310.00000,'VALUE',0.00,0.00,310.00,0,NULL,'INSURED_STATUS',2020,79.00,3,77.00)
,('Individual Professional Liability','Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2020-06-30 00:00:00','2020-07-31 00:00:00',471.00000,'VALUE',0.00,0.00,471.00,0,NULL,'INSURED_STATUS',2020,132.00,3,113.00)
,('Individual Professional Liability','Retired Instructor','retiredInstructor','2020-06-30 00:00:00','2020-07-31 00:00:00',471.00000,'VALUE',0.00,0.00,471.00,0,NULL,'INSURED_STATUS',2020,132.00,3,113.00)
,('Individual Professional Liability','Scuba Fit Instructor','scubaFitInstructor','2020-06-30 00:00:00','2020-07-31 00:00:00',78.00000,'VALUE',0.00,0.00,78.00,0,NULL,'SCUBA_FIT',2020,24.00,3,18.00)
,('Individual Professional Liability','ScubaFit – Declined','scubaFitInstructorDeclined','2020-06-30 00:00:00','2020-07-31 00:00:00',0.00000,'VALUE',0.00,0.00,0.00,0,NULL,'SCUBA_FIT',2020,0.00,0,0.00)
,('Individual Professional Liability','Swim Instructor','swimInstructor','2020-06-30 00:00:00','2020-07-31 00:00:00',407.00000,'VALUE',0.00,0.00,407.00,0,NULL,'INSURED_STATUS',2020,116.00,3,97.00)
,('Individual Professional Liability','TecRec Endorsement','withTecRecEndorsementForSelectionAbove','2020-06-30 00:00:00','2020-07-31 00:00:00',0.00000,'VALUE',0.00,0.00,0.00,0,NULL,'TEC_REC',2020,0.00,0,0.00)
,('Individual Professional Liability','TecRec Endorsement - Declined','tecRecDeclined','2020-06-30 00:00:00','2020-07-31 00:00:00',0.00000,'VALUE',0.00,0.00,0.00,0,NULL,'TEC_REC',2020,0.00,0,0.00)
;

DELETE FROM premium_rate_card where product IN ('Individual Professional Liability') AND start_date IN ('2020-08-01 00:00:00') AND is_upgrade IN (0);

INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,`year`,downpayment,installment_count,installment_amount) VALUES 
('Individual Professional Liability','Cylinder Inspector','cylinderInspector','2020-08-01 00:00:00','2020-08-31 00:00:00',281.00000,'VALUE',0.00,0.00,281.00,0,NULL,'CYLINDER',2020,71.0,3,70.0)
,('Individual Professional Liability','Cylinder Inspector & Instructor','cylinderInspectorAndInstructor','2020-08-01 00:00:00','2020-08-31 00:00:00',349.00000,'VALUE',0.00,0.00,349.00,0,NULL,'CYLINDER',2020,88.0,3,87.0)
,('Individual Professional Liability','Cylinder Instructor','cylinderInstructor','2020-08-01 00:00:00','2020-08-31 00:00:00',147.00000,'VALUE',0.00,0.00,147.00,0,NULL,'CYLINDER',2020,39.0,3,36.0)
,('Individual Professional Liability','Equipment Liability Coverage','equipmentLiabilityCoverage','2020-08-01 00:00:00','2020-08-31 00:00:00',372.00000,'VALUE',0.00,0.00,372.00,0,NULL,'EQUIPMENT_LIABILITY',2020,105.0,3,89.0)
,('Individual Professional Liability','Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined','2020-08-01 00:00:00','2020-08-31 00:00:00',0.00000,'VALUE',0.00,0.00,0.00,0,NULL,'EQUIPMENT_LIABILITY',2020,0.0,3,0.0)
,('Individual Professional Liability','0 Excess','excessLiabilityCoverageDeclined','2020-08-01 00:00:00','2020-08-31 00:00:00',0.00000,'VALUE',0.00,0.00,NULL,0,'0','EXCESS_LIABILITY',2020,0.0,3,0.0)
,('Individual Professional Liability','1M Excess','excessLiabilityCoverage1000000','2020-08-01 00:00:00','2020-08-31 00:00:00',581.00000,'VALUE',0.00,0.00,581.00,0,'1','EXCESS_LIABILITY',2020,164.0,3,139.0)
,('Individual Professional Liability','2M Excess','excessLiabilityCoverage2000000','2020-08-01 00:00:00','2020-08-31 00:00:00',1164.00000,'VALUE',0.00,0.00,1164.00,0,'2','EXCESS_LIABILITY',2020,327.0,3,279.0)
,('Individual Professional Liability','3M Excess','excessLiabilityCoverage3000000','2020-08-01 00:00:00','2020-08-31 00:00:00',1511.00000,'VALUE',0.00,0.00,1511.00,0,'3','EXCESS_LIABILITY',2020,422.0,3,363.0)
,('Individual Professional Liability','4M Excess','excessLiabilityCoverage4000000','2020-08-01 00:00:00','2020-08-31 00:00:00',1897.00000,'VALUE',0.00,0.00,1897.00,0,'4','EXCESS_LIABILITY',2020,532.0,3,455.0)
;
INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,`year`,downpayment,installment_count,installment_amount) VALUES 
('Individual Professional Liability','9M Excess','excessLiabilityCoverage9000000','2020-08-01 00:00:00','2020-08-31 00:00:00',4236.00000,'VALUE',0.00,0.00,4236.00,0,'5','EXCESS_LIABILITY',2020, 1188,3,1016)
,('Individual Professional Liability','Assistant Instructor','assistantInstructor','2020-08-01 00:00:00','2020-08-31 00:00:00',443.00000,'VALUE',0.00,0.00,443.00,0,NULL,'INSURED_STATUS',2020,125.0,3,106.0)
,('Individual Professional Liability','Assistant Only','divemasterAssistantInstructorAssistingOnly','2020-08-01 00:00:00','2020-08-31 00:00:00',311.00000,'VALUE',0.00,0.00,311.00,0,NULL,'INSURED_STATUS',2020,86.0,3,75.0)
,('Individual Professional Liability','Dive Master','divemaster','2020-08-01 00:00:00','2020-08-31 00:00:00',443.00000,'VALUE',0.00,0.00,443.00,0,NULL,'INSURED_STATUS',2020,125.0,3,106.0)
,('Individual Professional Liability','Free Diver Instructor','freediveInstructor','2020-08-01 00:00:00','2020-08-31 00:00:00',443.00000,'VALUE',0.00,0.00,443.00,0,NULL,'INSURED_STATUS',2020,125.0,3,106.0)
,('Individual Professional Liability','Instructor','instructor','2020-08-01 00:00:00','2020-08-31 00:00:00',686.00000,'VALUE',0.00,0.00,686.00,0,NULL,'INSURED_STATUS',2020,194.0,3,164.0)
,('Individual Professional Liability','International AI','internationalAssistantInstructor','2020-08-01 00:00:00','2020-08-31 00:00:00',287.00000,'VALUE',0.00,0.00,287.00,0,NULL,'INSURED_STATUS',2020,74.0,3,71.0)
,('Individual Professional Liability','International AO','internationalDivemasterAssistantInstructorAssistingOnly','2020-08-01 00:00:00','2020-08-31 00:00:00',179.00000,'VALUE',0.00,0.00,179.00,0,NULL,'INSURED_STATUS',2020,47.0,3,44.0)
,('Individual Professional Liability','International DM','internationalDivemaster','2020-08-01 00:00:00','2020-08-31 00:00:00',287.00000,'VALUE',0.00,0.00,287.00,0,NULL,'INSURED_STATUS',2020,74.0,3,71.0)
,('Individual Professional Liability','International Instructor','internationalInstructor','2020-08-01 00:00:00','2020-08-31 00:00:00',434.00000,'VALUE',0.00,0.00,434.00,0,NULL,'INSURED_STATUS',2020,110.0,3,108.0)
;
INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,`year`,downpayment,installment_count,installment_amount) VALUES 
('Individual Professional Liability','International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor','2020-08-01 00:00:00','2020-07-31 00:00:00',287.00000,'VALUE',0.00,0.00,287.00,0,NULL,'INSURED_STATUS',2020,74.0,3,71.0)
,('Individual Professional Liability','Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2020-08-01 00:00:00','2020-08-31 00:00:00',443.00000,'VALUE',0.00,0.00,443.00,0,NULL,'INSURED_STATUS',2020,125.0,3,106.0)
,('Individual Professional Liability','Retired Instructor','retiredInstructor','2020-08-01 00:00:00','2020-08-31 00:00:00',443.00000,'VALUE',0.00,0.00,443.00,0,NULL,'INSURED_STATUS',2020,125.0,3,106.0)
,('Individual Professional Liability','Swim Instructor','swimInstructor','2020-08-01 00:00:00','2020-08-31 00:00:00',375.00000,'VALUE',0.00,0.00,375.00,0,NULL,'INSURED_STATUS',2020,105.0,3,90.0)
,('Individual Professional Liability','Scuba Fit Instructor','scubaFitInstructor','2020-08-01 00:00:00','2020-08-31 00:00:00',78.00000,'VALUE',0.00,0.00,78.00,0,NULL,'SCUBA_FIT',2020,24.0,3,18.0)
,('Individual Professional Liability','ScubaFit – Declined','scubaFitInstructorDeclined','2020-08-01 00:00:00','2020-08-31 00:00:00',0.00000,'VALUE',0.00,0.00,0.00,0,NULL,'SCUBA_FIT',2020,0.0,3,0.0)
,('Individual Professional Liability','TecRec Endorsement','withTecRecEndorsementForSelectionAbove','2020-08-01 00:00:00','2020-08-31 00:00:00',0.00000,'VALUE',0.00,0.00,0.00,0,NULL,'TEC_REC',2020,0.0,3,0.0)
,('Individual Professional Liability','TecRec Endorsement - Declined','tecRecDeclined','2020-08-01 00:00:00','2020-08-31 00:00:00',0.00000,'VALUE',0.00,0.00,0.00,0,NULL,'TEC_REC',2020,0.0,3,0.0)
;

DELETE FROM premium_rate_card where start_date IN ('2020-09-01 00:00:00') AND product IN ('Individual Professional Liability') AND is_upgrade IN (0);

INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,`year`,downpayment,installment_count,installment_amount) VALUES 
('Individual Professional Liability','Cylinder Inspector','cylinderInspector','2020-09-01 00:00:00','2020-09-30 00:00:00',256.00000,'VALUE',0.00,0.00,256.00,0,NULL,'CYLINDER',2020,94.0,2,81.0)
,('Individual Professional Liability','Cylinder Inspector & Instructor','cylinderInspectorAndInstructor','2020-09-01 00:00:00','2020-09-30 00:00:00',318.00000,'VALUE',0.00,0.00,318.00,0,NULL,'CYLINDER',2020,114.0,2,102.0)
,('Individual Professional Liability','Cylinder Instructor','cylinderInstructor','2020-09-01 00:00:00','2020-09-30 00:00:00',134.00000,'VALUE',0.00,0.00,134.00,0,NULL,'CYLINDER',2020,48.0,2,43.0)
,('Individual Professional Liability','Equipment Liability Coverage','equipmentLiabilityCoverage','2020-09-01 00:00:00','2020-09-30 00:00:00',339.00000,'VALUE',0.00,0.00,339.00,0,NULL,'EQUIPMENT_LIABILITY',2020,123.0,2,108.0)
,('Individual Professional Liability','Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined','2020-09-01 00:00:00','2020-09-30 00:00:00',0.00000,'VALUE',0.00,0.00,0.00,0,NULL,'EQUIPMENT_LIABILITY',2020,0.0,2,0.0)
,('Individual Professional Liability','0 Excess','excessLiabilityCoverageDeclined','2020-09-01 00:00:00','2020-09-30 00:00:00',0.00000,'VALUE',0.00,0.00,NULL,0,'0','EXCESS_LIABILITY',2020,0.0,2,0.0)
,('Individual Professional Liability','1M Excess','excessLiabilityCoverage1000000','2020-09-01 00:00:00','2020-09-30 00:00:00',528.00000,'VALUE',0.00,0.00,528.00,0,'1','EXCESS_LIABILITY',2020,190.0,2,169.0)
,('Individual Professional Liability','2M Excess','excessLiabilityCoverage2000000','2020-09-01 00:00:00','2020-09-30 00:00:00',1059.00000,'VALUE',0.00,0.00,1059.00,0,'2','EXCESS_LIABILITY',2020,381.0,2,339.0)
,('Individual Professional Liability','3M Excess','excessLiabilityCoverage3000000','2020-09-01 00:00:00','2020-09-30 00:00:00',1374.00000,'VALUE',0.00,0.00,1374.00,0,'3','EXCESS_LIABILITY',2020,496.0,2,439.0)
,('Individual Professional Liability','4M Excess','excessLiabilityCoverage4000000','2020-09-01 00:00:00','2020-09-30 00:00:00',1725.00000,'VALUE',0.00,0.00,1725.00,0,'4','EXCESS_LIABILITY',2020,621.0,2,552.0)
;
INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,`year`,downpayment,installment_count,installment_amount) VALUES 
('Individual Professional Liability','9M Excess','excessLiabilityCoverage9000000','2020-09-01 00:00:00','2020-09-30 00:00:00',3851.00000,'VALUE',0.00,0.00,3851.00,0,'5','EXCESS_LIABILITY',2020,1389.0,2,1231.0)
,('Individual Professional Liability','Assistant Instructor','assistantInstructor','2020-09-01 00:00:00','2020-09-30 00:00:00',406.00000,'VALUE',0.00,0.00,406.00,0,NULL,'INSURED_STATUS',2020,146.0,2,130.0)
,('Individual Professional Liability','Assistant Only','divemasterAssistantInstructorAssistingOnly','2020-09-01 00:00:00','2020-09-30 00:00:00',285.00000,'VALUE',0.00,0.00,285.00,0,NULL,'INSURED_STATUS',2020,103.0,2,91.0)
,('Individual Professional Liability','Dive Master','divemaster','2020-09-01 00:00:00','2020-09-30 00:00:00',406.00000,'VALUE',0.00,0.00,406.00,0,NULL,'INSURED_STATUS',2020,146.0,2,130.0)
,('Individual Professional Liability','Free Diver Instructor','freediveInstructor','2020-09-01 00:00:00','2020-09-30 00:00:00',406.00000,'VALUE',0.00,0.00,406.00,0,NULL,'INSURED_STATUS',2020,146.0,2,130.0)
,('Individual Professional Liability','Instructor','instructor','2020-09-01 00:00:00','2020-09-30 00:00:00',626.00000,'VALUE',0.00,0.00,626.00,0,NULL,'INSURED_STATUS',2020,226.0,2,200.0)
,('Individual Professional Liability','International AI','internationalAssistantInstructor','2020-09-01 00:00:00','2020-09-30 00:00:00',263.00000,'VALUE',0.00,0.00,263.00,0,NULL,'INSURED_STATUS',2020,89.0,2,87.0)
,('Individual Professional Liability','International AO','internationalDivemasterAssistantInstructorAssistingOnly','2020-09-01 00:00:00','2020-09-30 00:00:00',165.00000,'VALUE',0.00,0.00,165.00,0,NULL,'INSURED_STATUS',2020,57.0,2,54.0)
,('Individual Professional Liability','International DM','internationalDivemaster','2020-09-01 00:00:00','2020-09-30 00:00:00',263.00000,'VALUE',0.00,0.00,263.00,0,NULL,'INSURED_STATUS',2020,89.0,2,87.0)
,('Individual Professional Liability','International Instructor','internationalInstructor','2020-09-01 00:00:00','2020-09-30 00:00:00',397.00000,'VALUE',0.00,0.00,397.00,0,NULL,'INSURED_STATUS',2020,137.0,2,130.0)
;
INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,`year`,downpayment,installment_count,installment_amount) VALUES 
('Individual Professional Liability','International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor','2020-09-01 00:00:00','2020-09-30 00:00:00',263.00000,'VALUE',0.00,0.00,263.00,0,NULL,'INSURED_STATUS',2020,89.0,2,87.0)
,('Individual Professional Liability','Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2020-09-01 00:00:00','2020-09-30 00:00:00',406.00000,'VALUE',0.00,0.00,406.00,0,NULL,'INSURED_STATUS',2020,146.0,2,130.0)
,('Individual Professional Liability','Retired Instructor','retiredInstructor','2020-09-01 00:00:00','2020-09-30 00:00:00',406.00000,'VALUE',0.00,0.00,406.00,0,NULL,'INSURED_STATUS',2020,146.0,2,130.0)
,('Individual Professional Liability','Swim Instructor','swimInstructor','2020-09-01 00:00:00','2020-09-30 00:00:00',342.00000,'VALUE',0.00,0.00,342.00,0,NULL,'INSURED_STATUS',2020,124.0,2,109.0)
,('Individual Professional Liability','Scuba Fit Instructor','scubaFitInstructor','2020-09-01 00:00:00','2020-09-30 00:00:00',78.00000,'VALUE',0.00,0.00,78,0,NULL,'SCUBA_FIT',2020,28.0,2,25.0)
,('Individual Professional Liability','ScubaFit – Declined','scubaFitInstructorDeclined','2020-09-01 00:00:00','2020-09-30 00:00:00',0.00000,'VALUE',0.00,0.00,0.00,0,NULL,'SCUBA_FIT',2020,0.0,2,0.0)
,('Individual Professional Liability','TecRec Endorsement','withTecRecEndorsementForSelectionAbove','2020-09-01 00:00:00','2020-09-30 00:00:00',0.00000,'VALUE',0.00,0.00,0.00,0,NULL,'TEC_REC',2020,0.0,2,0.0)
,('Individual Professional Liability','TecRec Endorsement - Declined','tecRecDeclined','2020-09-01 00:00:00','2020-09-30 00:00:00',0.00000,'VALUE',0.00,0.00,0.00,0,NULL,'TEC_REC',2020,0.0,2,0.0)
;

DELETE FROM premium_rate_card where start_date IN ('2020-10-01 00:00:00') AND product IN ('Individual Professional Liability') AND is_upgrade IN (0);

INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,`year`,downpayment,installment_count,installment_amount) VALUES 
('Individual Professional Liability','Cylinder Inspector','cylinderInspector','2020-10-01 00:00:00','2020-10-31 00:00:00',229.00000,'VALUE',0.00,0.00,229.00,0,NULL,'CYLINDER',2020,81.0,2,74.0)
,('Individual Professional Liability','Cylinder Inspector & Instructor','cylinderInspectorAndInstructor','2020-10-01 00:00:00','2020-10-31 00:00:00',286.00000,'VALUE',0.00,0.00,286.00,0,NULL,'CYLINDER',2020,104.0,2,91.0)
,('Individual Professional Liability','Cylinder Instructor','cylinderInstructor','2020-10-01 00:00:00','2020-10-31 00:00:00',120.00000,'VALUE',0.00,0.00,120.00,0,NULL,'CYLINDER',2020,44.0,2,38.0)
,('Individual Professional Liability','Equipment Liability Coverage','equipmentLiabilityCoverage','2020-10-01 00:00:00','2020-10-31 00:00:00',305.00000,'VALUE',0.00,0.00,305.00,0,NULL,'EQUIPMENT_LIABILITY',2020,109.0,2,98.0)
,('Individual Professional Liability','Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined','2020-10-01 00:00:00','2020-10-31 00:00:00',0.00000,'VALUE',0.00,0.00,0.00,0,NULL,'EQUIPMENT_LIABILITY',2020,0.0,2,0.0)
,('Individual Professional Liability','0 Excess','excessLiabilityCoverageDeclined','2020-10-01 00:00:00','2020-10-31 00:00:00',0.00000,'VALUE',0.00,0.00,NULL,0,'0','EXCESS_LIABILITY',2020,0.0,2,0.0)
,('Individual Professional Liability','1M Excess','excessLiabilityCoverage1000000','2020-10-01 00:00:00','2020-10-31 00:00:00',476.00000,'VALUE',0.00,0.00,476.00,0,'1','EXCESS_LIABILITY',2020,172.0,2,152.0)
,('Individual Professional Liability','2M Excess','excessLiabilityCoverage2000000','2020-10-01 00:00:00','2020-10-31 00:00:00',952.00000,'VALUE',0.00,0.00,952.00,0,'2','EXCESS_LIABILITY',2020,342.0,2,305.0)
,('Individual Professional Liability','3M Excess','excessLiabilityCoverage3000000','2020-10-01 00:00:00','2020-10-31 00:00:00',1236.00000,'VALUE',0.00,0.00,1236.00,0,'3','EXCESS_LIABILITY',2020,446.0,2,395.0)
,('Individual Professional Liability','4M Excess','excessLiabilityCoverage4000000','2020-10-01 00:00:00','2020-10-31 00:00:00',1725.00000,'VALUE',0.00,0.00,1725.00,0,'4','EXCESS_LIABILITY',2020,560.0,2,496.0)
;
INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,`year`,downpayment,installment_count,installment_amount) VALUES 
('Individual Professional Liability','9M Excess','excessLiabilityCoverage9000000','2020-10-01 00:00:00','2020-10-31 00:00:00',3466.00000,'VALUE',0.00,0.00,3466.00,0,'5','EXCESS_LIABILITY',2020,1250.0,2,1108.0)
,('Individual Professional Liability','Assistant Instructor','assistantInstructor','2020-10-01 00:00:00','2020-10-31 00:00:00',367.00000,'VALUE',0.00,0.00,367.00,0,NULL,'INSURED_STATUS',2020,131.0,2,118.0)
,('Individual Professional Liability','Assistant Only','divemasterAssistantInstructorAssistingOnly','2020-10-01 00:00:00','2020-10-31 00:00:00',259.00000,'VALUE',0.00,0.00,259.00,0,NULL,'INSURED_STATUS',2020,93.0,2,83.0)
,('Individual Professional Liability','Dive Master','divemaster','2020-10-01 00:00:00','2020-10-31 00:00:00',367.00000,'VALUE',0.00,0.00,367.00,0,NULL,'INSURED_STATUS',2020,131.0,2,118.0)
,('Individual Professional Liability','Free Diver Instructor','freediveInstructor','2020-10-01 00:00:00','2020-10-31 00:00:00',367.00000,'VALUE',0.00,0.00,367.00,0,NULL,'INSURED_STATUS',2020,131.0,2,118.0)
,('Individual Professional Liability','Instructor','instructor','2020-10-01 00:00:00','2020-10-31 00:00:00',566.00000,'VALUE',0.00,0.00,566.00,0,NULL,'INSURED_STATUS',2020,202.0,2,182.0)
,('Individual Professional Liability','International AI','internationalAssistantInstructor','2020-10-01 00:00:00','2020-10-31 00:00:00',239.00000,'VALUE',0.00,0.00,239.00,0,NULL,'INSURED_STATUS',2020,81.0,2,74.0)
,('Individual Professional Liability','International AO','internationalDivemasterAssistantInstructorAssistingOnly','2020-10-01 00:00:00','2020-10-31 00:00:00',151.00000,'VALUE',0.00,0.00,151.00,0,NULL,'INSURED_STATUS',2020,51.0,2,50.0)
,('Individual Professional Liability','International DM','internationalDivemaster','2020-10-01 00:00:00','2020-10-31 00:00:00',239.00000,'VALUE',0.00,0.00,239.00,0,NULL,'INSURED_STATUS',2020,81.0,2,74.0)
,('Individual Professional Liability','International Instructor','internationalInstructor','2020-10-01 00:00:00','2020-10-31 00:00:00',360.00000,'VALUE',0.00,0.00,360.00,0,NULL,'INSURED_STATUS',2020,120.0,2,120.0)
;
INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,`year`,downpayment,installment_count,installment_amount) VALUES 
('Individual Professional Liability','International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor','2020-10-01 00:00:00','2020-10-31 00:00:00',239.00000,'VALUE',0.00,0.00,239.00,0,NULL,'INSURED_STATUS',2020,81.0,2,79.0)
,('Individual Professional Liability','Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2020-10-01 00:00:00','2020-10-31 00:00:00',367.00000,'VALUE',0.00,0.00,367.00,0,NULL,'INSURED_STATUS',2020,131.0,2,118.0)
,('Individual Professional Liability','Retired Instructor','retiredInstructor','2020-10-01 00:00:00','2020-10-31 00:00:00',367.00000,'VALUE',0.00,0.00,367.00,0,NULL,'INSURED_STATUS',2020,131.0,2,118.0)
,('Individual Professional Liability','Swim Instructor','swimInstructor','2020-10-01 00:00:00','2020-10-31 00:00:00',308.00000,'VALUE',0.00,0.00,308.00,0,NULL,'INSURED_STATUS',2020,112.0,2,98.0)
,('Individual Professional Liability','Scuba Fit Instructor','scubaFitInstructor','2020-10-01 00:00:00','2020-10-31 00:00:00',78.00000,'VALUE',0.00,0.00,78.00,0,NULL,'SCUBA_FIT',2020,28.0,2,25.0)
,('Individual Professional Liability','ScubaFit – Declined','scubaFitInstructorDeclined','2020-10-01 00:00:00','2020-10-31 00:00:00',0.00000,'VALUE',0.00,0.00,0.00,0,NULL,'SCUBA_FIT',2020,0.0,2,0.0)
,('Individual Professional Liability','TecRec Endorsement','withTecRecEndorsementForSelectionAbove','2020-10-01 00:00:00','2020-10-31 00:00:00',0.00000,'VALUE',0.00,0.00,0.00,0,NULL,'TEC_REC',2020,0.0,2,0.0)
,('Individual Professional Liability','TecRec Endorsement - Declined','tecRecDeclined','2020-10-01 00:00:00','2020-10-31 00:00:00',0.00000,'VALUE',0.00,0.00,0.00,0,NULL,'TEC_REC',2020,0.0,2,0.0)
;

DELETE FROM premium_rate_card where start_date IN ('2020-11-01 00:00:00') AND product IN ('Individual Professional Liability') AND is_upgrade IN (0);

INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,`year`,downpayment,installment_count,installment_amount) VALUES 
('Individual Professional Liability','Cylinder Inspector','cylinderInspector','2020-11-01 00:00:00','2020-11-30 00:00:00',204.00000,'VALUE',0.00,0.00,204.00,0,NULL,'CYLINDER',2020,74.0,2,65.0)
,('Individual Professional Liability','Cylinder Inspector & Instructor','cylinderInspectorAndInstructor','2020-11-01 00:00:00','2020-11-30 00:00:00',254.00000,'VALUE',0.00,0.00,254.00,0,NULL,'CYLINDER',2020,92.0,2,81.0)
,('Individual Professional Liability','Cylinder Instructor','cylinderInstructor','2020-11-01 00:00:00','2020-11-30 00:00:00',106.00000,'VALUE',0.00,0.00,106.00,0,NULL,'CYLINDER',2020,38.0,2,34.0)
,('Individual Professional Liability','Equipment Liability Coverage','equipmentLiabilityCoverage','2020-11-01 00:00:00','2020-11-30 00:00:00',271.00000,'VALUE',0.00,0.00,271.00,0,NULL,'EQUIPMENT_LIABILITY',2020,97.0,2,87.0)
,('Individual Professional Liability','Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined','2020-11-01 00:00:00','2020-11-30 00:00:00',0.00000,'VALUE',0.00,0.00,0.00,0,NULL,'EQUIPMENT_LIABILITY',2020,0.0,2,0.0)
,('Individual Professional Liability','0 Excess','excessLiabilityCoverageDeclined','2020-11-01 00:00:00','2020-11-30 00:00:00',0.00000,'VALUE',0.00,0.00,0.0,0,'0','EXCESS_LIABILITY',2020,0.0,2,0.0)
,('Individual Professional Liability','1M Excess','excessLiabilityCoverage1000000','2020-11-01 00:00:00','2020-11-30 00:00:00',422.00000,'VALUE',0.00,0.00,422.00,0,'1','EXCESS_LIABILITY',2020,152.0,2,135.0)
,('Individual Professional Liability','2M Excess','excessLiabilityCoverage2000000','2020-11-01 00:00:00','2020-11-30 00:00:00',847.00000,'VALUE',0.00,0.00,847.00,0,'2','EXCESS_LIABILITY',2020,305.0,2,271.0)
,('Individual Professional Liability','3M Excess','excessLiabilityCoverage3000000','2020-11-01 00:00:00','2020-11-30 00:00:00',1099.00000,'VALUE',0.00,0.00,1099.00,0,'3','EXCESS_LIABILITY',2020,395.0,2,352.0)
,('Individual Professional Liability','4M Excess','excessLiabilityCoverage4000000','2020-11-01 00:00:00','2020-11-30 00:00:00',1380.00000,'VALUE',0.00,0.00,1380.00,0,'4','EXCESS_LIABILITY',2020,498.0,2,441.0)
;
INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,`year`,downpayment,installment_count,installment_amount) VALUES 
('Individual Professional Liability','9M Excess','excessLiabilityCoverage9000000','2020-11-01 00:00:00','2020-11-30 00:00:00',3081.00000,'VALUE',0.00,0.00,3081.00,0,'5','EXCESS_LIABILITY',2020,1111.0,2,985.0)
,('Individual Professional Liability','Assistant Instructor','assistantInstructor','2020-11-01 00:00:00','2020-11-30 00:00:00',330.00000,'VALUE',0.00,0.00,330.00,0,NULL,'INSURED_STATUS',2020,118.0,2,106.0)
,('Individual Professional Liability','Assistant Only','divemasterAssistantInstructorAssistingOnly','2020-11-01 00:00:00','2020-11-30 00:00:00',233.00000,'VALUE',0.00,0.00,233.00,0,NULL,'INSURED_STATUS',2020,83.0,2,75.0)
,('Individual Professional Liability','Dive Master','divemaster','2020-11-01 00:00:00','2020-11-30 00:00:00',330.00000,'VALUE',0.00,0.00,330.00,0,NULL,'INSURED_STATUS',2020,118.0,2,106.0)
,('Individual Professional Liability','Free Diver Instructor','freediveInstructor','2020-11-01 00:00:00','2020-11-30 00:00:00',330.00000,'VALUE',0.00,0.00,330.00,0,NULL,'INSURED_STATUS',2020,118.0,2,106.0)
,('Individual Professional Liability','Instructor','instructor','2020-11-01 00:00:00','2020-11-30 00:00:00',506.00000,'VALUE',0.00,0.00,506.00,0,NULL,'INSURED_STATUS',2020,182.0,2,162.0)
,('Individual Professional Liability','International AI','internationalAssistantInstructor','2020-11-01 00:00:00','2020-11-30 00:00:00',216.00000,'VALUE',0.00,0.00,216.00,0,NULL,'INSURED_STATUS',2020,74.0,2,71.0)
,('Individual Professional Liability','International AO','internationalDivemasterAssistantInstructorAssistingOnly','2020-11-01 00:00:00','2020-11-30 00:00:00',137.00000,'VALUE',0.00,0.00,137.00,0,NULL,'INSURED_STATUS',2020,47.0,2,45.0)
,('Individual Professional Liability','International DM','internationalDivemaster','2020-11-01 00:00:00','2020-11-30 00:00:00',216.00000,'VALUE',0.00,0.00,216.00,0,NULL,'INSURED_STATUS',2020,74.0,2,71.0)
,('Individual Professional Liability','International Instructor','internationalInstructor','2020-11-01 00:00:00','2020-11-30 00:00:00',323.00000,'VALUE',0.00,0.00,323.00,0,NULL,'INSURED_STATUS',2020,109.0,2,107.0)
;
INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,`year`,downpayment,installment_count,installment_amount) VALUES 
('Individual Professional Liability','International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor','2020-11-01 00:00:00','2020-11-30 00:00:00',216.00000,'VALUE',0.00,0.00,216.00,0,NULL,'INSURED_STATUS',2020,74.0,2,71.0)
,('Individual Professional Liability','Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2020-11-01 00:00:00','2020-11-30 00:00:00',330.00000,'VALUE',0.00,0.00,330.00,0,NULL,'INSURED_STATUS',2020,118.0,2,106.0)
,('Individual Professional Liability','Retired Instructor','retiredInstructor','2020-11-01 00:00:00','2020-11-30 00:00:00',330.00000,'VALUE',0.00,0.00,330.00,0,NULL,'INSURED_STATUS',2020,118.0,2,106.0)
,('Individual Professional Liability','Swim Instructor','swimInstructor','2020-11-01 00:00:00','2020-11-30 00:00:00',276.00000,'VALUE',0.00,0.00,276.00,0,NULL,'INSURED_STATUS',2020,100.0,2,88.0)
,('Individual Professional Liability','Scuba Fit Instructor','scubaFitInstructor','2020-11-01 00:00:00','2020-11-30 00:00:00',78.00000,'VALUE',0.00,0.00,78.00,0,NULL,'SCUBA_FIT',2020,28.0,2,25.0)
,('Individual Professional Liability','ScubaFit – Declined','scubaFitInstructorDeclined','2020-11-01 00:00:00','2020-11-30 00:00:00',0.00000,'VALUE',0.00,0.00,0.00,0,NULL,'SCUBA_FIT',2020,0.0,2,0.0)
,('Individual Professional Liability','TecRec Endorsement','withTecRecEndorsementForSelectionAbove','2020-11-01 00:00:00','2020-11-30 00:00:00',0.00000,'VALUE',0.00,0.00,0.00,0,NULL,'TEC_REC',2020,0.0,2,0.0)
,('Individual Professional Liability','TecRec Endorsement - Declined','tecRecDeclined','2020-11-01 00:00:00','2020-11-30 00:00:00',0.00000,'VALUE',0.00,0.00,0.00,0,NULL,'TEC_REC',2020,0.0,2,0.0)
;

DELETE FROM premium_rate_card where start_date IN ('2020-12-01 00:00:00') AND product IN ('Individual Professional Liability') AND is_upgrade IN (0);

INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,`year`,downpayment,installment_count,installment_amount) VALUES 
('Individual Professional Liability','Cylinder Inspector','cylinderInspector','2020-12-01 00:00:00','2020-12-31 00:00:00',178.00000,'VALUE',0.00,0.00,178.00,0,NULL,'CYLINDER',2020,93.0,1,85.0)
,('Individual Professional Liability','Cylinder Inspector & Instructor','cylinderInspectorAndInstructor','2020-12-01 00:00:00','2020-12-31 00:00:00',222.00000,'VALUE',0.00,0.00,222.00,0,NULL,'CYLINDER',2020,116.0,1,106.0)
,('Individual Professional Liability','Cylinder Instructor','cylinderInstructor','2020-12-01 00:00:00','2020-12-31 00:00:00',94.00000,'VALUE',0.00,0.00,94.00,0,NULL,'CYLINDER',2020,49.0,1,45.0)
,('Individual Professional Liability','Equipment Liability Coverage','equipmentLiabilityCoverage','2020-12-01 00:00:00','2020-12-31 00:00:00',237.00000,'VALUE',0.00,0.00,237.00,0,NULL,'EQUIPMENT_LIABILITY',2020,124.0,1,113.0)
,('Individual Professional Liability','Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined','2020-12-01 00:00:00','2020-12-31 00:00:00',0.00000,'VALUE',0.00,0.00,0.00,0,NULL,'EQUIPMENT_LIABILITY',2020,0.0,1,0.0)
,('Individual Professional Liability','0 Excess','excessLiabilityCoverageDeclined','2020-12-01 00:00:00','2020-12-31 00:00:00',0.00000,'VALUE',0.00,0.00,0.0,0,'0','EXCESS_LIABILITY',2020,0.0,1,0.0)
,('Individual Professional Liability','1M Excess','excessLiabilityCoverage1000000','2020-12-01 00:00:00','2020-12-31 00:00:00',370.00000,'VALUE',0.00,0.00,370.00,0,'1','EXCESS_LIABILITY',2020,193.0,1,177.0)
,('Individual Professional Liability','2M Excess','excessLiabilityCoverage2000000','2020-12-01 00:00:00','2020-12-31 00:00:00',741.00000,'VALUE',0.00,0.00,741.00,0,'2','EXCESS_LIABILITY',2020,386.0,1,355.0)
,('Individual Professional Liability','3M Excess','excessLiabilityCoverage3000000','2020-12-01 00:00:00','2020-12-31 00:00:00',962.00000,'VALUE',0.00,0.00,962.00,0,'3','EXCESS_LIABILITY',2020,501.0,1,461.0)
,('Individual Professional Liability','4M Excess','excessLiabilityCoverage4000000','2020-12-01 00:00:00','2020-12-31 00:00:00',1208.00000,'VALUE',0.00,0.00,1208.00,0,'4','EXCESS_LIABILITY',2020,629.0,1,579.0)
;
INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,`year`,downpayment,installment_count,installment_amount) VALUES 
('Individual Professional Liability','9M Excess','excessLiabilityCoverage9000000','2020-12-01 00:00:00','2020-12-31 00:00:00',2696.00000,'VALUE',0.00,0.00,2696.00,0,'5','EXCESS_LIABILITY',2020,1404.0,1,1292.0)
,('Individual Professional Liability','Assistant Instructor','assistantInstructor','2020-12-01 00:00:00','2020-12-31 00:00:00',292.00000,'VALUE',0.00,0.00,292.00,0,NULL,'INSURED_STATUS',2020,151.0,1,141.0)
,('Individual Professional Liability','Assistant Only','divemasterAssistantInstructorAssistingOnly','2020-12-01 00:00:00','2020-12-31 00:00:00',207.00000,'VALUE',0.00,0.00,207.00,0,NULL,'INSURED_STATUS',2020,107.0,1,100.0)
,('Individual Professional Liability','Dive Master','divemaster','2020-12-01 00:00:00','2020-12-31 00:00:00',292.00000,'VALUE',0.00,0.00,292.00,0,NULL,'INSURED_STATUS',2020,151.0,1,141.0)
,('Individual Professional Liability','Free Diver Instructor','freediveInstructor','2020-12-01 00:00:00','2020-12-31 00:00:00',292.00000,'VALUE',0.00,0.00,292.00,0,NULL,'INSURED_STATUS',2020,151.0,1,141.0)
,('Individual Professional Liability','Instructor','instructor','2020-12-01 00:00:00','2020-12-31 00:00:00',446.00000,'VALUE',0.00,0.00,446.00,0,NULL,'INSURED_STATUS',2020,231.0,1,215.0)
,('Individual Professional Liability','International AI','internationalAssistantInstructor','2020-12-01 00:00:00','2020-12-31 00:00:00',192.00000,'VALUE',0.00,0.00,192.00,0,NULL,'INSURED_STATUS',2020,96.0,1,96.0)
,('Individual Professional Liability','International AO','internationalDivemasterAssistantInstructorAssistingOnly','2020-12-01 00:00:00','2020-12-30 00:00:00',123.00000,'VALUE',0.00,0.00,123.00,0,NULL,'INSURED_STATUS',2020,62.0,1,61.0)
,('Individual Professional Liability','International DM','internationalDivemaster','2020-12-01 00:00:00','2020-12-31 00:00:00',192.00000,'VALUE',0.00,0.00,192.00,0,NULL,'INSURED_STATUS',2020,96.0,1,96.0)
,('Individual Professional Liability','International Instructor','internationalInstructor','2020-12-01 00:00:00','2020-12-31 00:00:00',286.00000,'VALUE',0.00,0.00,286.00,0,NULL,'INSURED_STATUS',2020,143.0,1,143.0)
;
INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,`year`,downpayment,installment_count,installment_amount) VALUES 
('Individual Professional Liability','International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor','2020-12-01 00:00:00','2020-12-31 00:00:00',192.00000,'VALUE',0.00,0.00,192.00,0,NULL,'INSURED_STATUS',2020,96.0,1,96.0)
,('Individual Professional Liability','Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2020-12-01 00:00:00','2020-12-31 00:00:00',292.00000,'VALUE',0.00,0.00,292.00,0,NULL,'INSURED_STATUS',2020,151.0,1,141.0)
,('Individual Professional Liability','Retired Instructor','retiredInstructor','2020-12-01 00:00:00','2020-12-31 00:00:00',292.00000,'VALUE',0.00,0.00,292.00,0,NULL,'INSURED_STATUS',2020,151.0,1,141.0)
,('Individual Professional Liability','Swim Instructor','swimInstructor','2020-12-01 00:00:00','2020-12-31 00:00:00',243.00000,'VALUE',0.00,0.00,243.00,0,NULL,'INSURED_STATUS',2020,127.0,1,116.0)
,('Individual Professional Liability','Scuba Fit Instructor','scubaFitInstructor','2020-12-01 00:00:00','2020-12-31 00:00:00',78,'VALUE',0.00,0.00,78,0,NULL,'SCUBA_FIT',2020,41.0,1,37.0)
,('Individual Professional Liability','ScubaFit – Declined','scubaFitInstructorDeclined','2020-12-01 00:00:00','2020-12-31 00:00:00',0.00000,'VALUE',0.00,0.00,0.00,0,NULL,'SCUBA_FIT',2020,0.0,1,0.0)
,('Individual Professional Liability','TecRec Endorsement','withTecRecEndorsementForSelectionAbove','2020-12-01 00:00:00','2020-12-31 00:00:00',0.00000,'VALUE',0.00,0.00,0.00,0,NULL,'TEC_REC',2020,0.0,1,0.0)
,('Individual Professional Liability','TecRec Endorsement - Declined','tecRecDeclined','2020-12-01 00:00:00','2020-12-31 00:00:00',0.00000,'VALUE',0.00,0.00,0.00,0,NULL,'TEC_REC',2020,0.0,1,0.0)
;

DELETE from premium_rate_card where start_date IN ('2020-12-01 00:00:00') AND product IN ('Emergency First Response');
INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,`year`,downpayment,installment_count,installment_amount) VALUES 
('Emergency First Response','0 Excess','excessLiabilityCoverageDeclined','2020-12-01 00:00:00','2020-12-31 00:00:00',0.00000,'VALUE',NULL,NULL,NULL,0,'0','EXCESS_LIABILITY',2020,0.0,1,0.0)
,('Emergency First Response','1M Excess','excessLiabilityCoverage1000000','2020-12-01 00:00:00','2020-12-31 00:00:00',370.00000,'VALUE',0.00,NULL,370.00,0,'1','EXCESS_LIABILITY',2020,193.0,1,177.0)
,('Emergency First Response','2M Excess','excessLiabilityCoverage2000000','2020-12-01 00:00:00','2020-12-31 00:00:00',741.00000,'VALUE',0.00,NULL,741.00,0,'2','EXCESS_LIABILITY',2020,386.0,1,355.0)
,('Emergency First Response','3M Excess','excessLiabilityCoverage3000000','2020-12-01 00:00:00','2020-12-31 00:00:00',962.00000,'VALUE',0.00,NULL,962.00,0,'3','EXCESS_LIABILITY',2020,501.0,1,461.0)
,('Emergency First Response','4M Excess','excessLiabilityCoverage4000000','2020-12-01 00:00:00','2020-12-31 00:00:00',1208.00000,'VALUE',0.00,NULL,1208.00,0,'4','EXCESS_LIABILITY',2020,629.0,1,579.0)
,('Emergency First Response','9M Excess','excessLiabilityCoverage9000000','2020-12-01 00:00:00','2020-12-31 00:00:00',2696.00000,'VALUE',0.00,NULL,2696.00,0,'5','EXCESS_LIABILITY',2020,1404.0,1,1292.0)
;
DELETE from premium_rate_card where start_date IN ('2020-11-01 00:00:00') AND product IN ('Emergency First Response');

INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,`year`,downpayment,installment_count,installment_amount) VALUES 
('Emergency First Response','0 Excess','excessLiabilityCoverageDeclined','2020-11-01 00:00:00','2020-11-30 00:00:00',0.00000,'VALUE',NULL,NULL,NULL,0,'0','EXCESS_LIABILITY',2020,0.0,2,0.0)
,('Emergency First Response','1M Excess','excessLiabilityCoverage1000000','2020-11-01 00:00:00','2020-11-30 00:00:00',422.00000,'VALUE',0.00,NULL,422.00,0,'1','EXCESS_LIABILITY',2020,152.0,2,135.0)
,('Emergency First Response','2M Excess','excessLiabilityCoverage2000000','2020-11-01 00:00:00','2020-11-30 00:00:00',847.00000,'VALUE',0.00,NULL,847.00,0,'2','EXCESS_LIABILITY',2020,305.0,2,271.0)
,('Emergency First Response','3M Excess','excessLiabilityCoverage3000000','2020-11-01 00:00:00','2020-11-30 00:00:00',1099.00000,'VALUE',0.00,NULL,1099.00,0,'3','EXCESS_LIABILITY',2020,395.0,2,352.0)
,('Emergency First Response','4M Excess','excessLiabilityCoverage4000000','2020-11-01 00:00:00','2020-11-30 00:00:00',1380.00000,'VALUE',0.00,NULL,1380.00,0,'4','EXCESS_LIABILITY',2020,498.0,2,441.0)
,('Emergency First Response','9M Excess','excessLiabilityCoverage9000000','2020-11-01 00:00:00','2020-11-30 00:00:00',3081.00000,'VALUE',0.00,NULL,3081.00,0,'5','EXCESS_LIABILITY',2020,1111.0,2,985.0)
;

DELETE from premium_rate_card where start_date IN ('2020-10-01 00:00:00') AND product IN ('Emergency First Response');

INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,`year`,downpayment,installment_count,installment_amount) VALUES 
('Emergency First Response','0 Excess','excessLiabilityCoverageDeclined','2020-10-01 00:00:00','2020-10-31 00:00:00',0.00000,'VALUE',NULL,NULL,NULL,0,'0','EXCESS_LIABILITY',2020,0.0,2,0.0)
,('Emergency First Response','1M Excess','excessLiabilityCoverage1000000','2020-10-01 00:00:00','2020-10-31 00:00:00',476.00000,'VALUE',0.00,NULL,476.00,0,'1','EXCESS_LIABILITY',2020,172.0,2,152.0)
,('Emergency First Response','2M Excess','excessLiabilityCoverage2000000','2020-10-01 00:00:00','2020-10-31 00:00:00',952.00000,'VALUE',0.00,NULL,952.00,0,'2','EXCESS_LIABILITY',2020,342.0,2,305.0)
,('Emergency First Response','3M Excess','excessLiabilityCoverage3000000','2020-10-01 00:00:00','2020-10-31 00:00:00',1236.00000,'VALUE',0.00,NULL,1236.00,0,'3','EXCESS_LIABILITY',2020,446.0,2,395.0)
,('Emergency First Response','4M Excess','excessLiabilityCoverage4000000','2020-10-01 00:00:00','2020-10-31 00:00:00',1552.00000,'VALUE',0.00,NULL,1552.00,0,'4','EXCESS_LIABILITY',2020,560.0,2,496.0)
,('Emergency First Response','9M Excess','excessLiabilityCoverage9000000','2020-10-01 00:00:00','2020-10-31 00:00:00',3466.00000,'VALUE',0.00,NULL,3466.00,0,'5','EXCESS_LIABILITY',2020,1250.0,2,1108.0)
,('Emergency First Response','Liability Coverage($1,000,000)','liabilityCoverage1000000','2020-10-01 00:00:00','2021-06-30 00:00:00',307.00000,'VALUE',NULL,NULL,307.00,0,'1','',2020,111.0,2,98.0)
;

DELETE from premium_rate_card where start_date IN ('2020-09-01 00:00:00') AND product IN ('Emergency First Response');

INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,`year`,downpayment,installment_count,installment_amount) VALUES 
('Emergency First Response','0 Excess','excessLiabilityCoverageDeclined','2020-09-01 00:00:00','2020-09-30 00:00:00',0.00000,'VALUE',NULL,NULL,NULL,0,'0','EXCESS_LIABILITY',2020,0.0,2,0.0)
,('Emergency First Response','1M Excess','excessLiabilityCoverage1000000','2020-09-01 00:00:00','2020-09-30 00:00:00',528.00000,'VALUE',0.00,NULL,528.00,0,'1','EXCESS_LIABILITY',2020,190.0,2,169.0)
,('Emergency First Response','2M Excess','excessLiabilityCoverage2000000','2020-09-01 00:00:00','2020-09-30 00:00:00',1059.00000,'VALUE',0.00,NULL,1059.00,0,'2','EXCESS_LIABILITY',2020,381.0,2,339.0)
,('Emergency First Response','3M Excess','excessLiabilityCoverage3000000','2020-09-01 00:00:00','2020-09-30 00:00:00',1374.00000,'VALUE',0.00,NULL,1374.00,0,'3','EXCESS_LIABILITY',2020,496.0,2,439.0)
,('Emergency First Response','4M Excess','excessLiabilityCoverage4000000','2020-09-01 00:00:00','2020-09-30 00:00:00',1725.00000,'VALUE',0.00,NULL,1725.00,0,'4','EXCESS_LIABILITY',2020,621.0,2,552.0)
,('Emergency First Response','9M Excess','excessLiabilityCoverage9000000','2020-09-01 00:00:00','2020-09-30 00:00:00',3851.00000,'VALUE',0.00,NULL,3851.00,0,'5','EXCESS_LIABILITY',2020,1389.0,2,1231.0)
;

DELETE from premium_rate_card where start_date IN ('2020-08-01 00:00:00') AND product IN ('Emergency First Response');

INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,`year`,downpayment,installment_count,installment_amount) VALUES 
('Emergency First Response','0 Excess','excessLiabilityCoverageDeclined','2020-08-01 00:00:00','2020-08-31 00:00:00',0.00000,'VALUE',NULL,NULL,NULL,0,'0','EXCESS_LIABILITY',2020,0.0,3,0.0)
,('Emergency First Response','1M Excess','excessLiabilityCoverage1000000','2020-08-01 00:00:00','2020-08-31 00:00:00',581.00000,'VALUE',0.00,NULL,581.00,0,'1','EXCESS_LIABILITY',2020,164.0,3,139.0)
,('Emergency First Response','2M Excess','excessLiabilityCoverage2000000','2020-08-01 00:00:00','2020-08-31 00:00:00',1164.00000,'VALUE',0.00,NULL,1164.00,0,'2','EXCESS_LIABILITY',2020,327.0,3,279.0)
,('Emergency First Response','3M Excess','excessLiabilityCoverage3000000','2020-08-01 00:00:00','2020-08-31 00:00:00',1511.00000,'VALUE',0.00,NULL,1511.00,0,'3','EXCESS_LIABILITY',2020,422.0,3,363.0)
,('Emergency First Response','4M Excess','excessLiabilityCoverage4000000','2020-08-01 00:00:00','2020-08-31 00:00:00',1897.00000,'VALUE',0.00,NULL,1897.00,0,'4','EXCESS_LIABILITY',2020,532.0,3,455.0)
,('Emergency First Response','9M Excess','excessLiabilityCoverage9000000','2020-08-01 00:00:00','2020-08-31 00:00:00',4236.00000,'VALUE',0.00,NULL,4236.00,0,'5','EXCESS_LIABILITY',2020,1188.0,3,1016.0)
;

DELETE from premium_rate_card where start_date IN ('2020-06-30 00:00:00') AND product IN ('Emergency First Response');

INSERT INTO premium_rate_card (product,coverage,`key`,start_date,end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,`year`,downpayment,installment_count,installment_amount) VALUES 
('Emergency First Response','0 Excess','excessLiabilityCoverageDeclined','2020-06-30 00:00:00','2020-07-31 00:00:00',0.00000,'VALUE',NULL,NULL,NULL,0,'0','EXCESS_LIABILITY',2020,0.0,3,0.0)
,('Emergency First Response','1M Excess','excessLiabilityCoverage1000000','2020-06-30 00:00:00','2020-07-31 00:00:00',633.00000,'VALUE',0.00,NULL,633.00,0,'1','EXCESS_LIABILITY',2020,180.0,3,151.0)
,('Emergency First Response','2M Excess','excessLiabilityCoverage2000000','2020-06-30 00:00:00','2020-07-31 00:00:00',1269.00000,'VALUE',0.00,NULL,1269.00,0,'2','EXCESS_LIABILITY',2020,357.0,3,304.0)
,('Emergency First Response','3M Excess','excessLiabilityCoverage3000000','2020-06-30 00:00:00','2020-07-31 00:00:00',1648.00000,'VALUE',0.00,NULL,1648.00,0,'3','EXCESS_LIABILITY',2020,463.0,3,395.0)
,('Emergency First Response','4M Excess','excessLiabilityCoverage4000000','2020-06-30 00:00:00','2020-07-31 00:00:00',2069.00000,'VALUE',0.00,NULL,2069.00,0,'4','EXCESS_LIABILITY',2020,581.0,3,496.0)
,('Emergency First Response','9M Excess','excessLiabilityCoverage9000000','2020-06-30 00:00:00','2020-07-31 00:00:00',4620.00000,'VALUE',0.00,NULL,4620.00,0,'5','EXCESS_LIABILITY',2020,1299.0,3,1107.0)
;