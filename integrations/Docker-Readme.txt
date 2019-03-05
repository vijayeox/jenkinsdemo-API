This docker-compose file is for automation of building all the apps for the oxzion3.0 in docker.
===================================================================================================
FIRST INSTALL DOCKER USING:

1) $ sudo apt-get update
2) $ sudo apt-get install docker docker-ce docker-ce-cli docker.io docker-compose docker-runc docker-containerd

+++++++++++++++++++
+ Pre-requesites: +
+++++++++++++++++++

1)Read the Readme file in all the apps.
2)Chat app needs a mysql database connection. Read the readme for chat app in /mattermost for setting up the database before build.


TO BUILD THE DOCKER-COMPOSE FILE:-

"$ docker-compose build" in the same directory of the "YAMLs" file ( use sudo if permission denied) to build.
====================================================================================================

++++++++++
+ EXTRAS +
++++++++++

TO RUN a docker image from a DOCKERFILE

$ docker build [path]

