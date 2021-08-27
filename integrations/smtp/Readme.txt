Run the following commands to compile and generate a assembled component
mvn clean install
mvn assembly:single

You will find smtp-server-bin.zip in the target folder.
Unzip it in any location of your preference - the smtp server deployment location

Run the following command from the smtp server deployment location
sudo ./startup.sh

Emails received will be written in the outbox folder
outbox/<from>/<date sent>/text-<timestamp>.txt


 
