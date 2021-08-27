INSERT INTO ox_query(uuid,name,datasource_id,configuration,ispublic,created_by,date_created,org_id,isdeleted,version) VALUES (UUID(),'Hub Submissions',(SELECT id from ox_datasource where name LIKE 'OxzionElasticDs'),'{"app_name":"diveinsurance","operation":"count","field":"id","filter":[["end_date",">","now"]]}',	1,(SELECT id from ox_user where username like 'testhub07.gmail.com'),NOW(),(SELECT id from ox_organization where name LIKE 'Vincencia & Buckley'),0,1);

INSERT INTO ox_query(uuid,name,datasource_id,configuration,ispublic,created_by,date_created,org_id,isdeleted,version) VALUES (UUID(),'Hub Policies',(SELECT id from ox_datasource where name LIKE 'OxzionElasticDs'),'{"app_name":"diveinsurance","filter":[["end_date",">","now"],"AND",["start_date","<","now"]],"operation":"count"}',1,(SELECT id from ox_user where username like 'testhub07.gmail.com'),NOW(),(SELECT id from ox_organization where name LIKE 'Vincencia & Buckley'),0,0);

INSERT INTO ox_query(uuid,name,datasource_id,configuration,ispublic,created_by,date_created,org_id,isdeleted,version) VALUES (UUID(),'Hub Written Premium',(SELECT id from ox_datasource where name LIKE 'OxzionElasticDs'),'{"app_name":"diveinsurance","operation":"sum","field":"amount","round":"2","date-period":"2019-07-01/2020-06-30","date_type":"start_date"}',1,(SELECT id from ox_user where username like 'testhub07.gmail.com'),NOW(),(SELECT id from ox_organization where name LIKE 'Vincencia & Buckley'),0,0);

INSERT INTO ox_widget (uuid,visualization_id,ispublic,created_by,date_created,org_id,isdeleted,name,configuration,version,expression) VALUES ('d2434992-e6a4-4fc6-9dde-d35e4da58b19',(SELECT id from ox_visualization where name like 'Aggregate value'),1,(SELECT id from ox_user where username like 'testhub07.gmail.com'),'2019-12-13 17:00:04',(SELECT id from ox_organization where name LIKE 'Vincencia & Buckley'),0,'Hub Submissions','{"numberFormat":" "}',1,'');

INSERT INTO ox_widget_query (ox_widget_id,ox_query_id,sequence,configuration) VALUES ((SELECT id from ox_widget where name LIKE 'Hub Submissions'),(SELECT id from ox_query where name LIKE 'Hub Submissions'),0,'{"filter":null, "grouping":null, "sort":null}');

INSERT INTO ox_widget (uuid,visualization_id,ispublic,created_by,date_created,org_id,isdeleted,name,configuration,version,expression) VALUES ('744fde28-e273-4042-bb92-5aa7f6d3554b',(SELECT id from ox_visualization where name like 'Aggregate value'),1,(SELECT id from ox_user where username like 'testhub07.gmail.com'),'2019-12-13 17:00:04',(SELECT id from ox_organization where name LIKE 'Vincencia & Buckley'),0,'Hub Policies','{"numberFormat":" "}',1,'');

INSERT INTO ox_widget_query (ox_widget_id,ox_query_id,sequence,configuration) VALUES ((SELECT id from ox_widget where name LIKE 'Hub Policies'),(SELECT id from ox_query where name LIKE 'Hub Policies'),0,'{"filter":null, "grouping":null, "sort":null}');

INSERT INTO ox_widget (uuid,visualization_id,ispublic,created_by,date_created,org_id,isdeleted,name,configuration,version,expression) VALUES ('1f8fc873-b729-41b6-9c2f-bed07db8f70a',(SELECT id from ox_visualization where name like 'Aggregate value'),1,(SELECT id from ox_user where username like 'testhub07.gmail.com'),'2019-12-13 17:00:04',(SELECT id from ox_organization where name LIKE 'Vincencia & Buckley'),0,'Hub Written Premium','{"numberFormat":"$ 0.00 a"}',1,'');

INSERT INTO ox_widget_query (ox_widget_id,ox_query_id,sequence,configuration) VALUES ((SELECT id from ox_widget where name LIKE 'Hub Written Premium'),(SELECT id from ox_query where name LIKE 'Hub Written Premium'),0,'{"filter":null, "grouping":null, "sort":null}');

INSERT INTO ox_dashboard (uuid,name,ispublic,description,dashboard_type,created_by,date_created,org_id,isdeleted,content,version) VALUES 
('4796e7fe-50b5-469c-9737-35f3921891ba','Vicencia and Buckley',1,'CSR Dashboard','html',(SELECT id from ox_user where username like 'testhub07.gmail.com'),NOW(),(SELECT id from ox_organization where name LIKE 'Vincencia & Buckley'),0,'<h3 style="color: rgb(170, 170, 170); font-style: italic; text-align: center;"><span style="font-family:Tahoma,Geneva,sans-serif;"></span></h3>

<div style="display: flex;flex-direction: row;/* margin: auto; */justify-content: space-around;">
<div style="background-color: #053b6d;color:$fff;padding: 10px;border: 1px solid black;width: 20%;border-radius:10px;">
<h3 style="color: rgb(170, 170, 170); font-style: italic; text-align: center;"><span style="color:#ffffff;"><span style="font-family:Tahoma,Geneva,sans-serif;"><span style="font-size:16px;"><strong>Total Submissions</strong></span></span></span></h3>

<h3 style="color: rgb(170, 170, 170); font-style: italic; text-align: center;"><span style="color:#ffffff;"><span style="font-family:Tahoma,Geneva,sans-serif;"><span style="font-size:26px;"><span class="oxzion-widget" data-oxzion-widget-id="d2434992-e6a4-4fc6-9dde-d35e4da58b19" id="id_19b60c81-b652-4089-8ce9-45a97d2e08c6">0</span></span></span></span></h3>
</div>

<div style="background-color: #053b6d;color:$fff;padding: 10px;border: 1px solid black;width: 20%;border-radius:10px;">
<h3 style="color: rgb(170, 170, 170); font-style: italic; text-align: center;"><span style="color:#ffffff;"><span style="font-family:Tahoma,Geneva,sans-serif;"><span style="font-size:16px;"><strong>Total Number Of Active Policies</strong></span></span></span></h3>

<h3 style="color: rgb(170, 170, 170); font-style: italic; text-align: center;"><span style="color:#ffffff;"><span style="font-family:Tahoma,Geneva,sans-serif;"><span style="font-size:26px;"><span class="oxzion-widget" data-oxzion-widget-id="744fde28-e273-4042-bb92-5aa7f6d3554b" id="id_f8304b5e-a0e1-41ad-bf8b-3cfae336ee40">0</span></span></span></span></h3>
</div>

<div style="background-color: #053b6d;color:$fff;padding: 10px;border: 1px solid black;width: 20%;border-radius:10px;">
<h3 style="color: rgb(170, 170, 170); font-style: italic; text-align: center;"><span style="color:#ffffff;"><span style="font-family:Tahoma,Geneva,sans-serif;"><span style="font-size:16px;"><strong>Total Written Premium</strong></span></span></span></h3>

<h3 style="color: rgb(170, 170, 170); font-style: italic; text-align: center;"><span style="color:#ffffff;"><span style="font-family:Tahoma,Geneva,sans-serif;"><span style="font-size:26px;"><span class="oxzion-widget" data-oxzion-widget-id="1f8fc873-b729-41b6-9c2f-bed07db8f70a" id="id_774eab82-74a4-480f-bd5f-549e04dcddf9">$ 0.00 </span></span></span></span></h3>
</div>
</div>
',1)
;

--This is to be modified to properly point to the right end point and use the right core based on environment and right org_id
update ox_datasource set configuration = '{"data": {"user": "elastic","password": "changeme","serveraddress": "13.52.224.89","port": "9200", "core":"hub_staging", "type":"type", "scheme":"http"}}', org_id = 1835 where name LIKE 'OxzionElasticDs';
