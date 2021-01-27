CREATE OR REPLACE VIEW AssignmentsFollowUps AS
SELECT `of`.uuid, `of`.`entity_id`AS`entityId`,`of`.start_date,`of`.end_Date,`of`.`rygStatus`,`of`.fileTitle, `oxu`.uuid as `assigneeId`, `oxu`.username as `assigneeUser`, `cu`.uuid as `createdUserId`, `cu`.username as `createdUser`, `of`.account_id
From ox_file `of`
inner join ox_file_assignee `ofa` on `ofa`.file_id = `of`.id and and `ofa`.assignee=1
INNER join ox_user `oxu` on `oxu`.id = `ofa`.user_id
inner join ox_user `cu` on `cu`.id = `of`.`created_by`