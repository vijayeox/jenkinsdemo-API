UPDATE premium_rate_card set `key`="cylinderInspectorOrCylinderInspectionInstructorDeclined" where `key`='cylinderInspectorOrInstructorDeclined';

UPDATE premium_rate_card set `coverage`="Cylinder Inspector & Cylinder Inspection Instructor - Declined" where `coverage`='Cylinder Inspector & Instructor - Declined';

UPDATE premium_rate_card set `coverage`="Cylinder Inspection Instructor" where `coverage`='Cylinder Instructor';

UPDATE premium_rate_card set `key`="cylinderInspectionInstructor" where `key`='cylinderInstructor';


UPDATE premium_rate_card set `coverage`="Excess Liability Coverage ($1,000,000)" where `coverage`='1M Excess';
UPDATE premium_rate_card set `coverage`="Excess Liability Coverage ($2,000,000)" where `coverage`='2M Excess';
UPDATE premium_rate_card set `coverage`="Excess Liability Coverage ($3,000,000)" where `coverage`='3M Excess';
UPDATE premium_rate_card set `coverage`="Excess Liability Coverage ($4,000,000)" where `coverage`='4M Excess';
UPDATE premium_rate_card set `coverage`="Excess Liability Coverage ($9,000,000)" where `coverage`='9M Excess';


UPDATE premium_rate_card set `coverage`="Excess Liability Coverage ($1,000,000)" where `coverage`='1M';
UPDATE premium_rate_card set `coverage`="Excess Liability Coverage ($2,000,000)" where `coverage`='2M';
UPDATE premium_rate_card set `coverage`="Excess Liability Coverage ($3,000,000)" where `coverage`='3M';
UPDATE premium_rate_card set `coverage`="Excess Liability Coverage ($4,000,000)" where `coverage`='4M';
UPDATE premium_rate_card set `coverage`="Excess Liability Coverage ($9,000,000)" where `coverage`='9M';



UPDATE premium_rate_card set `previous_key`="cylinderInspectionInstructor" where `previous_key`='cylinderInstructor';
UPDATE premium_rate_card set `previous_key`="cylinderInspectorOrCylinderInspectionInstructorDeclined" where `previous_key`='cylinderInspectorOrInstructorDeclined';