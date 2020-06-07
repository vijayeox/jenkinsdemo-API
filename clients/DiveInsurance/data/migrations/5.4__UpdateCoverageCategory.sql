UPDATE premium_rate_card SET coverage_category = "INSURED_STATUS" WHERE product = 'Individual Professional Liability' AND coverage in ('Instructor','Dive Master','Free Diver Instructor','Assistant Instructor','Assistant Only','Nonteaching/Supervisory Instructor','Retired Instructor','International Instructor','International DM','International AI','International NonTeaching/Supervising','Swim Instructor','International AO','Dive Master / Assistant Instructor Assisting Only','International Assistant Instructor','International Dive Master','International Dive Master/Assistant Instructor Assisting Only','International Nonteaching/Supervisory Instructor','Dive Master/Assistant Instructor Assisting Only');

UPDATE premium_rate_card SET coverage = 'ScubaFit – Declined' WHERE `key` = 'scubaFitInstructorDeclined';
UPDATE premium_rate_card SET coverage_category = "SCUBA_FIT" WHERE product = 'Individual Professional Liability' AND coverage in ('Scuba Fit Instructor','ScubaFit – Declined');

UPDATE premium_rate_card SET coverage_category = "EQUIPMENT_LIABILITY" WHERE product = 'Individual Professional Liability' AND coverage in ('Equipment Liability Coverage','Equipment Liability Coverage - Declined');

UPDATE premium_rate_card SET coverage = 'Cylinder Instructor' WHERE `key` = 'cylinderInstructor';
DELETE FROM premium_rate_card WHERE coverage = 'Cylinder Inspector Or Instructor - Declined' AND  product = 'Individual Professional Liability';
UPDATE premium_rate_card SET coverage_category = "CYLINDER" WHERE product = 'Individual Professional Liability' AND coverage in ('Cylinder Inspector','Cylinder Inspector & Instructor','Cylinder Instructor','Cylinder Inspector & Instructor - Declined');


UPDATE premium_rate_card SET coverage_category = "TEC_REC" WHERE product = 'Individual Professional Liability' AND coverage in ('TecRec Endorsement','TecRec Endorsement - Declined');


UPDATE premium_rate_card SET coverage_category = "EXCESS_LIABILITY" WHERE product = 'DIVE BOAT' AND `key` in ('excessLiabilityCoverageDeclined','excessLiabilityCoverage1000000','excessLiabilityCoverage2000000','excessLiabilityCoverage3000000','excessLiabilityCoverage4000000','excessLiabilityCoverage9000000');
UPDATE premium_rate_card SET coverage_category = "GROUP_COVERAGE" WHERE product = 'DIVE BOAT' AND `key` in ('groupCoverageMoreThan0','groupCoverageMoreThan25000','groupCoverageMoreThan50000','groupCoverageMoreThan100000','groupCoverageMoreThan150000','groupCoverageMoreThan200000','groupCoverageMoreThan250000','groupCoverageMoreThan350000','groupCoverageMoreThan500000');
UPDATE premium_rate_card SET coverage_category = "GROUP_EXCESS_LIABILITY" WHERE product = 'DIVE BOAT' AND `key` in ('groupExcessLiability1M','groupExcessLiability2M','groupExcessLiability3M','groupExcessLiability4M','groupExcessLiability9M');

UPDATE premium_rate_card SET previous_key = NULL WHERE `is_upgrade` = 0;