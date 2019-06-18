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

This script take 3 parameters to run.
a)Build Name like `api` `calendar`
b)Server name
c)Identity File Path(PEM/PPK)

For example

$ bash **_build.sh calendar abc@xyz.com ~/.ssh/abc.pem_**

-------------------------

<h2>SETUP</h2>

-------------------------
<h3>Step 1: Install Software Prerequisites</h3>

-------------------------

<h4>Node.js 8.x: </h4>

To learn how to install Node.js [click here.](https://nodesource.com/blog/installing-node-js-8-tutorial-linux-via-package-manager/)

<h4> PhP 7.x: </h4>

To learn how to install PhP [click here.](https://tecadmin.net/install-php-7-on-ubuntu/)
Set the memory limit in php.ini to atleast 512MB and restart apache

install the following extensions
sudo apt install php-curl

<h4> Apache 2.4+: </h4>

To learn how to install Apache [click here.](https://www.digitalocean.com/community/tutorials/how-to-install-the-apache-web-server-on-ubuntu-18-04-quickstart)

<h4>MySql 5.x: </h4>

To learn how to install MySql [click here.](https://www.digitalocean.com/community/tutorials/how-to-install-mysql-on-ubuntu-16-04)

<h4>Java JDK 8.x: </h4>

To learn how to install Java 8 [click here.](https://www.digitalocean.com/community/tutorials/how-to-install-java-with-apt-on-ubuntu-18-04)

<h4> Docker: </h4>

To learn how to install Docker [click here.](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-on-ubuntu-18-04)

-------------------------
<h3>Create oxzion User:
>sudo adduser oxzion --home /opt/oxzion --shell /usr/sbin/nologin --disabled-login
>sudo mkdir /opt/oxzion

<h3>Data Folder setup:
>sudo mkdir -p /var/lib/oxzion/vfs
>sudo chown oxzion:oxzion -R /var/lib/oxzion/vfs
>sudo ln -s /var/lib/oxzion/vfs /opt/oxzion/view/vfs
>sudo mkdir -p /var/log/oxzion/api
>sudo mkdir -p /var/www/api/data
>sudo chown www-data:www-data /var/log/oxzion/api
>sudo mkdir -p /var/lib/oxzion/api/cache
>sudo mkdir -p /var/lib/oxzion/api/uploads
>sudo chown www-data:www-data /var/lib/oxzion/api
>sudo ln -s /var/log/oxzion/api /var/www/api/logs
>sudo ln -s /var/lib/oxzion/api/cache /var/www/api/data/cache
>sudo ln -s /var/lib/oxzion/api/uploads /var/www/api/data/uploads
>sudo mkdir -p /var/lib/oxzion/rainloop
>sudo chown www-data:www-data -R /var/lib/oxzion/rainloop
>sudo mkdir -p /var/www/rainloop
>sudo ln -s /var/lib/oxzion/rainloop /var/www/rainloop/data
>sudo chown www-data:www-data -R /var/www/rainloop
>sudo mkdir -p /var/log/oxzion/crm
>sudo chown www-data:www-data -R /var/log/oxzion/crm
>sudo mkdir -p /var/lib/oxzion/crm
>sudo ln -s /var/log/oxzion/crm /var/lib/oxzion/crm/logs
>sudo chown www-data:www-data -R /var/lib/oxzion/crm
>sudo mkdir -p /var/www/crm
>sudo ln -s /var/lib/oxzion/crm /var/www/crm/var
>sudo chown www-data:www-data -R /var/www/crm
>sudo mkdir -p /var/log/oxzion/chat
>sudo chown oxzion:oxzion -R /var/log/oxzion/chat
>sudo mkdir -p /var/lib/oxzion/chat
>sudo chown oxzion:oxzion -R /var/lib/oxzion/chat
>sudo mkdir -p /opt/oxzion/mattermost
>sudo ln -s /var/log/oxzion/chat /opt/oxzion/mattermost/logs
>sudo ln -s /var/lib/oxzion/chat /opt/oxzion/mattermost/data
>sudo chown oxzion:oxzion -R /opt/oxzion/mattermost

<h3> ActiveMq setup
>curl "https://archive.apache.org/dist/activemq/5.15.6/apache-activemq-5.15.6-bin.tar.gz" -o apache-activemq-5.15.6-bin.tar.gz

>sudo tar xzf apache-activemq-5.15.6-bin.tar.gz -C  /opt
>sudo ln -s /opt/apache-activemq-5.15.6 /opt/activemq
>sudo useradd -r -M -d /opt/activemq activemq
>sudo chown -R activemq:activemq /opt/apache-activemq-5.15.6
>sudo chown -h activemq:activemq /opt/activemq
copy the activemq.service to /etc/systemd/system/activemq.service
>sudo systemctl daemon-reload

<3> Camel setup
>sudo mkdir /opt/oxzion/camel
>sudo chown oxzion:oxzion -R /opt/oxzion/camel

<h3>Step 2: A one-time database setup is required for the following integrations:

-------------------------

<h4>API</h4>

- Use the dump file **_schema.sql_** and **_data.sql_** in **_api/v1/data/schema_** for database creation.
- A Database Migration should be performed by using the following command &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_[Note: You should be in the oxzion3.0 root folder to perform the command]_
>api/v1/migrations migrate

<h4>OroCRM</h4>

- Create a database  **_oro_crm_** with a user **_crmuser_** and granting all previleges to the user in database.
>sudo mysql -u root -p
mysql>create database oro_crm;
mysql>CREATE USER 'crmuser'@'localhost' IDENTIFIED BY '<password>';
mysql>GRANT ALL PRIVILEGES ON `oro_crm` . * TO 'crmuser'@'localhost' identified by '<password>';

-A Database migration should be perform using the following commannd &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_[Note: You should be in the oxzion3.0 root folder to perform the command]_

Ensure the Installed flag in parameters.yml under env folder is initially set to false before you start your build
>sudo rm -R temp
>mkdir temp
>unzip build.zip -d temp
>cd temp/integrations/crm
>php bin/console oro:install --env=prod --timeout=30000 --application-url="http://localhost:8075/crm/public" --organization-name="Vantage Agora" --user-name="admin" --user-email="admin@example.com" --user-firstname="Admin" --user-lastname="User" --user-password="admin" --language=en --formatting-code=en_US

If it fails for some reason start with an empy database and remove the cache folder
>rm -R var/cache/*

After the migration is completed replace the parameters.yml in the env folder from the /var/www/crm/config folder on the instance after deployment
>sudo cp config/parameters.yml /var/www/crm/config/
>sudo chown www-data:www-data /var/www/crm/config/parameters.yml
>cp config/parameters.yml ~/env/integrations/orocrm/config/

sudo apt-get install -y supervisor
sudo apt-get install redis-server
sudo apt install php7.0-opcache
sudo phpenmod opcache

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
