#This script is used to deploy build.zip to respective folders
#!/bin/bash
# exit when any command fails
set -e
#trap 'echo "\"${BASH_COMMAND}\" command failed with exit code $?."' EXIT
#going back to oxzion3.0 root directory
cd ../
#Defining variables for later use
home=${PWD}
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
{
    echo -e "${YELLOW}Extracting build zip to './temp' folder...${RESET}"
    mkdir -p temp
    unzip build.zip -d temp
    echo -e "${GREEN}Extracting build zip to 'temp' folder Completed Successfully!\n${RESET}"
    cd temp
    TEMP=${PWD}
}
api()
{   
    cd ${TEMP}
    echo -e "${YELLOW}Copying API...${RESET}"
    if [ ! -d "./integrations/api/v1" ] ;
    then
        echo -e "${RED}API was not was not packaged so skipping it\n${RESET}"
    else    
        #making the directory where api will be copied.
        mkdir -p /var/www/api
        #moving to temp directory and copying required
        cd ${TEMP}
        cp -R integrations/api/v1/* /var/www/api/
        echo -e "${GREEN}Copying API Complete!\n${RESET}"
        echo -e "${YELLOW}Starting migrations script for API"
        api/v1/migrations migrate
        echo -e "${GREEN}Migrations Complete!"
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
        systemctl stop camel
        mkdir -p /opt/oxzion/camel
        #moving to temp directory and copying required
        cd ${TEMP}
        cp -R integrations/camel/* /opt/oxzion/camel/
        echo -e "${GREEN}Copying Camel Complete!\n${RESET}"
        systemctl start camel
    fi    
}

#Function to copy calendar
calendar()
{
    cd ${TEMP}
    echo -e "${YELLOW}Copying EventCalendar.."
    if [ ! -d "./integrations/eventcalendar" ] ;
    then
        echo -e "${RED}CALENDAR was not packaged so skipping it\n${RESET}"
    else
        mkdir -p /var/www/eventcalendar
        cd ${TEMP}
        cp -R integrations/eventcalendar/* /var/www/eventcalendar/
        echo -e "${GREEN}Copying EventCalendar Complete!"
    fi
}
#Function to copy mattermost
mattermost()
{
    cd ${TEMP}
    echo -e "${YELLOW}Copying Mattermost.."
    if [ ! -d "./integrations/mattermost" ] ;
    then
        echo -e "${RED}MATTERMOST was not packaged so skipping it\n${RESET}"
    else
    	systemctl stop mattermost
        mkdir -p /opt/oxzion/mattermost
        cd ${TEMP}
        cp -R integrations/mattermost/* /opt/oxzion/mattermost/
        echo -e "${GREEN}Copying Mattermost Complete!"
        systemctl start mattermost
    fi
}
#Function to copy OROcrm
orocrm()
{
    cd ${TEMP}
    echo -e "${YELLOW}Copying OROcrm.."
    if [ ! -d "./integrations/orocrm" ] ;
    then
        echo -e "${RED}OROCRM was not packaged so skipping it\n${RESET}"
    else    
    	mkdir -p /var/www/orocrm
    	cd ${TEMP}
    	cp -R integrations/orocrm/* /var/www/orocrm/
    	echo -e "${GREEN}Copying OROcrm Complete!"
        echo -e "${YELLOW}Starting migrations script for OROcrm"
        php integrations/orocrm/bin/console oro:install --env=prod --timeout=30000 --application-url="http://localhost:8075/crm/public" --organization-name="Vantage Agora" --user-name="admin" --user-email="admin@example.com" --user-firstname="Admin" --user-lastname="User" --user-password="admin" --language=en --formatting-code=en_US
        echo -e "${GREEN}Migrations Complete!"
    fi
}
#Function to copy rainloop
rainloop()
{
    cd ${TEMP}
    echo -e "${YELLOW}Copying Rainloop.."
    if [ ! -d "./integrations/rainloop" ] ;
    then
        echo -e "${RED}RAINLOOP was not packaged so skipping it\n${RESET}"
    else
    	mkdir -p /var/www/rainloop
    	cd ${TEMP}
    	cp -R integrations/rainloop/* /var/www/rainloop
    	echo -e "${GREEN}Copying Rainloop Complete!"
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
   		systemctl stop view
    	mkdir -p /opt/oxzion/view
    	cd ${TEMP}
    	cp -R view/* /opt/oxzion/view
    	echo -e "${GREEN}Copying view Complete!${RESET}"
    	systemctl start view
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
        mkdir -p /opt/oxzion/workflow
        cd ${TEMP}
        cp -R integrations/workflow* /opt/oxzion/workflow
        echo -e "${GREEN}Copying workflow Complete!${RESET}"
        cd /opt/oxzion/workflow
        docker build -t workflow .
        docker run --network="host" -d --env-file ~/env/workflow/.env --rm --name wf_1 workflow 
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
workflow
echo -e "${GREEN}${BLINK}DEPLOYED SUCCESSFULLY${RESET}"