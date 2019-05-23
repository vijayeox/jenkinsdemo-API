<div align="center">
  <h1>OXZION-3.0 BUILD AND DEPLOY</h1>
  <p>
    To package and deploy oxzion-3.0 to production.
  </p>
  <br>
</div>

-------------------------

<h2>USAGE:</h2>

-------------------------

- Use the script **_build.sh_** to build and package and **_deploy.sh_** to deploy.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_[Note: The deploy script is for production server]_
- Use the script **_buildimages.sh_** to build all the docker images in oxzion3.0.
- Use the script **_freshsetup.sh_** to do a fresh setup.

-------------------------

<h2>TO RUN THE SCRIPT</h2>

--------------------------

$ bash **_scriptname.sh_**

-------------------------

<h2>SETUP</h2>

-------------------------
<h3>Step 1: Install Software Prerequisites</h3>

-------------------------

<h4>Node.js 8.x: </h4>

To learn how to install Node.js [click here.](https://nodesource.com/blog/installing-node-js-8-tutorial-linux-via-package-manager/)

<h4> PhP 7.x: </h4>

To learn how to install PhP [click here.](https://tecadmin.net/install-php-7-on-ubuntu/)

<h4> Apache 2.4+: </h4>

To learn how to install Apache [click here.](https://www.digitalocean.com/community/tutorials/how-to-install-the-apache-web-server-on-ubuntu-18-04-quickstart)

<h4>MySql 5.x: </h4>

To learn how to install MySql [click here.](https://www.digitalocean.com/community/tutorials/how-to-install-mysql-on-ubuntu-16-04)

<h4>Java JDK 8.x: </h4>

To learn how to install Java 8 [click here.](https://www.digitalocean.com/community/tutorials/how-to-install-java-with-apt-get-on-ubuntu-16-04)

<h4> Docker: </h4>

To learn how to install Docker [click here.](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-on-ubuntu-18-04)

-------------------------

<h3>Step 2: A one-time database setup is required for the following integrations:

-------------------------

<h4>API</h4>

- Use the dump file **_schema.sql_** and **_data.sql_** in **_api/v1/data/schema_** for database creation.
- A Database Migration should be performed by using the following command &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_[Note: You should be in the oxzion3.0 root folder to perform the command]_
>php integrations/orocrm/bin/console oro:install --env=prod --timeout=30000 --application-url="http://localhost:8075/crm/public" --organization-name="Vantage Agora" --user-name="admin" --user-email="admin@example.com" --user-firstname="Admin" --user-lastname="User" --user-password="admin" --language=en --formatting-code=en_US

<h4>OroCRM</h4>

- Create a database  **_oro_crm_** with a user **_crmuser_** and granting all previleges to the user in database.
-A Database migration should be perform using the following commannd &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_[Note: You should be in the oxzion3.0 root folder to perform the command]_
>api/v1/migrations migrate
<h4>Mattermost</h4>

- Create a database **_mattermost_test_** with user **_mmuser_** and granting all previleges to the user to the database.

- Change configurations for the database in **_mattermost/mattermost-server/config/default.json_** under **_SqlSettings_**.

<h4>Calendar</h4>

- Create calendar database by executing **_pec_db_mysql.sql_** dump file from **_eventcalendar/SampleEventDB_** in MySQL.

-------------------------
<h3>Step 3: Setup Environment files</h3>

-------------------------
- A .env file is required in **_Calendar_**, **_Chat_**, **_CRM_**, **_Mail_**, **_MailAdmin_**, and  **_Task_**.

-------------------------

<h3>EXTRAS</h3>

-------------------------

- To Learn how to make a service [click here.](https://dzone.com/articles/run-your-java-application-as-a-service-on-ubuntu)
