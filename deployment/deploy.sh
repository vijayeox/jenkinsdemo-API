#This script is used to deploy build.zip to respective folders
#!/bin/bash
# exit when any command fails
#set -e
#trap 'echo "\"${BASH_COMMAND}\" command failed with exit code $?."' EXIT
#going back to oxzion3.0 root directory
cd ../
echo ${PWD}
#Defining variables for later use
source /home/ubuntu/env/integrations/orocrm/env.sh
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
        rsync -r --delete api/v1/data/uploads/* /var/www/api/data/uploads/
        rm -R api/v1/data/uploads
        rm -R api/v1/data/cache
        rm -R api/v1/logs
        rsync -rl --delete api/v1/* /var/www/api/
        ln -s /var/lib/oxzion/api/cache /var/www/api/data/cache
        ln -s /var/lib/oxzion/api/uploads /var/www/api/data/uploads
        echo -e "${GREEN}Copying API Complete!\n${RESET}"
        echo -e "${YELLOW}Starting migrations script for API"
        cd /var/www/api
        ./migrations migrate
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
        echo -e "${GREEN}Stopping Camel service"
        systemctl stop camel
        echo -e "${YELLOW}Stopped!"
        #moving to temp directory and copying required
        cd ${TEMP}
        rsync -rl --delete integrations/camel/* /opt/oxzion/camel/
        echo -e "${GREEN}Copying Camel Complete!\n${RESET}"
        echo -e "${YELLOW}Starting Camel service"
        systemctl start camel
        echo -e "${GREEN}Started!"
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
        cd ${TEMP}
        rsync -rl --delete integrations/eventcalendar/* /var/www/eventcalendar/
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
        echo -e "${GREEN}Stopping Mattermost service"
    	systemctl stop mattermost
        echo -e "${YELLOW}Stopped!"
        cd ${TEMP}
        rsync -rl --delete integrations/mattermost/* /opt/oxzion/mattermost/
        echo -e "${GREEN}Copying Mattermost Complete!"
        echo -e "${GREEN}Starting Mattermost service"
        systemctl start mattermost
        echo -e "${YELLOW}Started!"
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
    	cd ${TEMP}
    	rsync -rl --delete integrations/crm/* /var/www/crm/
    	echo -e "${GREEN}Copying OROcrm Complete!"
        echo -e "${YELLOW}Starting migrations script for OROcrm"
        php integrations/orocrm/bin/console oro:install --env=prod --timeout=30000 --application-url="${appurl}" --organization-name="Vantage Agora" --user-name="admin" --user-email="${email}" --user-firstname="Admin" --user-lastname="User" --user-password="admin" --language=en --formatting-code=en_US
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
    	cd ${TEMP}
    	rsync -rl --delete integrations/rainloop/* /var/www/rainloop/
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
   		echo -e "${GREEN}Stopping view service"
        systemctl stop view
        echo -e "${YELLOW}Stopped!"
    	cd ${TEMP}
    	rsync -rl --delete view/vfs/* /opt/oxzion/view/vfs/
        chown oxzion:oxzion -R /opt/oxzion/view/vfs/
        rm -R view/vfs
        rsync -rl --delete view/* /opt/oxzion/view/
        chown oxzion:oxzion -R /opt/oxzion/view
        echo -e "${GREEN}Copying view Complete!${RESET}"
    	echo -e "${GREEN}Starting view service"
        systemctl start view
        echo -e "${YELLOW}Started!"
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
        rsync -rl --delete integrations/workflow/* /opt/oxzion/workflow/
        echo -e "${GREEN}Copying workflow Complete!${RESET}"
        cd /opt/oxzion/workflow
        echo -e "${YELLOW}Building Workflow Docker Image!${RESET}"
        docker build -t workflow .
        echo -e "${GREEN}Built!"
        echo -e "${YELLOW}Starting workflow in docker!${RESET}"
        docker run --network="host" -d --env-file ~/env/integrations/workflow/.env --rm --name wf_1 workflow 
        echo -e "${GREEN}Started Workflow!${RESET}"
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