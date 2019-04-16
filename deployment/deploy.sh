#This script is used to deploy build.zip to respective folders
#!/bin/sh
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
    echo -e "${YELLOW}Copying API...${RESET}"
    if [ ! -d "./api/v1" ] ;
    then
        echo -e "${RED}API was not was not packaged so skipping it\n${RESET}"
    else    
        #making the directory where api will be copied.
        mkdir -p /var/www/api
        #moving to temp directory and copying required
        cd ${TEMP}
        cp -R api/v1/* /var/www/api/
        echo -e "${GREEN}Copying API Complete!\n${RESET}"
    fi    
}
camel()
{
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
    echo -e "${MAGENTA}Copying EventCalendar.."
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
    echo -e "${MAGENTA}Copying Mattermost.."
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
    echo -e "${MAGENTA}Copying OROcrm.."
    if [ ! -d "./integrations/orocrm" ] ;
    then
        echo -e "${RED}OROCRM was not packaged so skipping it\n${RESET}"
    else    
    	mkdir -p /var/www/orocrm
    	cd ${TEMP}
    	cp -R integrations/orocrm/* /var/www/orocrm/
    	echo -e "${GREEN}Copying OROcrm Complete!"
    fi
}
#Function to copy rainloop
rainloop()
{
    echo -e "${MAGENTA}Copying Rainloop.."
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
    echo -e "${Green}Copying view...${RESET}"
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

#calling functions accordingly
unpack
echo -e "${MAGENTA}Now copying files to respective locations..${RESET}"
api
view
echo -e "${YELLOW}Copying Integrations Now...\n${RESET}"
camel
calendar
mattermost
orocrm
rainloop
echo -e "${GREEN}Copying Integraions Completed Successfully!\n${RESET}"
echo -e "${GREEN}${BLINK}DEPLOYED SUCCESSFULLY${RESET}"