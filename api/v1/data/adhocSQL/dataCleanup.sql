-- BELOW SCRIPTS ARE ONLY FOR CLEANING UP QA3. PLEAASE DO NOT RUN IT IN FRESH INSTANCE OR PRODUCTION

UPDATE ox_indexed_file_attribute oxia 
INNER JOIN ox_field oxf ON oxf.id = oxia.field_id
INNER JOIN ox_app oxa ON oxa.id = oxf.app_id
SET oxia.field_value_date = CONCAT(SUBSTRING(oxia.field_value_text,1,10), ' 00:00:00'),
    oxia.field_value_type = 'DATE',
    oxia.field_value_text = NULL
WHERE oxf.name IN ('startDate','endDate') 
AND oxa.uuid = '8a02bf61-6d32-443b-bd6b-12c899f186f8'
AND oxia.field_value_type = 'TEXT'
AND LENGTH(oxia.field_value_TEXT) > 10;


UPDATE ox_indexed_file_attribute oxia 
INNER JOIN ox_field oxf ON oxf.id = oxia.field_id
INNER JOIN ox_app oxa ON oxa.id = oxf.app_id
SET oxia.field_value_date = CONCAT(SUBSTRING(oxia.field_value_text,7,4),'-',SUBSTRING(oxia.field_value_text,4,2),'-', SUBSTRING(oxia.field_value_text,1,2),' 00:00:00'),
    oxia.field_value_type = 'DATE',
    oxia.field_value_text = NULL
WHERE oxf.name IN ('startDate','endDate') 
AND oxa.uuid = '8a02bf61-6d32-443b-bd6b-12c899f186f8'
AND oxia.field_value_type = 'TEXT'
AND LENGTH(oxia.field_value_TEXT) = 10;
