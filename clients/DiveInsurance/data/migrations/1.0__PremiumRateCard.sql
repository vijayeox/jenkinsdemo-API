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

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Instructor','instructor','2019-06-30', '2019-07-31',699);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2019-06-30', '2019-07-31',404);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2019-06-30', '2019-07-31',404);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2019-06-30', '2019-07-31',404);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2019-06-30', '2019-07-31',404);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly','2019-06-30', '2019-07-31',275);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2019-06-30', '2019-07-31',275);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor','2019-06-30', '2019-07-31',371);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster','2019-06-30', '2019-07-31',237);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor','2019-06-30', '2019-07-31',237);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor','2019-06-30', '2019-07-31',237);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly','2019-06-30', '2019-07-31',138);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor','2019-06-30', '2019-07-31',352);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Scuba Fit Instructor','scubaFitInstructor','2019-06-30', '2019-07-31',60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Scuba Fit – Declined','scubaFitInstructorDeclined','2019-06-30', '2019-07-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector','cylinderInspector','2019-06-30', '2019-07-31',225);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Instructor','cylinderInstructor','2019-06-30', '2019-07-31',118);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector & Instructor','cylinderInspectorAndInstructor','2019-06-30', '2019-07-31',281);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector Or Instructor - Declined','cylinderInspectorOrInstructorDeclined','2019-06-30', '2019-07-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage','equipmentLiabilityCoverage','2019-06-30', '2019-07-31',299);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined','2019-06-30', '2019-07-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '0 Excess', 'excessLiabilityCoverageDeclined', '2019-06-30', '2019-09-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000','2019-06-30', '2019-07-31',467);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '2M Excess','excessLiabilityCoverage2000000','2019-06-30', '2019-07-31',936);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '3M Excess','excessLiabilityCoverage3000000','2019-06-30', '2019-07-31',1215);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '4M Excess','excessLiabilityCoverage4000000','2019-06-30', '2019-07-31',1526);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '9M Excess','excessLiabilityCoverage9000000', '2019-06-30', '2019-07-31',3408);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Instructor','instructor','2019-08-01', '2019-08-31',592);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster','2019-08-01', '2019-08-31',371);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2019-08-01', '2019-08-31',371);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2019-08-01', '2019-08-31',371);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2019-08-01', '2019-08-31',371);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly','2019-08-01', '2019-08-31',253);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor','2019-08-01', '2019-08-31',253);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor','2019-08-01', '2019-08-31',341);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster','2019-08-01', '2019-08-31',218);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor','2019-08-01', '2019-08-31',218);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor','2019-08-01', '2019-07-31',218);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly','2019-08-01', '2019-08-31',127);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor','2019-08-01', '2019-08-31',323);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Scuba Fit Instructor','scubaFitInstructor','2019-08-01', '2019-08-31',60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'ScubaFit – Declined','scubaFitInstructorDeclined','2019-08-01', '2019-08-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector','cylinderInspector','2019-08-01', '2019-08-31',207);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Instructor','cylinderInstructor','2019-08-01', '2019-08-31',109);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Insp & Inst','cylinderInspectorAndInstructor','2019-08-01', '2019-08-31',258);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector Or Instructor - Declined','cylinderInspectorOrInstructorDeclined','2019-08-01', '2019-08-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage','equipmentLiabilityCoverage','2019-08-01', '2019-08-31',275);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined','2019-08-01', '2019-08-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '0 Excess', 'excessLiabilityCoverageDeclined','2019-08-01', '2019-08-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000','2019-08-01', '2019-08-31',429);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '2M Excess','excessLiabilityCoverage2000000','2019-08-01', '2019-08-31',858);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '3M Excess','excessLiabilityCoverage3000000','2019-08-01', '2019-08-31',1114);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '4M Excess','excessLiabilityCoverage4000000','2019-08-01', '2019-08-31',1399);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '9M Excess','excessLiabilityCoverage9000000','2019-08-01', '2019-08-31',3124);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2019-09-01', '2019-09-30',538);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Dive Master', 'divemaster','2019-09-01', '2019-09-30',337);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2019-09-01', '2019-09-30',337);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2019-09-01', '2019-09-30',337);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2019-09-01', '2019-09-30',337);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2019-09-01', '2019-09-30',230);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor', '2019-09-01', '2019-09-30',230);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2019-09-01', '2019-09-30',310);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2019-09-01', '2019-09-30',198);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2019-09-01', '2019-09-30',198);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2019-09-01', '2019-09-30',115);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2019-09-01', '2019-09-30',294);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Scuba Fit Instructor','scubaFitInstructor', '2019-09-01', '2019-09-30',60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'ScubaFit – Declined','scubaFitInstructorDeclined', '2019-09-01', '2019-09-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector','cylinderInspector', '2019-09-01', '2019-09-30',188);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Instructor','cylinderInstructor', '2019-09-01', '2019-09-30',99);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Insp & Inst','cylinderInspectorAndInstructor', '2019-09-01', '2019-09-30',235);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector Or Instructor - Declined','cylinderInspectorOrInstructorDeclined', '2019-09-01', '2019-09-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage','equipmentLiabilityCoverage', '2019-09-01', '2019-09-30',250);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined', '2019-09-01', '2019-09-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '0 Excess','excessLiabilityCoverageDeclined', '2019-09-01', '2019-09-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000', '2019-09-01', '2019-09-30',390);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '2M Excess','excessLiabilityCoverage2000000', '2019-09-01', '2019-09-30',780);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '3M Excess','excessLiabilityCoverage3000000', '2019-09-01', '2019-09-30',1013);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '4M Excess','excessLiabilityCoverage4000000', '2019-09-01', '2019-09-30',1272);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '9M Excess','excessLiabilityCoverage9000000', '2019-09-01', '2019-09-30',2840);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2019-10-01', '2019-10-31',484);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster', '2019-10-01', '2019-10-31',303);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor', '2019-10-01', '2019-10-31',303);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor', '2019-10-01', '2019-10-31',303);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor', '2019-10-01', '2019-10-31',303);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2019-10-01', '2019-10-31',207);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor', '2019-10-01', '2019-10-31',207);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2019-10-01', '2019-10-31',279);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2019-10-01', '2019-10-31',178);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2019-10-01', '2019-10-31',178);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2019-10-01', '2019-10-31',178);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2019-10-01', '2019-10-31',104);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2019-10-01', '2019-10-31',264);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Scuba Fit Instructor','scubaFitInstructor', '2019-10-01', '2019-10-31',60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'ScubaFit – Declined','scubaFitInstructorDeclined', '2019-10-01', '2019-10-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector','cylinderInspector', '2019-10-01', '2019-10-31',169);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Instructor','cylinderInstructor', '2019-10-01', '2019-10-31',89);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Insp & Inst','cylinderInspectorAndInstructor', '2019-10-01', '2019-10-31',211);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector Or Instructor - Declined','cylinderInspectorOrInstructorDeclined', '2019-10-01', '2019-10-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage','equipmentLiabilityCoverage', '2019-10-01', '2019-10-31',225);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '0 Excess','excessLiabilityCoverageDeclined', '2019-10-01', '2019-10-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000', '2019-10-01', '2019-10-31',351);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '2M Excess','excessLiabilityCoverage2000000', '2019-10-01', '2019-10-31',702);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '3M Excess','excessLiabilityCoverage3000000', '2019-10-01', '2019-10-31',912);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '4M Excess','excessLiabilityCoverage4000000', '2019-10-01', '2019-10-31',1145);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '9M Excess','excessLiabilityCoverage9000000', '2019-10-01', '2019-10-31',2556);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Instructor', 'instructor','2019-11-01', '2019-11-30',430);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster', '2019-11-01', '2019-11-30',270);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor', '2019-11-01', '2019-11-30',270);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor', '2019-11-01', '2019-11-30',270);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor', '2019-11-01', '2019-11-30',270);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2019-11-01', '2019-11-30',184);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor', '2019-11-01', '2019-11-30',184);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2019-11-01', '2019-11-30',248);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2019-11-01', '2019-11-30',158);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2019-11-01', '2019-11-30',158);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2019-11-01', '2019-11-30',158);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2019-11-01', '2019-11-30',92);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2019-11-01', '2019-11-30',235);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Scuba Fit Instructor','scubaFitInstructor', '2019-11-01', '2019-11-30',60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'ScubaFit – Declined','scubaFitInstructorDeclined', '2019-11-01', '2019-11-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector','cylinderInspector', '2019-11-01', '2019-11-30',150);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Instructor','cylinderInstructor', '2019-11-01', '2019-11-30',79);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Insp & Inst','cylinderInspectorAndInstructor', '2019-11-01', '2019-11-30',188);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector Or Instructor - Declined','cylinderInspectorOrInstructorDeclined', '2019-11-01', '2019-11-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage','equipmentLiabilityCoverage', '2019-11-01', '2019-11-30',200);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined', '2019-11-01', '2019-11-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '0 Excess','excessLiabilityCoverageDeclined', '2019-11-01', '2019-11-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000', '2019-11-01', '2019-11-30',312);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '2M Excess','excessLiabilityCoverage2000000', '2019-11-01', '2019-11-30',624);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '3M Excess','excessLiabilityCoverage3000000', '2019-11-01', '2019-11-30',810);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '4M Excess','excessLiabilityCoverage4000000', '2019-11-01', '2019-11-30',1018);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '9M Excess','excessLiabilityCoverage9000000', '2019-11-01', '2019-11-30',2272);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2019-12-01', '2019-12-31',377);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Dive Master', 'divemaster','2019-12-01', '2019-12-31',236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2019-12-01', '2019-12-31',236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2019-12-01', '2019-12-31',236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2019-12-01', '2019-12-31',236);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2019-12-01', '2019-12-31',161);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor', '2019-12-01', '2019-12-31',161);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2019-12-01', '2019-12-31',217);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2019-12-01', '2019-12-31',139);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2019-12-01', '2019-12-31',139);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2019-12-01', '2019-12-31',139);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2019-12-01', '2019-12-31',81);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2019-12-01', '2019-12-31',206);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Scuba Fit Instructor','scubaFitInstructor', '2019-12-01', '2019-12-31',60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'ScubaFit – Declined','scubaFitInstructorDeclined', '2019-12-01', '2019-12-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector','cylinderInspector', '2019-12-01', '2019-12-31',132);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Instructor','cylinderInstructor', '2019-12-01', '2019-12-31',69);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Insp & Inst','cylinderInspectorAndInstructor', '2019-12-01', '2019-12-31',164);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector Or Instructor - Declined','cylinderInspectorOrInstructorDeclined', '2019-12-01', '2019-12-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage','equipmentLiabilityCoverage', '2019-12-01', '2019-12-31',175);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined', '2019-12-01', '2019-12-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000', '2019-12-01', '2019-12-31',273);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '2M Excess','excessLiabilityCoverage2000000', '2019-12-01', '2019-12-31',546);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '3M Excess','excessLiabilityCoverage3000000', '2019-12-01', '2019-12-31',709);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '4M Excess','excessLiabilityCoverage4000000', '2019-12-01', '2019-12-31',891);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '9M Excess','excessLiabilityCoverage9000000', '2019-12-01', '2019-12-31',1988);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2020-01-01', '2020-01-31',323);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Dive Master', 'divemaster','2020-01-01', '2020-01-31',202);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2020-01-01', '2020-01-31',202);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2020-01-01', '2020-01-31',202);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2020-01-01', '2020-01-31',202);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2020-01-01', '2020-01-31',138);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor', '2020-01-01', '2020-01-31',138);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2020-01-01', '2020-01-31',186);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2020-01-01', '2020-01-31',119);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2020-01-01', '2020-01-31',119);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2020-01-01', '2020-01-31',119);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2020-01-01', '2020-01-31',69);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2020-01-01', '2020-01-31',176);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Scuba Fit Instructor','scubaFitInstructor', '2020-01-01', '2020-01-31',60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'ScubaFit – Declined','scubaFitInstructorDeclined', '2020-01-01', '2020-01-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector','cylinderInspector', '2020-01-01', '2020-01-31',113);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Instructor','cylinderInstructor', '2020-01-01', '2020-01-31',59);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Insp & Inst','cylinderInspectorAndInstructor', '2020-01-01', '2020-01-31',141);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector Or Instructor - Declined','cylinderInspectorOrInstructorDeclined', '2020-01-01', '2020-01-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage','equipmentLiabilityCoverage', '2020-01-01', '2020-01-31',150);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined', '2020-01-01', '2020-01-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '0 Excess','excessLiabilityCoverageDeclined', '2020-01-01', '2020-01-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000', '2020-01-01', '2020-01-31',234);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '2M Excess','excessLiabilityCoverage2000000', '2020-01-01', '2020-01-31',468);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '3M Excess','excessLiabilityCoverage3000000', '2020-01-01', '2020-01-31',608);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '4M Excess','excessLiabilityCoverage4000000', '2020-01-01', '2020-01-31',763);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '9M Excess','excessLiabilityCoverage9000000', '2020-01-01', '2020-01-31',1704);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2020-02-01', '2020-02-29',269);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Dive Master', 'divemaster','2020-02-01', '2020-02-29',169);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2020-02-01', '2020-02-29',169);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2020-02-01', '2020-02-29',169);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2020-02-01', '2020-02-29',169);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2020-02-01', '2020-02-29',115);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor', '2020-02-01', '2020-02-29',115);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2020-02-01', '2020-02-29',155);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2020-02-01', '2020-02-29',99);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2020-02-01', '2020-02-29',99);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2020-02-01', '2020-02-29',99);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2020-02-01', '2020-02-29',58);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2020-02-01', '2020-02-29',147);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Scuba Fit Instructor','scubaFitInstructor', '2020-02-01', '2020-02-29',60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'ScubaFit – Declined','scubaFitInstructorDeclined', '2020-02-01', '2020-02-29',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector','cylinderInspector', '2020-02-01', '2020-02-29',94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Instructor','cylinderInstructor', '2020-02-01', '2020-02-29',50);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Insp & Inst','cylinderInspectorAndInstructor', '2020-02-01', '2020-02-29',118);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector Or Instructor - Declined','cylinderInspectorOrInstructorDeclined', '2020-02-01', '2020-02-29',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage','equipmentLiabilityCoverage', '2020-02-01', '2020-02-29',125);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined', '2020-02-01', '2020-02-29',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '0 Excess','excessLiabilityCoverageDeclined', '2020-02-01', '2020-02-29',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000', '2020-02-01', '2020-02-29',195);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '2M Excess','excessLiabilityCoverage2000000', '2020-02-01', '2020-02-29',390);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '3M Excess','excessLiabilityCoverage3000000', '2020-02-01', '2020-02-29',507);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '4M Excess','excessLiabilityCoverage4000000', '2020-02-01', '2020-02-29',636);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '9M Excess','excessLiabilityCoverage9000000', '2020-02-01', '2020-02-29',1420);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2020-03-01', '2020-03-31',215);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Dive Master', 'divemaster','2020-03-01', '2020-03-31',135);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2020-03-01', '2020-03-31',135);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2020-03-01', '2020-03-31',135);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2020-03-01', '2020-03-31',135);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2020-03-01', '2020-03-31',92);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor', '2020-03-01', '2020-03-31',92);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2020-03-01', '2020-03-31',124);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2020-03-01', '2020-03-31',79);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2020-03-01', '2020-03-31',79);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2020-03-01', '2020-03-31',79);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2020-03-01', '2020-03-31',46);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2020-03-01', '2020-03-31',118);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Scuba Fit Instructor','scubaFitInstructor', '2020-03-01', '2020-03-31',60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'ScubaFit – Declined','scubaFitInstructorDeclined', '2020-03-01', '2020-03-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector','cylinderInspector', '2020-03-01', '2020-03-31',75);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Instructor','cylinderInstructor', '2020-03-01', '2020-03-31',40);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Insp & Inst','cylinderInspectorAndInstructor', '2020-03-01', '2020-03-31',94);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector Or Instructor - Declined','cylinderInspectorOrInstructorDeclined', '2020-03-01', '2020-03-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage','equipmentLiabilityCoverage', '2020-03-01', '2020-03-31',100);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined', '2020-03-01', '2020-03-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000', '2020-03-01', '2020-03-31',156);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '2M Excess','excessLiabilityCoverage2000000', '2020-03-01', '2020-03-31',312);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '3M Excess','excessLiabilityCoverage3000000', '2020-03-01', '2020-03-31',405);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '4M Excess','excessLiabilityCoverage4000000', '2020-03-01', '2020-03-31',509);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '9M Excess','excessLiabilityCoverage9000000', '2020-03-01', '2020-03-31',1136);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2020-04-01', '2020-04-30',162);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster', '2020-04-01', '2020-04-30',101);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor', '2020-04-01', '2020-04-30',101);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor', '2020-04-01', '2020-04-30',101);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor', '2020-04-01', '2020-04-30',101);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2020-04-01', '2020-04-30',69);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor', '2020-04-01', '2020-04-30',69);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2020-04-01', '2020-04-30',93);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2020-04-01', '2020-04-30',60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2020-04-01', '2020-04-30',60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2020-04-01', '2020-04-30',60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2020-04-01', '2020-04-30',35);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor','2020-04-01', '2020-04-30',88);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Scuba Fit Instructor','scubaFitInstructor','2020-04-01', '2020-04-30',60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'ScubaFit – Declined','scubaFitInstructorDeclined','2020-04-01', '2020-04-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector','cylinderInspector', '2020-04-01', '2020-04-30',57);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Instructor','cylinderInstructor', '2020-04-01', '2020-04-30',30);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Insp & Inst','cylinderInspectorAndInstructor', '2020-04-01', '2020-04-30',71);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector Or Instructor - Declined','cylinderInspectorOrInstructorDeclined', '2020-04-01', '2020-04-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage','equipmentLiabilityCoverage', '2020-04-01', '2020-04-30',75);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined', '2020-04-01', '2020-04-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '0 Excess','excessLiabilityCoverageDeclined', '2020-04-01', '2020-04-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000', '2020-04-01', '2020-04-30',117);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '2M Excess','excessLiabilityCoverage2000000', '2020-04-01', '2020-04-30',234);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '3M Excess','excessLiabilityCoverage3000000', '2020-04-01', '2020-04-30',304);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '4M Excess','excessLiabilityCoverage4000000', '2020-04-01', '2020-04-30',382);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '9M Excess','excessLiabilityCoverage9000000', '2020-04-01', '2020-04-30',852);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2020-05-01', '2020-05-31',108);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Dive Master','divemaster', '2020-05-01', '2020-05-31',68);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor', '2020-05-01', '2020-05-31',68);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor', '2020-05-01', '2020-05-31',68);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor', '2020-05-01', '2020-05-31',68);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2020-05-01', '2020-05-31',46);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor', '2020-05-01', '2020-05-31',46);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2020-05-01', '2020-05-31',62);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2020-05-01', '2020-05-31',40);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2020-05-01', '2020-05-31',40);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2020-05-01', '2020-05-31',40);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2020-05-01', '2020-05-31',23);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2020-05-01', '2020-05-31',59);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Scuba Fit Instructor','scubaFitInstructor', '2020-05-01', '2020-05-31',60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'ScubaFit – Declined','scubaFitInstructorDeclined', '2020-05-01', '2020-05-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector','cylinderInspector', '2020-05-01', '2020-05-31',38);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Instructor','cylinderInstructor', '2020-05-01', '2020-05-31',20);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Insp & Inst','cylinderInspectorAndInstructor', '2020-05-01', '2020-05-31',47);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector Or Instructor - Declined','cylinderInspectorOrInstructorDeclined', '2020-05-01', '2020-05-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage','equipmentLiabilityCoverage', '2020-05-01', '2020-05-31',50);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined', '2020-05-01', '2020-05-31',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000', '2020-05-01', '2020-05-31',78);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '2M Excess','excessLiabilityCoverage2000000', '2020-05-01', '2020-05-31',156);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '3M Excess','excessLiabilityCoverage3000000', '2020-05-01', '2020-05-31',203);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '4M Excess','excessLiabilityCoverage4000000', '2020-05-01', '2020-05-31',255);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '9M Excess','excessLiabilityCoverage9000000', '2020-05-01', '2020-05-31',568);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Instructor','instructor', '2020-06-01', '2020-06-30',54);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Dive Master', 'divemaster','2020-06-01', '2020-06-30',34);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Free Diver Instructor','freediveInstructor','2020-06-01', '2020-06-30',34);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Assistant Instructor','assistantInstructor','2020-06-01', '2020-06-30',34);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Nonteaching/Supervisory Instructor','nonteachingSupervisoryInstructor','2020-06-01', '2020-06-30',34);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Assistant Only','divemasterAssistantInstructorAssistingOnly', '2020-06-01', '2020-06-30',23);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Retired Instructor','retiredInstructor', '2020-06-01', '2020-06-30',23);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International Instructor','internationalInstructor', '2020-06-01', '2020-06-30',31);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International DM','internationalDivemaster', '2020-06-01', '2020-06-30',20);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AI','internationalAssistantInstructor', '2020-06-01', '2020-06-30',20);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International NonTeaching/Supervising','internationalNonteachingSupervisoryInstructor', '2020-06-01', '2020-06-30',20);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'International AO','internationalDivemasterAssistantInstructorAssistingOnly', '2020-06-01', '2020-06-30',12);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Swim Instructor','swimInstructor', '2020-06-01', '2020-06-30',30);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Scuba Fit Instructor','scubaFitInstructor', '2020-06-01', '2020-06-30',60);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'ScubaFit – Declined','scubaFitInstructorDeclined', '2020-06-01', '2020-06-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector','cylinderInspector', '2020-06-01', '2020-06-30',19);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Instructor','cylinderInstructor', '2020-06-01', '2020-06-30',10);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Insp & Inst','cylinderInspectorAndInstructor', '2020-06-01', '2020-06-30',24);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Cylinder Inspector Or Instructor - Declined','cylinderInspectorOrInstructorDeclined', '2020-06-01', '2020-06-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage','equipmentLiabilityCoverage', '2020-06-01', '2020-06-30',25);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', 'Equipment Liability Coverage - Declined','equipmentLiabilityCoverageDeclined', '2020-06-01', '2020-06-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '0 Excess','excessLiabilityCoverageDeclined', '2020-06-01', '2020-06-30',0);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000', '2020-06-01', '2020-06-30',39);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '1M Excess','excessLiabilityCoverage1000000', '2020-06-01', '2020-06-30',39);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '2M Excess','excessLiabilityCoverage2000000', '2020-06-01', '2020-06-30',78);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '3M Excess','excessLiabilityCoverage3000000', '2020-06-01', '2020-06-30',102);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '4M Excess','excessLiabilityCoverage4000000', '2020-06-01', '2020-06-30',128);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Individual Professional Liability', '9M Excess','excessLiabilityCoverage9000000', '2020-06-01', '2020-06-30',284);

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


UPDATE `premium_rate_card` SET `tax`=28,`padi_fee`=26 ,`total`=699 WHERE `premium`=645 AND coverage = 'Instructor';     
UPDATE `premium_rate_card` SET `tax`=25,`padi_fee`=26 ,`total`=643 WHERE `premium`=592 AND coverage = 'Instructor';     
UPDATE `premium_rate_card` SET `tax`=23,`padi_fee`=26 ,`total`=587 WHERE `premium`=538 AND coverage = 'Instructor';     
UPDATE `premium_rate_card` SET `tax`=21,`padi_fee`=26 ,`total`=531 WHERE `premium`=484 AND coverage = 'Instructor';     
UPDATE `premium_rate_card` SET `tax`=18,`padi_fee`=26 ,`total`=474 WHERE `premium`=430 AND coverage = 'Instructor';     
UPDATE `premium_rate_card` SET `tax`=16,`padi_fee`=26 ,`total`=419 WHERE `premium`=377 AND coverage = 'Instructor';     
UPDATE `premium_rate_card` SET `tax`=14,`padi_fee`=26 ,`total`=363 WHERE `premium`=323 AND coverage = 'Instructor';     
UPDATE `premium_rate_card` SET `tax`=12,`padi_fee`=26 ,`total`=307 WHERE `premium`=269 AND coverage = 'Instructor';     
UPDATE `premium_rate_card` SET `tax`=9,`padi_fee`=26 ,`total`=250 WHERE `premium`=215 AND coverage = 'Instructor';      
UPDATE `premium_rate_card` SET `tax`=7,`padi_fee`=26 ,`total`=195 WHERE `premium`=162 AND coverage = 'Instructor';      
UPDATE `premium_rate_card` SET `tax`=5,`padi_fee`=26 ,`total`=139 WHERE `premium`=108 AND coverage = 'Instructor';      
UPDATE `premium_rate_card` SET `tax`=2,`padi_fee`=26 ,`total`=82 WHERE `premium`=54 AND coverage = 'Instructor';

UPDATE `premium_rate_card` SET `tax`=17,`padi_fee`=16 ,`total`=437 WHERE `premium`=404 AND coverage = 'DM/AI/Freediver';      
UPDATE `premium_rate_card` SET `tax`=16,`padi_fee`=26 ,`total`=413 WHERE `premium`=371 AND coverage = 'DM/AI/Freediver';      
UPDATE `premium_rate_card` SET `tax`=14,`padi_fee`=26 ,`total`=377 WHERE `premium`=337 AND coverage = 'DM/AI/Freediver';      
UPDATE `premium_rate_card` SET `tax`=13,`padi_fee`=26 ,`total`=342 WHERE `premium`=303 AND coverage = 'DM/AI/Freediver';      
UPDATE `premium_rate_card` SET `tax`=12,`padi_fee`=26 ,`total`=308 WHERE `premium`=270 AND coverage = 'DM/AI/Freediver';      
UPDATE `premium_rate_card` SET `tax`=10,`padi_fee`=26 ,`total`=272 WHERE `premium`=236 AND coverage = 'DM/AI/Freediver';      
UPDATE `premium_rate_card` SET `tax`=9,`padi_fee`=26 ,`total`=237 WHERE `premium`=202 AND coverage = 'DM/AI/Freediver'; 
UPDATE `premium_rate_card` SET `tax`=7,`padi_fee`=26 ,`total`=202 WHERE `premium`=169 AND coverage = 'DM/AI/Freediver'; 
UPDATE `premium_rate_card` SET `tax`=6,`padi_fee`=26 ,`total`=167 WHERE `premium`=135 AND coverage = 'DM/AI/Freediver'; 
UPDATE `premium_rate_card` SET `tax`=4,`padi_fee`=26 ,`total`=131 WHERE `premium`=101 AND coverage = 'DM/AI/Freediver'; 
UPDATE `premium_rate_card` SET `tax`=3,`padi_fee`=26 ,`total`=97 WHERE `premium`=68 AND coverage = 'DM/AI/Freediver';   
UPDATE `premium_rate_card` SET `tax`=1,`padi_fee`=26 ,`total`=61 WHERE `premium`=34 AND coverage = 'DM/AI/Freediver';

UPDATE `premium_rate_card` SET `tax`=12,`padi_fee`=16 ,`total`=303 WHERE `premium`=275 AND coverage = 'Asst Only';      
UPDATE `premium_rate_card` SET `tax`=11,`padi_fee`=26 ,`total`=290 WHERE `premium`=253 AND coverage = 'Asst Only';      
UPDATE `premium_rate_card` SET `tax`=10,`padi_fee`=26 ,`total`=266 WHERE `premium`=230 AND coverage = 'Asst Only';      
UPDATE `premium_rate_card` SET `tax`=9,`padi_fee`=26 ,`total`=242 WHERE `premium`=207 AND coverage = 'Asst Only'; 
UPDATE `premium_rate_card` SET `tax`=8,`padi_fee`=26 ,`total`=218 WHERE `premium`=184 AND coverage = 'Asst Only'; 
UPDATE `premium_rate_card` SET `tax`=7,`padi_fee`=26 ,`total`=194 WHERE `premium`=161 AND coverage = 'Asst Only'; 
UPDATE `premium_rate_card` SET `tax`=6,`padi_fee`=26 ,`total`=170 WHERE `premium`=138 AND coverage = 'Asst Only'; 
UPDATE `premium_rate_card` SET `tax`=5,`padi_fee`=26 ,`total`=146 WHERE `premium`=115 AND coverage = 'Asst Only'; 
UPDATE `premium_rate_card` SET `tax`=4,`padi_fee`=26 ,`total`=122 WHERE `premium`=92 AND coverage = 'Asst Only';  
UPDATE `premium_rate_card` SET `tax`=3,`padi_fee`=26 ,`total`=98 WHERE `premium`=69 AND coverage = 'Asst Only';   
UPDATE `premium_rate_card` SET `tax`=2,`padi_fee`=26 ,`total`=74 WHERE `premium`=46 AND coverage = 'Asst Only';   
UPDATE `premium_rate_card` SET `tax`=1,`padi_fee`=26 ,`total`=50 WHERE `premium`=23 AND coverage = 'Asst Only';

UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=397 WHERE `premium`=371 AND coverage = 'Int''l Instructor'; 
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=367 WHERE `premium`=341 AND coverage = 'Int''l Instructor'; 
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=336 WHERE `premium`=310 AND coverage = 'Int''l Instructor'; 
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=305 WHERE `premium`=279 AND coverage = 'Int''l Instructor'; 
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=274 WHERE `premium`=248 AND coverage = 'Int''l Instructor'; 
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=243 WHERE `premium`=217 AND coverage = 'Int''l Instructor'; 
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=212 WHERE `premium`=186 AND coverage = 'Int''l Instructor'; 
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=181 WHERE `premium`=155 AND coverage = 'Int''l Instructor'; 
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=150 WHERE `premium`=124 AND coverage = 'Int''l Instructor'; 
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=119 WHERE `premium`=93 AND coverage = 'Int''l Instructor';  
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=88 WHERE `premium`=62 AND coverage = 'Int''l Instructor';   
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=57 WHERE `premium`=31 AND coverage = 'Int''l Instructor';

UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=263 WHERE `premium`=237 AND coverage = 'Int''l DM/AI';      
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=244 WHERE `premium`=218 AND coverage = 'Int''l DM/AI';      
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=224 WHERE `premium`=198 AND coverage = 'Int''l DM/AI';      
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=204 WHERE `premium`=178 AND coverage = 'Int''l DM/AI';      
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=184 WHERE `premium`=158 AND coverage = 'Int''l DM/AI';      
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=165 WHERE `premium`=139 AND coverage = 'Int''l DM/AI';      
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=145 WHERE `premium`=119 AND coverage = 'Int''l DM/AI';      
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=125 WHERE `premium`=99 AND coverage = 'Int''l DM/AI'; 
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=105 WHERE `premium`=79 AND coverage = 'Int''l DM/AI'; 
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=86 WHERE `premium`=60 AND coverage = 'Int''l DM/AI';  
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=66 WHERE `premium`=40 AND coverage = 'Int''l DM/AI';  
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=46 WHERE `premium`=20 AND coverage = 'Int''l DM/AI';

UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=164 WHERE `premium`=138 AND coverage = 'Int''l AO';   
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=153 WHERE `premium`=127 AND coverage = 'Int''l AO';   
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=141 WHERE `premium`=115 AND coverage = 'Int''l AO';   
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=130 WHERE `premium`=104 AND coverage = 'Int''l AO';   
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=118 WHERE `premium`=92 AND coverage = 'Int''l AO';    
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=107 WHERE `premium`=81 AND coverage = 'Int''l AO';    
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=95 WHERE `premium`=69 AND coverage = 'Int''l AO';     
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=84 WHERE `premium`=58 AND coverage = 'Int''l AO';     
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=72 WHERE `premium`=46 AND coverage = 'Int''l AO';     
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=61 WHERE `premium`=35 AND coverage = 'Int''l AO';     
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=49 WHERE `premium`=23 AND coverage = 'Int''l AO';     
UPDATE `premium_rate_card` SET `padi_fee`=26 ,`total`=38 WHERE `premium`=12 AND coverage = 'Int''l AO';

UPDATE `premium_rate_card` SET `tax`=15,`padi_fee`=11 ,`total`=378 WHERE `premium`=352 AND coverage = 'Swim Instructor';      
UPDATE `premium_rate_card` SET `tax`=14,`padi_fee`=11 ,`total`=348 WHERE `premium`=323 AND coverage = 'Swim Instructor';      
UPDATE `premium_rate_card` SET `tax`=13,`padi_fee`=11 ,`total`=318 WHERE `premium`=294 AND coverage = 'Swim Instructor';      
UPDATE `premium_rate_card` SET `tax`=11,`padi_fee`=11 ,`total`=286 WHERE `premium`=264 AND coverage = 'Swim Instructor';      
UPDATE `premium_rate_card` SET `tax`=10,`padi_fee`=11 ,`total`=256 WHERE `premium`=235 AND coverage = 'Swim Instructor';      
UPDATE `premium_rate_card` SET `tax`=9,`padi_fee`=11 ,`total`=226 WHERE `premium`=206 AND coverage = 'Swim Instructor'; 
UPDATE `premium_rate_card` SET `tax`=8,`padi_fee`=11 ,`total`=195 WHERE `premium`=176 AND coverage = 'Swim Instructor'; 
UPDATE `premium_rate_card` SET `tax`=6,`padi_fee`=11 ,`total`=164 WHERE `premium`=147 AND coverage = 'Swim Instructor'; 
UPDATE `premium_rate_card` SET `tax`=5,`padi_fee`=11 ,`total`=134 WHERE `premium`=118 AND coverage = 'Swim Instructor'; 
UPDATE `premium_rate_card` SET `tax`=4,`padi_fee`=11 ,`total`=103 WHERE `premium`=88 AND coverage = 'Swim Instructor';  
UPDATE `premium_rate_card` SET `tax`=3,`padi_fee`=11 ,`total`=73 WHERE `premium`=59 AND coverage = 'Swim Instructor';   
UPDATE `premium_rate_card` SET `tax`=1,`padi_fee`=11 ,`total`=42 WHERE `premium`=30 AND coverage = 'Swim Instructor';

UPDATE `premium_rate_card` SET `tax`=13 ,`total`=312 WHERE `premium`=299 AND coverage = 'Equipment';  
UPDATE `premium_rate_card` SET `tax`=12 ,`total`=287 WHERE `premium`=275 AND coverage = 'Equipment';  
UPDATE `premium_rate_card` SET `tax`=11 ,`total`=261 WHERE `premium`=250 AND coverage = 'Equipment';  
UPDATE `premium_rate_card` SET `tax`=10 ,`total`=235 WHERE `premium`=225 AND coverage = 'Equipment';  
UPDATE `premium_rate_card` SET `tax`=9 ,`total`=209 WHERE `premium`=200 AND coverage = 'Equipment';   
UPDATE `premium_rate_card` SET `tax`=8 ,`total`=183 WHERE `premium`=175 AND coverage = 'Equipment';   
UPDATE `premium_rate_card` SET `tax`=6 ,`total`=156 WHERE `premium`=150 AND coverage = 'Equipment';   
UPDATE `premium_rate_card` SET `tax`=5 ,`total`=130 WHERE `premium`=125 AND coverage = 'Equipment';   
UPDATE `premium_rate_card` SET `tax`=4 ,`total`=104 WHERE `premium`=100 AND coverage = 'Equipment';   
UPDATE `premium_rate_card` SET `tax`=3 ,`total`=78 WHERE `premium`=75 AND coverage = 'Equipment';     
UPDATE `premium_rate_card` SET `tax`=2 ,`total`=52 WHERE `premium`=50 AND coverage = 'Equipment';     
UPDATE `premium_rate_card` SET `tax`=1 ,`total`=26 WHERE `premium`=25 AND coverage = 'Equipment';

UPDATE `premium_rate_card` SET `tax`=10 ,`total`=235 WHERE `premium`=225 AND coverage = 'Cylinder Inspector';     
UPDATE `premium_rate_card` SET `tax`=9 ,`total`=216 WHERE `premium`=207 AND coverage = 'Cylinder Inspector';      
UPDATE `premium_rate_card` SET `tax`=8 ,`total`=196 WHERE `premium`=188 AND coverage = 'Cylinder Inspector';      
UPDATE `premium_rate_card` SET `tax`=7 ,`total`=176 WHERE `premium`=169 AND coverage = 'Cylinder Inspector';      
UPDATE `premium_rate_card` SET `tax`=6 ,`total`=156 WHERE `premium`=150 AND coverage = 'Cylinder Inspector';      
UPDATE `premium_rate_card` SET `tax`=6 ,`total`=138 WHERE `premium`=132 AND coverage = 'Cylinder Inspector';      
UPDATE `premium_rate_card` SET `tax`=5 ,`total`=118 WHERE `premium`=113 AND coverage = 'Cylinder Inspector';      
UPDATE `premium_rate_card` SET `tax`=4 ,`total`=98 WHERE `premium`=94 AND coverage = 'Cylinder Inspector';  
UPDATE `premium_rate_card` SET `tax`=3 ,`total`=78 WHERE `premium`=75 AND coverage = 'Cylinder Inspector';  
UPDATE `premium_rate_card` SET `tax`=2 ,`total`=59 WHERE `premium`=57 AND coverage = 'Cylinder Inspector';  
UPDATE `premium_rate_card` SET `tax`=2 ,`total`=40 WHERE `premium`=38 AND coverage = 'Cylinder Inspector';  
UPDATE `premium_rate_card` SET `tax`=1 ,`total`=20 WHERE `premium`=19 AND coverage = 'Cylinder Inspector';

UPDATE `premium_rate_card` SET `tax`=5 ,`total`=123 WHERE `premium`=118 AND coverage = 'Cylinder Instructor';     
UPDATE `premium_rate_card` SET `tax`=5 ,`total`=114 WHERE `premium`=109 AND coverage = 'Cylinder Instructor';     
UPDATE `premium_rate_card` SET `tax`=4 ,`total`=103 WHERE `premium`=99 AND coverage = 'Cylinder Instructor';      
UPDATE `premium_rate_card` SET `tax`=4 ,`total`=93 WHERE `premium`=89 AND coverage = 'Cylinder Instructor'; 
UPDATE `premium_rate_card` SET `tax`=3 ,`total`=82 WHERE `premium`=79 AND coverage = 'Cylinder Instructor'; 
UPDATE `premium_rate_card` SET `tax`=3 ,`total`=72 WHERE `premium`=69 AND coverage = 'Cylinder Instructor'; 
UPDATE `premium_rate_card` SET `tax`=3 ,`total`=62 WHERE `premium`=59 AND coverage = 'Cylinder Instructor'; 
UPDATE `premium_rate_card` SET `tax`=2 ,`total`=52 WHERE `premium`=50 AND coverage = 'Cylinder Instructor'; 
UPDATE `premium_rate_card` SET `tax`=2 ,`total`=42 WHERE `premium`=40 AND coverage = 'Cylinder Instructor'; 
UPDATE `premium_rate_card` SET `tax`=1 ,`total`=31 WHERE `premium`=30 AND coverage = 'Cylinder Instructor'; 
UPDATE `premium_rate_card` SET `tax`=1 ,`total`=21 WHERE `premium`=20 AND coverage = 'Cylinder Instructor'; 
UPDATE `premium_rate_card` SET `tax`=1 ,`total`=11 WHERE `premium`=10 AND coverage = 'Cylinder Instructor';

UPDATE `premium_rate_card` SET `tax`=12 ,`total`=293 WHERE `premium`=281 AND coverage = 'Cylinder Insp & Inst';   
UPDATE `premium_rate_card` SET `tax`=11 ,`total`=269 WHERE `premium`=258 AND coverage = 'Cylinder Insp & Inst';   
UPDATE `premium_rate_card` SET `tax`=10 ,`total`=245 WHERE `premium`=235 AND coverage = 'Cylinder Insp & Inst';   
UPDATE `premium_rate_card` SET `tax`=9 ,`total`=220 WHERE `premium`=211 AND coverage = 'Cylinder Insp & Inst';    
UPDATE `premium_rate_card` SET `tax`=8 ,`total`=196 WHERE `premium`=188 AND coverage = 'Cylinder Insp & Inst';    
UPDATE `premium_rate_card` SET `tax`=7 ,`total`=171 WHERE `premium`=164 AND coverage = 'Cylinder Insp & Inst';    
UPDATE `premium_rate_card` SET `tax`=6 ,`total`=147 WHERE `premium`=141 AND coverage = 'Cylinder Insp & Inst';    
UPDATE `premium_rate_card` SET `tax`=5 ,`total`=123 WHERE `premium`=118 AND coverage = 'Cylinder Insp & Inst';    
UPDATE `premium_rate_card` SET `tax`=4 ,`total`=98 WHERE `premium`=94 AND coverage = 'Cylinder Insp & Inst';      
UPDATE `premium_rate_card` SET `tax`=3 ,`total`=74 WHERE `premium`=71 AND coverage = 'Cylinder Insp & Inst';      
UPDATE `premium_rate_card` SET `tax`=2 ,`total`=49 WHERE `premium`=47 AND coverage = 'Cylinder Insp & Inst';      
UPDATE `premium_rate_card` SET `tax`=1 ,`total`=25 WHERE `premium`=24 AND coverage = 'Cylinder Insp & Inst';

UPDATE `premium_rate_card` SET `tax`=20 ,`total`=487 WHERE `premium`=467 AND coverage = '1M Excess';  
UPDATE `premium_rate_card` SET `tax`=18 ,`total`=447 WHERE `premium`=429 AND coverage = '1M Excess';  
UPDATE `premium_rate_card` SET `tax`=17 ,`total`=407 WHERE `premium`=390 AND coverage = '1M Excess';  
UPDATE `premium_rate_card` SET `tax`=15 ,`total`=366 WHERE `premium`=351 AND coverage = '1M Excess';  
UPDATE `premium_rate_card` SET `tax`=13 ,`total`=325 WHERE `premium`=312 AND coverage = '1M Excess';  
UPDATE `premium_rate_card` SET `tax`=12 ,`total`=285 WHERE `premium`=273 AND coverage = '1M Excess';  
UPDATE `premium_rate_card` SET `tax`=10 ,`total`=244 WHERE `premium`=234 AND coverage = '1M Excess';  
UPDATE `premium_rate_card` SET `tax`=8 ,`total`=203 WHERE `premium`=195 AND coverage = '1M Excess';   
UPDATE `premium_rate_card` SET `tax`=7 ,`total`=163 WHERE `premium`=156 AND coverage = '1M Excess';   
UPDATE `premium_rate_card` SET `tax`=5 ,`total`=122 WHERE `premium`=117 AND coverage = '1M Excess';   
UPDATE `premium_rate_card` SET `tax`=3 ,`total`=81 WHERE `premium`=78 AND coverage = '1M Excess';     
UPDATE `premium_rate_card` SET `tax`=2 ,`total`=41 WHERE `premium`=39 AND coverage = '1M Excess';

UPDATE `premium_rate_card` SET `tax`=40 ,`total`=976 WHERE `premium`=936 AND coverage = '2M Excess';  
UPDATE `premium_rate_card` SET `tax`=37 ,`total`=895 WHERE `premium`=858 AND coverage = '2M Excess';  
UPDATE `premium_rate_card` SET `tax`=34 ,`total`=814 WHERE `premium`=780 AND coverage = '2M Excess';  
UPDATE `premium_rate_card` SET `tax`=30 ,`total`=732 WHERE `premium`=702 AND coverage = '2M Excess';  
UPDATE `premium_rate_card` SET `tax`=27 ,`total`=651 WHERE `premium`=624 AND coverage = '2M Excess';  
UPDATE `premium_rate_card` SET `tax`=23 ,`total`=569 WHERE `premium`=546 AND coverage = '2M Excess';  
UPDATE `premium_rate_card` SET `tax`=20 ,`total`=488 WHERE `premium`=468 AND coverage = '2M Excess';  
UPDATE `premium_rate_card` SET `tax`=17 ,`total`=407 WHERE `premium`=390 AND coverage = '2M Excess';  
UPDATE `premium_rate_card` SET `tax`=13 ,`total`=325 WHERE `premium`=312 AND coverage = '2M Excess';  
UPDATE `premium_rate_card` SET `tax`=10 ,`total`=244 WHERE `premium`=234 AND coverage = '2M Excess';  
UPDATE `premium_rate_card` SET `tax`=7 ,`total`=163 WHERE `premium`=156 AND coverage = '2M Excess';   
UPDATE `premium_rate_card` SET `tax`=3 ,`total`=81 WHERE `premium`=78 AND coverage = '2M Excess';

UPDATE `premium_rate_card` SET `tax`=52 ,`total`=1267 WHERE `premium`=1215 AND coverage = '3M Excess';      
UPDATE `premium_rate_card` SET `tax`=48 ,`total`=1162 WHERE `premium`=1114 AND coverage = '3M Excess';      
UPDATE `premium_rate_card` SET `tax`=44 ,`total`=1057 WHERE `premium`=1013 AND coverage = '3M Excess';      
UPDATE `premium_rate_card` SET `tax`=39 ,`total`=951 WHERE `premium`=912 AND coverage = '3M Excess';  
UPDATE `premium_rate_card` SET `tax`=35 ,`total`=845 WHERE `premium`=810 AND coverage = '3M Excess';  
UPDATE `premium_rate_card` SET `tax`=30 ,`total`=739 WHERE `premium`=709 AND coverage = '3M Excess';  
UPDATE `premium_rate_card` SET `tax`=26 ,`total`=634 WHERE `premium`=608 AND coverage = '3M Excess';  
UPDATE `premium_rate_card` SET `tax`=22 ,`total`=529 WHERE `premium`=507 AND coverage = '3M Excess';  
UPDATE `premium_rate_card` SET `tax`=17 ,`total`=422 WHERE `premium`=405 AND coverage = '3M Excess';  
UPDATE `premium_rate_card` SET `tax`=13 ,`total`=317 WHERE `premium`=304 AND coverage = '3M Excess';  
UPDATE `premium_rate_card` SET `tax`=9 ,`total`=212 WHERE `premium`=203 AND coverage = '3M Excess';   
UPDATE `premium_rate_card` SET `tax`=4 ,`total`=106 WHERE `premium`=102 AND coverage = '3M Excess';

UPDATE `premium_rate_card` SET `tax`=66 ,`total`=1592 WHERE `premium`=1526 AND coverage = '4M Excess';      
UPDATE `premium_rate_card` SET `tax`=60 ,`total`=1459 WHERE `premium`=1399 AND coverage = '4M Excess';      
UPDATE `premium_rate_card` SET `tax`=55 ,`total`=1327 WHERE `premium`=1272 AND coverage = '4M Excess';      
UPDATE `premium_rate_card` SET `tax`=49 ,`total`=1194 WHERE `premium`=1145 AND coverage = '4M Excess';      
UPDATE `premium_rate_card` SET `tax`=44 ,`total`=1062 WHERE `premium`=1018 AND coverage = '4M Excess';      
UPDATE `premium_rate_card` SET `tax`=38 ,`total`=929 WHERE `premium`=891 AND coverage = '4M Excess';  
UPDATE `premium_rate_card` SET `tax`=33 ,`total`=796 WHERE `premium`=763 AND coverage = '4M Excess';  
UPDATE `premium_rate_card` SET `tax`=27 ,`total`=663 WHERE `premium`=636 AND coverage = '4M Excess';  
UPDATE `premium_rate_card` SET `tax`=22 ,`total`=531 WHERE `premium`=509 AND coverage = '4M Excess';  
UPDATE `premium_rate_card` SET `tax`=16 ,`total`=398 WHERE `premium`=382 AND coverage = '4M Excess';  
UPDATE `premium_rate_card` SET `tax`=11 ,`total`=266 WHERE `premium`=255 AND coverage = '4M Excess';  
UPDATE `premium_rate_card` SET `tax`=6 ,`total`=134 WHERE `premium`=128 AND coverage = '4M Excess';

UPDATE `premium_rate_card` SET `tax`=147 ,`total`=3555 WHERE `premium`=3408 AND coverage = '9M Excess';     
UPDATE `premium_rate_card` SET `tax`=134 ,`total`=3258 WHERE `premium`=3124 AND coverage = '9M Excess';     
UPDATE `premium_rate_card` SET `tax`=122 ,`total`=2962 WHERE `premium`=2840 AND coverage = '9M Excess';     
UPDATE `premium_rate_card` SET `tax`=110 ,`total`=2666 WHERE `premium`=2556 AND coverage = '9M Excess';     
UPDATE `premium_rate_card` SET `tax`=98 ,`total`=2370 WHERE `premium`=2272 AND coverage = '9M Excess';      
UPDATE `premium_rate_card` SET `tax`=85 ,`total`=2073 WHERE `premium`=1988 AND coverage = '9M Excess';      
UPDATE `premium_rate_card` SET `tax`=73 ,`total`=1777 WHERE `premium`=1704 AND coverage = '9M Excess';      
UPDATE `premium_rate_card` SET `tax`=61 ,`total`=1481 WHERE `premium`=1420 AND coverage = '9M Excess';      
UPDATE `premium_rate_card` SET `tax`=49 ,`total`=1185 WHERE `premium`=1136 AND coverage = '9M Excess';      
UPDATE `premium_rate_card` SET `tax`=37 ,`total`=889 WHERE `premium`=852 AND coverage = '9M Excess';  
UPDATE `premium_rate_card` SET `tax`=24 ,`total`=592 WHERE `premium`=568 AND coverage = '9M Excess';  
UPDATE `premium_rate_card` SET `tax`=12 ,`total`=296 WHERE `premium`=284 AND coverage = '9M Excess';

UPDATE `premium_rate_card` SET `tax`=11,`padi_fee`=10 ,`total`=262 WHERE `premium`=241 AND coverage = 'UG - DM/AI to Inst';   
UPDATE `premium_rate_card` SET `tax`=9,`padi_fee`=0 ,`total`=230 WHERE `premium`=221 AND coverage = 'UG - DM/AI to Inst';     
UPDATE `premium_rate_card` SET `tax`=9,`padi_fee`=0 ,`total`=210 WHERE `premium`=201 AND coverage = 'UG - DM/AI to Inst';     
UPDATE `premium_rate_card` SET `tax`=8,`padi_fee`=0 ,`total`=189 WHERE `premium`=181 AND coverage = 'UG - DM/AI to Inst';     
UPDATE `premium_rate_card` SET `tax`=6,`padi_fee`=0 ,`total`=166 WHERE `premium`=160 AND coverage = 'UG - DM/AI to Inst';     
UPDATE `premium_rate_card` SET `tax`=6,`padi_fee`=0 ,`total`=147 WHERE `premium`=141 AND coverage = 'UG - DM/AI to Inst';     
UPDATE `premium_rate_card` SET `tax`=5,`padi_fee`=0 ,`total`=126 WHERE `premium`=121 AND coverage = 'UG - DM/AI to Inst';     
UPDATE `premium_rate_card` SET `tax`=5,`padi_fee`=0 ,`total`=105 WHERE `premium`=100 AND coverage = 'UG - DM/AI to Inst';     
UPDATE `premium_rate_card` SET `tax`=3,`padi_fee`=0 ,`total`=83 WHERE `premium`=80 AND coverage = 'UG - DM/AI to Inst'; 
UPDATE `premium_rate_card` SET `tax`=3,`padi_fee`=0 ,`total`=64 WHERE `premium`=61 AND coverage = 'UG - DM/AI to Inst'; 
UPDATE `premium_rate_card` SET `tax`=2,`padi_fee`=0 ,`total`=42 WHERE `premium`=40 AND coverage = 'UG - DM/AI to Inst'; 
UPDATE `premium_rate_card` SET `tax`=1,`padi_fee`=0 ,`total`=21 WHERE `premium`=20 AND coverage = 'UG - DM/AI to Inst';

UPDATE `premium_rate_card` SET `tax`=5,`total`=134 WHERE `premium`=129 AND coverage = 'UG - AO to DM/AI';   
UPDATE `premium_rate_card` SET `tax`=5,`total`=123 WHERE `premium`=118 AND coverage = 'UG - AO to DM/AI';   
UPDATE `premium_rate_card` SET `tax`=4,`total`=111 WHERE `premium`=107 AND coverage = 'UG - AO to DM/AI';   
UPDATE `premium_rate_card` SET `tax`=4,`total`=100 WHERE `premium`=96 AND coverage = 'UG - AO to DM/AI';    
UPDATE `premium_rate_card` SET `tax`=4,`total`=90 WHERE `premium`=86 AND coverage = 'UG - AO to DM/AI';     
UPDATE `premium_rate_card` SET `tax`=3,`total`=78 WHERE `premium`=75 AND coverage = 'UG - AO to DM/AI';     
UPDATE `premium_rate_card` SET `tax`=3,`total`=67 WHERE `premium`=64 AND coverage = 'UG - AO to DM/AI';     
UPDATE `premium_rate_card` SET `tax`=2,`total`=56 WHERE `premium`=54 AND coverage = 'UG - AO to DM/AI';     
UPDATE `premium_rate_card` SET `tax`=2,`total`=45 WHERE `premium`=43 AND coverage = 'UG - AO to DM/AI';     
UPDATE `premium_rate_card` SET `tax`=1,`total`=33 WHERE `premium`=32 AND coverage = 'UG - AO to DM/AI';     
UPDATE `premium_rate_card` SET `tax`=1,`total`=23 WHERE `premium`=22 AND coverage = 'UG - AO to DM/AI';     
UPDATE `premium_rate_card` SET `tax`=0,`total`=11 WHERE `premium`=11 AND coverage = 'UG - AO to DM/AI';

UPDATE `premium_rate_card` SET `tax`=16,`padi_fee`=10 ,`total`=396 WHERE `premium`=370 AND coverage = 'UG - AO to Inst';      
UPDATE `premium_rate_card` SET `tax`=14,`padi_fee`=0 ,`total`=353 WHERE `premium`=339 AND coverage = 'UG - AO to Inst'; 
UPDATE `premium_rate_card` SET `tax`=13,`padi_fee`=0 ,`total`=321 WHERE `premium`=308 AND coverage = 'UG - AO to Inst'; 
UPDATE `premium_rate_card` SET `tax`=12,`padi_fee`=0 ,`total`=289 WHERE `premium`=277 AND coverage = 'UG - AO to Inst'; 
UPDATE `premium_rate_card` SET `tax`=10,`padi_fee`=0 ,`total`=256 WHERE `premium`=246 AND coverage = 'UG - AO to Inst'; 
UPDATE `premium_rate_card` SET `tax`=9,`padi_fee`=0 ,`total`=225 WHERE `premium`=216 AND coverage = 'UG - AO to Inst';  
UPDATE `premium_rate_card` SET `tax`=8,`padi_fee`=0 ,`total`=193 WHERE `premium`=185 AND coverage = 'UG - AO to Inst';  
UPDATE `premium_rate_card` SET `tax`=7,`padi_fee`=0 ,`total`=161 WHERE `premium`=154 AND coverage = 'UG - AO to Inst';  
UPDATE `premium_rate_card` SET `tax`=5,`padi_fee`=0 ,`total`=128 WHERE `premium`=123 AND coverage = 'UG - AO to Inst';  
UPDATE `premium_rate_card` SET `tax`=4,`padi_fee`=0 ,`total`=97 WHERE `premium`=93 AND coverage = 'UG - AO to Inst';    
UPDATE `premium_rate_card` SET `tax`=3,`padi_fee`=0 ,`total`=65 WHERE `premium`=62 AND coverage = 'UG - AO to Inst';    
UPDATE `premium_rate_card` SET `tax`=1,`padi_fee`=0 ,`total`=32 WHERE `premium`=31 AND coverage = 'UG - AO to Inst';

UPDATE `premium_rate_card` SET `total`=134 WHERE `premium`=134 AND coverage = 'UG - Intl DM to Intl Inst';  
UPDATE `premium_rate_card` SET `total`=123 WHERE `premium`=123 AND coverage = 'UG - Intl DM to Intl Inst';  
UPDATE `premium_rate_card` SET `total`=112 WHERE `premium`=112 AND coverage = 'UG - Intl DM to Intl Inst';  
UPDATE `premium_rate_card` SET `total`=101 WHERE `premium`=101 AND coverage = 'UG - Intl DM to Intl Inst';  
UPDATE `premium_rate_card` SET `total`=90 WHERE `premium`=90 AND coverage = 'UG - Intl DM to Intl Inst';    
UPDATE `premium_rate_card` SET `total`=78 WHERE `premium`=78 AND coverage = 'UG - Intl DM to Intl Inst';    
UPDATE `premium_rate_card` SET `total`=67 WHERE `premium`=67 AND coverage = 'UG - Intl DM to Intl Inst';    
UPDATE `premium_rate_card` SET `total`=56 WHERE `premium`=56 AND coverage = 'UG - Intl DM to Intl Inst';    
UPDATE `premium_rate_card` SET `total`=45 WHERE `premium`=45 AND coverage = 'UG - Intl DM to Intl Inst';    
UPDATE `premium_rate_card` SET `total`=33 WHERE `premium`=33 AND coverage = 'UG - Intl DM to Intl Inst';    
UPDATE `premium_rate_card` SET `total`=22 WHERE `premium`=22 AND coverage = 'UG - Intl DM to Intl Inst';    
UPDATE `premium_rate_card` SET `total`=11 WHERE `premium`=11 AND coverage = 'UG - Intl DM to Intl Inst';

UPDATE `premium_rate_card` SET `total`=233 WHERE `premium`=233 AND coverage = 'UG - Intl AO to Intl Inst';  
UPDATE `premium_rate_card` SET `total`=214 WHERE `premium`=214 AND coverage = 'UG - Intl AO to Intl Inst';  
UPDATE `premium_rate_card` SET `total`=195 WHERE `premium`=195 AND coverage = 'UG - Intl AO to Intl Inst';  
UPDATE `premium_rate_card` SET `total`=175 WHERE `premium`=175 AND coverage = 'UG - Intl AO to Intl Inst';  
UPDATE `premium_rate_card` SET `total`=156 WHERE `premium`=156 AND coverage = 'UG - Intl AO to Intl Inst';  
UPDATE `premium_rate_card` SET `total`=136 WHERE `premium`=136 AND coverage = 'UG - Intl AO to Intl Inst';  
UPDATE `premium_rate_card` SET `total`=117 WHERE `premium`=117 AND coverage = 'UG - Intl AO to Intl Inst';  
UPDATE `premium_rate_card` SET `total`=97 WHERE `premium`=97 AND coverage = 'UG - Intl AO to Intl Inst';    
UPDATE `premium_rate_card` SET `total`=78 WHERE `premium`=78 AND coverage = 'UG - Intl AO to Intl Inst';    
UPDATE `premium_rate_card` SET `total`=58 WHERE `premium`=58 AND coverage = 'UG - Intl AO to Intl Inst';    
UPDATE `premium_rate_card` SET `total`=39 WHERE `premium`=39 AND coverage = 'UG - Intl AO to Intl Inst';    
UPDATE `premium_rate_card` SET `total`=19 WHERE `premium`=19 AND coverage = 'UG - Intl AO to Intl Inst';

UPDATE `premium_rate_card` SET `total`=99 WHERE `premium`=99 AND coverage = 'UG - Intl AO to Intl DM/AI';   
UPDATE `premium_rate_card` SET `total`=91 WHERE `premium`=91 AND coverage = 'UG - Intl AO to Intl DM/AI';   
UPDATE `premium_rate_card` SET `total`=83 WHERE `premium`=83 AND coverage = 'UG - Intl AO to Intl DM/AI';   
UPDATE `premium_rate_card` SET `total`=74 WHERE `premium`=74 AND coverage = 'UG - Intl AO to Intl DM/AI';   
UPDATE `premium_rate_card` SET `total`=66 WHERE `premium`=66 AND coverage = 'UG - Intl AO to Intl DM/AI';   
UPDATE `premium_rate_card` SET `total`=58 WHERE `premium`=58 AND coverage = 'UG - Intl AO to Intl DM/AI';   
UPDATE `premium_rate_card` SET `total`=50 WHERE `premium`=50 AND coverage = 'UG - Intl AO to Intl DM/AI';   
UPDATE `premium_rate_card` SET `total`=41 WHERE `premium`=41 AND coverage = 'UG - Intl AO to Intl DM/AI';   
UPDATE `premium_rate_card` SET `total`=33 WHERE `premium`=33 AND coverage = 'UG - Intl AO to Intl DM/AI';   
UPDATE `premium_rate_card` SET `total`=25 WHERE `premium`=25 AND coverage = 'UG - Intl AO to Intl DM/AI';   
UPDATE `premium_rate_card` SET `total`=17 WHERE `premium`=17 AND coverage = 'UG - Intl AO to Intl DM/AI';   
UPDATE `premium_rate_card` SET `total`=8 WHERE `premium`=8 AND coverage = 'UG - Intl AO to Intl DM/AI';


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



INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Emergency First Response', 'liabilityCoverage5000000', 'liabilityCoverage5000000', '2020-07-01 00:00:00', '2021-06-30 00:00:00', 531.00);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Emergency First Response', 'liabilityCoverage4000000', 'liabilityCoverage4000000', '2020-07-01 00:00:00', '2021-06-30 00:00:00', 434.00);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Emergency First Response', 'liabilityCoverage2000000', 'liabilityCoverage2000000', '2020-07-01 00:00:00', '2021-06-30 00:00:00', 396.00);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Emergency First Response', 'liabilityCoverage1000000', 'liabilityCoverage1000000', '2020-07-01 00:00:00', '2021-06-30 00:00:00', 332.00);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Emergency First Response', 'notSelected', 'notSelected', '2020-07-01 00:00:00', '2021-06-30 00:00:00', 0.00);


INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Emergency First Response', 'liabilityCoverage5000000', 'liabilityCoverage5000000', '2020-10-01 00:00:00', '2021-06-30 00:00:00', 402.00);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Emergency First Response', 'liabilityCoverage4000000', 'liabilityCoverage4000000', '2020-10-01 00:00:00', '2021-06-30 00:00:00', 329.00);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Emergency First Response', 'liabilityCoverage2000000', 'liabilityCoverage2000000', '2020-10-01 00:00:00', '2021-06-30 00:00:00', 300.00);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Emergency First Response', 'liabilityCoverage1000000', 'liabilityCoverage1000000', '2020-10-01 00:00:00', '2021-06-30 00:00:00', 252.00);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Emergency First Response', 'notSelected', 'notSelected', '2020-10-01 00:00:00', '2021-06-30 00:00:00', 0.00);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Emergency First Response', 'liabilityCoverage5000000', 'liabilityCoverage5000000', '2021-01-01 00:00:00', '2021-06-30 00:00:00', 272.00);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Emergency First Response', 'liabilityCoverage4000000', 'liabilityCoverage4000000', '2021-01-01 00:00:00', '2021-06-30 00:00:00', 223.00);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Emergency First Response', 'liabilityCoverage2000000', 'liabilityCoverage2000000', '2021-01-01 00:00:00', '2021-06-30 00:00:00', 204.00);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Emergency First Response', 'liabilityCoverage1000000', 'liabilityCoverage1000000', '2021-01-01 00:00:00', '2021-06-30 00:00:00', 172.00);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Emergency First Response', 'notSelected', 'notSelected', '2021-01-01 00:00:00', '2021-06-30 00:00:00', 0.00);

INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Emergency First Response', 'liabilityCoverage5000000', 'liabilityCoverage5000000', '2021-04-01 00:00:00', '2021-06-30 00:00:00', 141.00);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Emergency First Response', 'liabilityCoverage4000000', 'liabilityCoverage4000000', '2021-04-01 00:00:00', '2021-06-30 00:00:00', 117.00);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Emergency First Response', 'liabilityCoverage2000000', 'liabilityCoverage2000000', '2021-04-01 00:00:00', '2021-06-30 00:00:00', 108.00);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Emergency First Response', 'liabilityCoverage1000000', 'liabilityCoverage1000000', '2021-04-01 00:00:00', '2021-06-30 00:00:00', 91.00);
INSERT INTO `premium_rate_card` (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`) VALUES ('Emergency First Response', 'notSelected', 'notSelected', '2021-04-01 00:00:00', '2021-06-30 00:00:00', 0.00);

