INSERT INTO ox_query(uuid,name,datasource_id,configuration,ispublic,created_by,date_created,org_id,isdeleted,version) VALUES (UUID(),'Hub Submissions',(SELECT id from ox_datasource where name LIKE 'OxzionElasticDs'),'{"app_name":"diveinsurance","filter":[["workflow_name","==","New Policy"],"AND",["end_date",">","now"]],"operation":"count","field":"workflow_name"}',1,1,NOW(),(SELECT id from ox_organization where name LIKE 'Vincencia & Buckley'),0,0);

INSERT INTO ox_query(uuid,name,datasource_id,configuration,ispublic,created_by,date_created,org_id,isdeleted,version) VALUES (UUID(),'Hub Policies',(SELECT id from ox_datasource where name LIKE 'OxzionElasticDs'),'{"app_name":"diveinsurance","filter":[["end_date",">","now"]],"operation":"count","field":"entity_id"}',1,1,NOW(),(SELECT id from ox_organization where name LIKE 'Vincencia & Buckley'),0,0);

INSERT INTO ox_query(uuid,name,datasource_id,configuration,ispublic,created_by,date_created,org_id,isdeleted,version) VALUES (UUID(),'Hub Written Premium',(SELECT id from ox_datasource where name LIKE 'OxzionElasticDs'),'{"app_name":"diveinsurance","operation":"sum","field":"total","round":"2"}',1,1,NOW(),(SELECT id from ox_organization where name LIKE 'Vincencia & Buckley'),0,0);



