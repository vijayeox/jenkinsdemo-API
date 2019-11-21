#!/bin/bash
#This script is used to deploy build.zip to respective folders
# exit when any command fails
set -e
#trap 'echo "\"${BASH_COMMAND}\" command failed with exit code $?."' EXIT
#going back to oxzion3.0 root directory
cd ../
echo "This is the present working directory ---->${PWD}"
#Defining variables for later use
homedir=${PWD}
RED="\e[91m"
GREEN="\e[92m"
BLUE="\e[34m"
YELLOW="\e[93m"
MAGENTA="\e[35m"
BLUEBG="\e[44m"
CYAN="\e[36m"
BLINK="\e[5m"
INVERT="\e[7m"
RESET="\e[0m"


#Checking if temp folder exist, delete and create new.
if [ -d "./temp" ] ;
then
    echo -e "${RED}Directory temp exist!${RESET}"
    echo -e "${YELLOW}Deleting existing 'temp' folder to avoid conflict...\n${RESET}"    
    rm -Rf temp
fi

#WRITING FUNCTIONS FOR DIFFERENT TASKS#
unpack()
{   echo "this is homedir ---> ${homedir}"
    cd ${homedir}
    echo -e "${YELLOW}Extracting build zip to './temp' folder...${RESET}"
    mkdir -p temp
    unzip build.zip -d temp
    echo -e "${GREEN}Extracting build zip to 'temp' folder Completed Successfully!\n${RESET}"
    cd temp
    TEMP=${PWD}
}
api()
{   
    echo "this is temp dir ---> ${TEMP}"
    cd ${TEMP}
    echo -e "${YELLOW}Copying API...${RESET}"
    if [ ! -d "./api/v1" ] ;
    then
        echo -e "${RED}API was not was not packaged so skipping it\n${RESET}"
    else    
        #making the directory where api will be copied.
        #moving to temp directory and copying required
        cd ${TEMP}
        rsync -rl api/v1/data/uploads/ /var/www/api/data/uploads/
        rm -Rf api/v1/data/uploads
        rm -Rf api/v1/data/cache
        rm -Rf api/v1/logs
        rsync -rl --delete api/v1/ /var/www/api/
        ln -nfs /var/lib/oxzion/api/cache /var/www/api/data/cache
        ln -nfs /var/lib/oxzion/api/uploads /var/www/api/data/uploads
        chown www-data:www-data -R /var/www/api
        echo -e "${GREEN}Copying API Complete!\n${RESET}"
        echo -e "${YELLOW}Starting migrations script for API${RESET}"
        cd /var/www/api
        ./migrations migrate
        sudo ln -nfs /var/log/oxzion/api /var/www/api/logs
        echo -e "${GREEN}Migrations Complete!${RESET}"
    fi    
}
camel()
{   
    cd ${TEMP}
    echo -e "${YELLOW}Copying Camel...${RESET}"
    if [ ! -d "./integrations/camel" ] ;
    then
        echo -e "${RED}CAMEL was not packaged so skipping it\n${RESET}"
    else
        #making the directory where api will be copied.
        echo -e "${GREEN}Stopping Camel service${RESET}"
        systemctl stop camel
        echo -e "${YELLOW}Stopped!${RESET}"
        #moving to temp directory and copying required
        cd ${TEMP}
        rsync -rl --delete integrations/camel/ /opt/oxzion/camel/
        chown -R oxzion:oxzion /opt/oxzion/camel
        echo -e "${GREEN}Copying Camel Complete!\n${RESET}"
        echo -e "${YELLOW}Starting Camel service${RESET}"
        systemctl start camel
        echo -e "${GREEN}Started!${RESET}"
    fi    
}

#Function to copy calendar
calendar()
{
    cd ${TEMP}
    echo -e "${YELLOW}Copying EventCalendar..${RESET}"
    if [ ! -d "./integrations/eventcalendar" ] ;
    then
        echo -e "${RED}CALENDAR was not packaged so skipping it\n${RESET}"
    else
        cd ${TEMP}
        rm -Rf integrations/eventcalendar/uploads
        rsync -rl --delete integrations/eventcalendar/ /var/www/calendar/
        ln -nfs /var/lib/oxzion/calendar /var/www/calendar/uploads
        chown www-data:www-data -R /var/www/calendar
        echo -e "${GREEN}Copying EventCalendar Complete!${RESET}"
    fi
}
#Function to copy mattermost
mattermost()
{
    cd ${TEMP}
    echo -e "${YELLOW}Copying Mattermost..${RESET}"
    if [ ! -d "./integrations/mattermost" ] ;
    then
        echo -e "${RED}MATTERMOST was not packaged so skipping it\n${RESET}"
    else
    	echo -e "${YELLOW}Backing up current configurations${RESET}"
    	cp /opt/oxzion/mattermost/config/config.json ${TEMP}/integrations/mattermost/config/config.json
    	cp /opt/oxzion/mattermost/config/config.json /home/ubuntu/env/integrations/mattermost/mattermost-server/config/config.json
    	echo -e "${GREEN}Backing up current configurations completed${RESET}"
        echo -e "${GREEN}Stopping Mattermost service${RESET}"
        systemctl stop mattermost
        echo -e "${YELLOW}Stopped!${RESET}"
        cd ${TEMP}
        rm -Rf integrations/mattermost/logs
        rm -Rf integrations/mattermost/data
        rsync -rl integrations/mattermost/ /opt/oxzion/mattermost/
        ln -nfs /var/lib/oxzion/chat /opt/oxzion/mattermost/data
        ln -nfs /var/log/oxzion/chat /opt/oxzion/mattermost/logs
        chown oxzion:oxzion -R /opt/oxzion/mattermost
        echo -e "${GREEN}Copying Mattermost Complete!${RESET}"
        echo -e "${GREEN}Starting Mattermost service${RESET}"
        systemctl start mattermost
        echo -e "${YELLOW}Started!${RESET}"
    fi
}
#Function to copy OROcrm
orocrm()
{
    cd ${TEMP}
    echo -e "${YELLOW}Copying CRM..${RESET}"
    if [ ! -d "./integrations/crm" ] ;
    then
        echo -e "${RED}CRM was not packaged so skipping it\n${RESET}"
    else    
        cd ${TEMP}
        systemctl stop supervisor
        mkdir -p /var/www/crm
        chown ubuntu:ubuntu -R integrations/crm
        echo -e "${YELLOW}Installing composer libraries in ${CYAN}crm/vendor${RESET}"
        runuser -l ubuntu -c "composer install -d ${TEMP}/integrations/crm"
        echo -e "${YELLOW}Installing node_modules in ${CYAN}crm/build${RESET}"
        runuser -l ubuntu -c "npm install --prefix ${TEMP}/integrations/crm/build --verbose"
        echo -e "${YELLOW}Installing Assets for CRM now${RESET}"
        runuser -l ubuntu -c "php ${TEMP}/integrations/crm/bin/console oro:assets:install"
        echo -e "${YELLOW}Loading migrations now${RESET}"
        runuser -l ubuntu -c "php ${TEMP}/integrations/crm/bin/console oro:migration:load --force"
        mkdir -p integrations/crm/public/css/themes/oro/bundles/bowerassets/font-awesome
        rsync -rl --delete integrations/crm/public/bundles/bowerassets/font-awesome/ integrations/crm/public/css/themes/oro/bundles/bowerassets/font-awesome/
        if [ ! -L "/var/www/crm/var" ] ;
        then
            ln -nfs /var/lib/oxzion/crm /var/www/crm/var
            ln -nfs /var/log/oxzion/crm /var/lib/oxzion/crm/logs    
        fi
        rm -Rf integrations/crm/var
        echo -e "${YELLOW}Copying existing link"
        cp -P /var/www/crm/var integrations/crm/var 
        rsync -rl --delete integrations/crm/ /var/www/crm/
        chown www-data:www-data -R /var/lib/oxzion/crm
        rsync -rl --delete /var/www/crm/orocrm_supervisor.conf /etc/supervisor/conf.d/
        echo -e "${GREEN}Copying CRM Complete!${RESET}"
        chown www-data:www-data -R /var/www/crm
        rm -Rf /var/www/crm/var/cache/*
        systemctl start supervisor
    fi
}
#Function to copy rainloop
rainloop()
{
    cd ${TEMP}
    echo -e "${YELLOW}Copying Rainloop..${RESET}"
    if [ ! -d "./integrations/rainloop" ] ;
    then
        echo -e "${RED}RAINLOOP was not packaged so skipping it\n${RESET}"
    else
        cd ${TEMP}
        service apache2 stop
        rsync -rl integrations/rainloop/data/ /var/www/rainloop/data/
        rm -Rf integrations/rainloop/data
        rsync -rl --delete integrations/rainloop/ /var/www/rainloop/
        ln -nfs /var/lib/oxzion/rainloop /var/www/rainloop/data
        chown www-data:www-data -R /var/www/rainloop
        chown www-data:www-data -R /var/lib/oxzion/rainloop
        echo -e "${GREEN}Copying Rainloop Complete!${RESET}"
        service apache2 start
    fi
}
view()
{
    cd ${TEMP}
    echo -e "${YELLOW}Copying view...${RESET}"
    if [ ! -d "./view" ] ;
    then
        echo -e "${RED}VIEW was not packaged so skipping it\n${RESET}"
    else
        echo -e "${GREEN}Stopping view service${RESET}"
        systemctl stop view
        echo -e "${YELLOW}Stopped!${RESET}"
        cd ${TEMP}
        rsync -rl view/vfs/ /opt/oxzion/view/vfs/
        chown oxzion:oxzion -R /opt/oxzion/view/vfs/
        rm -Rf view/vfs
        rsync -rl --delete view/ /opt/oxzion/view/
        chown oxzion:oxzion -R /opt/oxzion/view
        echo -e "${GREEN}Copying view Complete!${RESET}"
        echo -e "${GREEN}Starting view service${RESET}"
        systemctl start view
        echo -e "${YELLOW}Started!${RESET}"
    fi
}
workflow()
{
    cd ${TEMP}
    echo -e "${YELLOW}Copying workflow...${RESET}"
    if [ ! -d "./integrations/workflow" ] ;
    then
        echo -e "${RED}Workflow was not packaged so skipping it\n${RESET}"
    else
        docker stop wf_1
        cd ${TEMP}
        rsync -rl --delete integrations/workflow/ /opt/oxzion/workflow/
        echo -e "${GREEN}Copying workflow Complete!${RESET}"
        cd /opt/oxzion/workflow
        echo -e "${YELLOW}Building Workflow Docker Image!${RESET}"
        docker build -t workflow .
        echo -e "${GREEN}Built!${RESET}"
        echo -e "${YELLOW}Starting workflow in docker!${RESET}"
        docker run --network="host" -d --env-file ~/env/integrations/workflow/.env --rm --name wf_1 workflow 
        echo -e "${GREEN}Started Workflow!${RESET}"
    fi
}
openproject()
{
    OLDPATH=$PATH
    export PATH="/home/ubuntu/.nodenv/shims:/home/ubuntu/.rbenv/shims:$PATH"
    cd ${TEMP}
    echo -e "${YELLOW}Copying openproject...${RESET}"
    if [ ! -d "./integrations/openproject" ] ;
    then
        echo -e "${RED}Openproject was not packaged so skipping it\n${RESET}"
    else
        cd ${TEMP}/integrations/openproject
        ln -nfs /var/log/oxzion/task ./log
        ln -nfs /var/lib/oxzion/task ./files
        echo -e "${YELLOW}Running db migrate now...${RESET}"
        bundle exec rake db:migrate RAILS_ENV=production
        echo -e "${YELLOW}Copying codebase now...${RESET}"
        rsync -rl --delete ${TEMP}/integrations/openproject/ /var/www/task/
        echo -e "${YELLOW}Copying openproject Completed...${RESET}"
        chown www-data:www-data -R /var/www/task
    fi
    export PATH=$OLDPATH
        


}
helpapp()
{
    cd ${TEMP}
    echo -e "${YELLOW}Copying HelpApp...${RESET}"
    if [ ! -d "./integrations/help" ] ;
    then
        echo -e "${RED}HelpApp was not packaged so skipping it\n${RESET}"
    else
        echo -e "${YELLOW}Stopping Apache...${RESET}"
        sudo service apache2 stop
        echo -e "${YELLOW}Copying HelpApp...${RESET}"
        rsync -rl --delete ${TEMP}/integrations/help/ /var/www/help/
        echo -e "${YELLOW}Copying HelpApp Completed...${RESET}"
        sudo service apache2 start
        echo -e "${YELLOW}Starting Apache...${RESET}"
    fi
}
#on-hold
edms()
{
    cd ${TEMP}
    echo -e "${YELLOW}Copying EDMS...${RESET}"
    if [ ! -d "./integrations/edms" ] ;
    then
        echo -e "${RED}EDMS was not packaged so skipping it\n${RESET}"
    else
        cd ${TEMP}/integrations/edms/mayan
        echo -e "${YELLOW}Creating edms data folder...${RESET}"
        ln -nfs /var/lib/oxzion/edms ./media
        rsync -rl --delete ${TEMP}/integrations/edms/ /var/www/edms/
        echo -e "${YELLOW}Taking ownership as www-data...${RESET}"
        chown www-data:www-data -R /var/www/edms
        chown www-data:www-data -R /var/lib/oxzion/edms
        echo -e "${GREEN}Copying edms Complete!${RESET}"
    fi
}
#calling functions accordingly
unpack
echo -e "${YELLOW}Now copying files to respective locations..${RESET}"
api
view
echo -e "${CYAN}Copying Integrations Now...\n${RESET}"
camel
calendar
mattermost
orocrm
rainloop
openproject
workflow
helpapp
#edms
echo -e "${GREEN}${BLINK}DEPLOYED SUCCESSFULLY${RESET}"
