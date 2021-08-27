CREATE TABLE IF NOT EXISTS  coverage_options (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`padi_rating` varchar(100) NULL,
	`coverage_level` varchar(100) NULL,
	`coverage_name` varchar(100) NULL,
              PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARSET=latin1
COLLATE=latin1_swedish_ci;

INSERT INTO coverage_options (padi_rating,coverage_level,coverage_name) VALUES 
('OWSI','instructor','Instructor')
,('OWSI','nonteachingSupervisoryInstructor','Nonteaching / Supervisory Instructor (4)')
,('OWSI','freediveInstructor','Free Diver Instructor')
,('OWSI','retiredInstructor','Retired Instructor (4)')
,('OWSI','internationalInstructor','International Instructor (3)')
,('OWSI','internationalNonteachingSupervisoryInstructor','International Nonteaching / Supervisory Instructor (3)(4)')
,('MI','instructor','Instructor')
,('MI','nonteachingSupervisoryInstructor','Nonteaching / Supervisory Instructor (4)')
,('MI','freediveInstructor','Free Diver Instructor')
,('MI','retiredInstructor','Retired Instructor (4)')
;
INSERT INTO coverage_options (padi_rating,coverage_level,coverage_name) VALUES 
('MI','internationalInstructor','International Instructor (3)')
,('MI','internationalNonteachingSupervisoryInstructor','International Nonteaching / Supervisory Instructor (3)(4)')
,('MSDT','instructor','Instructor')
,('MSDT','nonteachingSupervisoryInstructor','Nonteaching / Supervisory Instructor (4)')
,('MSDT','freediveInstructor','Free Diver Instructor')
,('MSDT','retiredInstructor','Retired Instructor (4)')
,('MSDT','internationalInstructor','International Instructor (3)')
,('MSDT','internationalNonteachingSupervisoryInstructor','International Nonteaching / Supervisory Instructor (3)(4)')
,('UI','instructor','Instructor')
,('UI','nonteachingSupervisoryInstructor','Nonteaching / Supervisory Instructor (4)')
;
INSERT INTO coverage_options (padi_rating,coverage_level,coverage_name) VALUES 
('UI','freediveInstructor','Free Diver Instructor')
,('UI','retiredInstructor','Retired Instructor (4)')
,('UI','internationalInstructor','International Instructor (3)')
,('UI','internationalNonteachingSupervisoryInstructor','International Nonteaching / Supervisory Instructor (3)(4)')
,('AI','assistantInstructor','Assistant Instructor')
,('AI','divemasterAssistantInstructorAssistingOnly','Divemaster / Assistant Instructor Assisting Only (2)')
,('AI','internationalAssistantInstructor','International Assistant Instructor (3)')
,('AI','internationalDivemasterAssistantInstructorAssistingOnly','International Divemaster / Assistant Instructor Assisting Only (2)(3)')
,('AIN','assistantInstructor','Assistant Instructor')
,('AIN','divemasterAssistantInstructorAssistingOnly','Divemaster / Assistant Instructor Assisting Only (2)')
;
INSERT INTO coverage_options (padi_rating,coverage_level,coverage_name) VALUES 
('AIN','internationalAssistantInstructor','International Assistant Instructor (3)')
,('AIN','internationalDivemasterAssistantInstructorAssistingOnly','International Divemaster / Assistant Instructor Assisting Only (2)(3)')
,('DM','divemaster','Divemaster')
,('DM','divemasterAssistantInstructorAssistingOnly','Divemaster / Assistant Instructor Assisting Only (2)')
,('DM','internationalDivemaster','International Divemaster (3)')
,('DM','internationalDivemasterAssistantInstructorAssistingOnly','International Divemaster / Assistant Instructor Assisting Only (2)(3)')
,('LFSI','swimInstructor','Swim Instructor')
,('FDIC','freediveInstructor','Free Diver Instructor')
,('PM','noCoverageSelected','No Coverage Applicable')
,('AL','retiredInstructor','Retired Instructor (4)')
;