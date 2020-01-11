<div align="center">
  <h1>EOS-3.0 DEV ENVIRONMENT SETUP</h1>
  <p>
    Development Environment setup for application development at EOX Vantage.
  </p>
</div>
----

<div align="center">
<h3><u>Installations</u>:</h3>
</div>
-------------------------
- Please install the recommened markdown reader by the command mentioned below to read this readme properly.

$ sudo apt install remarkable

<h4> 1. <u>Install Smartgit</u>: </h4>

<h4>Install git command line</h4>

- $ sudo apt install git

<h4>Download Smartgit from the Official Website</h4>

- Download the debian bundle of smartgit for easy installation

![smartgit-installation](deployment/static/gif/installsmartgit.gif)
<h5 align="center">GIF: HOW TO INSTALL SMARTGIT</center></h5>
    
- Once Installed you can generate ssh keys and update them in your gitlab account for you to connect the git repository and enable you to do version control without password or you can enter your git account credential to pull code.

![key generation](deployment/static/gif/ssh-keygen.gif)
<h5 align="center">GIF: HOW TO GENERATE KEYS</h5>
- After you have generated the keys update the public part of key to gitlab account 

![](deployment/static/gif/addingpubkeygitlab.gif)
<h5 align="center">GIF: HOW TO ADD KEYS TO GITLAB</center></h5>

- Open Smartgit and goto repository tab and select clone to start cloning the codebase to your local machine.
- You need to know the gitlab repository address to clone it which you can find in the gitlab server i.e `code.oxzion.com`.
- We have different branches for different projects going on. The QA branch is for Development Team. Please clone or checkout if already cloned to the required branch to work on.

![clone](deployment/static/gif/smartgitclone.gif)
<h5 align="center">GIF: HOW TO CLONE A REPOSITORY IN SMARTGIT</center></h5>

<h4> 2. <u>Docker</u>: </h4>

To learn how to install Docker [click here.](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-on-ubuntu-18-04)

<h5><center> OR RUN THE FOLLOWING COMMANDS in terminal</center></h5>

- $ sudo apt update
- $ sudo apt install apt-transport-https ca-certificates curl software-properties-common

- $ curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -

- $ sudo nano /etc/apt/sources.list.d/additional-repositories.list

add the following line in the file

           Note:  deb [arch=amd64] https://download.docker.com/linux/ubuntu bionic stable

Then run the following commands

- $ sudo apt update
- $ sudo apt install docker-ce docker-compose -y

<h5>Docker should now be installed, the daemon started, and the process enabled to start on boot. Check that it’s running:</h5>

- $ sudo systemctl status docker

			Note: Use `sudo` to run docker command
		
-----------

<h4>3. <u>MySql 5.7</u>: </h4>

To learn how to install MySql [click here.](https://linuxize.com/post/how-to-install-mysql-on-ubuntu-18-04/)

<h5><center> OR RUN THE FOLLOWING COMMANDS in terminal</center></h5>

- $ sudo apt update
- $ sudo apt install mysql-server

<h5>Once the installation is completed, the MySQL service will start automatically. To check whether the MySQL server is running, type:</h5>
- $ sudo systemctl status mysql

#####Login to mysql client with superuser privilege for the first time

- $ sudo mysql

<h5>After installing mysql update your root password </h5><h5> 
If you want to login to your MySQL server as root from an external program such as phpMyAdmin.

Change the authentication method from auth_socket to mysql_native_password. You can do that by running the following command:</h5>
- ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'very_strong_ password';
- FLUSH PRIVILEGES;

####To exit the client

- \q or exit

After updating the root password you can login with the new password that you have set by the following

$ mysql -u 'user_name' -p 'password'

-----------



<h4> 4. <u>Database Creation</u>: </h4>

- Update bind-address in mysql configuration to allow external connections using ipv4 address

	- $ sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
	
	`look for "bind-address            = 127.0.0.1"`
        `update it to 0.0.0.0`

- After updating restart mysql
	
	- $ sudo service mysql restart
        

<h5>login to your mysql client</h5>

- Database for API

	- create database oxzionapi;
	- grant all privileges on `oxzionapi`.* to 'root'@'%' identified by 'password';
	-flush privileges;

- Database for workflow integration

	- create database `process-engine`;

- Database for camel integration

	- create database quartz_db;

<h4>Above are the database required for basic app development in App_builder branch and if you want to work for each integrations follow the readme inside each integrations to setup dev environment accordingly</h4>

<h4> 5. <u>Build Docker Images for Development Environement</u>: </h4>

<h5> The development environment uses docker containers for hassle free setup.</h5>

- Follow the readme in each integrations to build the docker images and run the containers

<h4> 6. <u>Updating Configurations</u>: </h4>

		Note : Using relative paths for operations on files below i.e relative to codebase repository root path
		
		Note : Use IPv4 address of your machine for updating host configurations, to check IPv4 address use `ifconfig` on terminal.
		
- For API
	- $ cp api/v1/config/autoload/local.php.dist api/v1/config/autoload/local.php

	`Then in the file api/v1/config/autoload/local.php update the database settings as per the api database you created and the user and the password in your local machine`

- Follow the API Readme and do the following part mentioned in the readme for API.

1. composer install
2. ./migrations migrate

- Then finally run the api docker from api/v1 directory using the following command 

- $ docker-compose up -d --build

- Give the following permission on the api/v1/data folder to make sure api like login works in frontend.

- $ sudo chmod 777 api/v1/data -R
- $ mkdir api/v1/logs
- $ sudo chmod 777 api/v1/logs -R

- For Workflow
	- $ cp integrations/workflow/.env.sample integrations/workflow/.env

	`update the database connection in the .env file you just copied`

	`Follow the readme to build the docker image and run the container`
	
- For Camel	
	
- Update the following files according to your host ip and database. 
	
	1. integrations/camel/src/main/resources/application.yml
	2. integrations/camel/src/main/resources/oxzion.properties
	3. integrations/camel/src/main/resources/Routes.groovy 

- For View

	`cp .env.example to .env in all the iframe apps under `view/apps` to your host.`

	- $ cp view/bos/src/server/local.js.example view/bos/src/server/local.js
	
	- update view/bos/src/server/local.js to your host environment
	
	- $ cp view/bos/src/osjs-server/.env.example view/bos/src/osjs-server/.env
	
	- update the .env file with SERVER parameter to your host ip. 
	
	- $ cp view/bos/src/client/local.js.example view/bos/src/client/local.js
	
	- update the local.js wrapper url parameter to host ip in view/bos/src/client/local.js
