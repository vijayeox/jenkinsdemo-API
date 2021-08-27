ALTER TABLE coverage_options ADD COLUMN `category` VARCHAR(200) DEFAULT NULL;


INSERT INTO coverage_options (padi_rating,coverage_level,coverage_name,category) VALUES 
('OWSI','instructor','Instructor','GROUP')
,('OWSI','nonteachingSupervisoryInstructor','Nonteaching / Supervisory Instructor','GROUP')
,('OWSI','freediveInstructor','Free Diver Instructor','GROUP')
,('OWSI','retiredInstructor','Retired Instructor','GROUP')
;

INSERT INTO coverage_options (padi_rating,coverage_level,coverage_name,category) VALUES 
('MI','instructor','Instructor','GROUP')
,('MI','nonteachingSupervisoryInstructor','Nonteaching / Supervisory Instructor','GROUP')
,('MI','freediveInstructor','Free Diver Instructor','GROUP')
,('MI','retiredInstructor','Retired Instructor','GROUP')
;

INSERT INTO coverage_options (padi_rating,coverage_level,coverage_name,category) VALUES 
('MSDT','instructor','Instructor','GROUP')
,('MSDT','nonteachingSupervisoryInstructor','Nonteaching / Supervisory Instructor','GROUP')
,('MSDT','freediveInstructor','Free Diver Instructor','GROUP')
,('MSDT','retiredInstructor','Retired Instructor','GROUP')
;

INSERT INTO coverage_options (padi_rating,coverage_level,coverage_name,category) VALUES 
('UI','instructor','Instructor','GROUP')
,('UI','nonteachingSupervisoryInstructor','Nonteaching / Supervisory Instructor','GROUP')
,('UI','freediveInstructor','Free Diver Instructor','GROUP')
,('UI','retiredInstructor','Retired Instructor','GROUP')
;

INSERT INTO coverage_options (padi_rating,coverage_level,coverage_name,category) VALUES ('AL','retiredInstructor','Retired Instructor','GROUP');

INSERT INTO coverage_options (padi_rating,coverage_level,coverage_name,category) VALUES 
('AI','assistantInstructor','Assistant Instructor','GROUP')
,('AI','divemasterAssistantInstructorAssistantOnly','Divemaster / Assistant Instructor ASSISTING ONLY','GROUP');

INSERT INTO coverage_options (padi_rating,coverage_level,coverage_name,category) VALUES 
('AIN','assistantInstructor','Assistant Instructor','GROUP')
,('AIN','divemasterAssistantInstructorAssistantOnly','Divemaster / Assistant Instructor ASSISTING ONLY','GROUP');

INSERT INTO coverage_options (padi_rating,coverage_level,coverage_name,category) VALUES 
('DM','divemaster','Divemaster','GROUP')
,('DM','divemasterAssistantInstructorAssistantOnly','Divemaster / Assistant Instructor ASSISTING ONLY','GROUP');

INSERT INTO coverage_options (padi_rating,coverage_level,coverage_name,category) VALUES 
('EFR','emergencyFirstResponseInstructor','Emergency First Response Instructor','GROUP');

INSERT INTO coverage_options (padi_rating,coverage_level,coverage_name,category) VALUES 
('LFSI','swimInstructor','Swim Instructor','GROUP');

INSERT INTO coverage_options (padi_rating,coverage_level,coverage_name,category) VALUES 
('FDIC','freediveInstructor','Free Diver Instructor','GROUP');