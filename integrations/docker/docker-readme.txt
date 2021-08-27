Do the following in your local machine:

$ sudo mkdir -p /var/lib/oxzion/rainloop/data
$ chmod 777 /var/lib/oxzion/rainloop/data
================================================

To build the docker image 

$ docker build --tag integrations .
================================================
Then go back to the integrations folder

$ cd ../
================================================
To start the docker container

$ docker run -it --network="host" -v ${PWD}:/integrations -v /var/lib/oxzion/rainloop/data:/var/www/public/rainloop/data integrations
======================================================================================================================================
To connect into the docker container's shell

$ docker run -it --network="host" -v ${PWD}:/integrations -v /var/lib/oxzion/rainloop/data:/var/www/public/rainloop/data --entrypoint bash integrations
$ docker run -it --network="host" -v ${PWD}:/integrations -v ${PWD}/rainloop/data:/var/www/public/rainloop/data --entrypoint bash integrations

======================================================================================================================================


======================================
Note: "use sudo if permission denied"

======================================
Instructions for OROCRM
======================================
1. Change your local machince's mysql's bind-address to 0.0.0.0 in mysql.cnf aka mysqld.cnf(newer versions). Path for mysql.cnf/mysqld.cnf for mysql 5.7+ is /etc/mysql/mysql.conf.d/mysqld.cnf
2. Make sure you have empty database named oro_crm with a user with host % and same should be updated in crm_root_directory/config/parameters.yml. An empty database is a mandatory step for first time installation. Clear the database if you are running this for the first time.

======
Debug
======
3. If any error comes at the time of oro:install. Remove orocrm_root_directory/var/cache folder (rm -rf orocrm_root_directory/var/cache) and try running it again.