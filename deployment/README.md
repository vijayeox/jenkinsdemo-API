<div align="center">
  <h1>OXZION-3.0 BUILD AND DEPLOY</h1>
  <p>
    To package and deploy oxzion-3.0 to production.
  </p>
  <br>
</div>

-------------------------
##USAGE:
-------------------------

- Use the script **_build.sh_** to build and package and **_deploy.sh_** to deploy.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;								[Note: The deploy script is for production server]
<br>
- Use the script **_buildimages.sh_** to build all the docker images in oxzion3.0.

-------------------------
##TO RUN THE SCRIPT
--------------------------

$ bash build.sh

or

$ bash deploy.sh

-------------------------
##NOTE: - A one-time database setup is required for the following the following integrations:
-------------------------

#### - API

- Use the dump file **_schema.sql_** and **_data.sql_** in **_api/v1/data/schema_** for database creation.

#### - OROCRM

- Create a database  **_oro_crm_** with a user **_crmuser_** and granting all previleges to the user in database.

#### - MATTERMOST
- Create a database **_mattermost_test_** with user **_mmuser_** and granting all previleges to the user to the database.
- Change configurations for the database in **_mattermost/mattermost-server/config/default.json_** under **_SqlSettings_**.

#### - CALENDAR

- Create calendar database by executing **_pec_db_mysql.sql_** dump file from **_eventcalendar/SampleEventDB_** in MySQL.


-------------------------
##EXTRAS
-------------------------

- To Learn how to make a service [click here.](https://dzone.com/articles/run-your-java-application-as-a-service-on-ubuntu)

-------------------------