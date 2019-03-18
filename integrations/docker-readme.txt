To build the docker image 
$ docker build --tag integrations .
To start the docker container
sudo mkdir -p /var/lib/oxzion/rainloop/data
chmod 777 /var/lib/oxzion/rainloop/data
$ docker run -it --network="host" -v ${PWD}:/integrations -v /var/lib/oxzion/rainloop/data:/var/www/public/rainloop/data integrations
To connect into the docker container's shell
$ docker run -it --network="host" -v ${PWD}:/integrations -v /var/lib/oxzion/rainloop/data:/var/www/public/rainloop/data --entrypoint bash integrations 



Note: "use sudo if permission denied"
