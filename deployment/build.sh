# script to package oxzion3.0 to production build
#!/bin/sh
# exit when any command fails
set -e
#trap 'echo "\"${BASH_COMMAND}\" command failed with exit code $?."' EXIT
#going back to oxzion3.0 root directory
cd ../
#Defining variables for later use
SERVER=${2}
OXHOME=${PWD}
RED="\e[91m"
GREEN="\e[92m"
BLUE="\e[34m"
YELLOW="\e[93m"
MAGENTA="\e[35m"
BLUEBG="\e[44m"
CYAN="\e[96m"
BLINK="\e[5m"
INVERT="\e[7m"
RESET="\e[0m"
#checking if no arguments passed. Give error and exit.
if [ $# -eq 0 ] ;
then
    echo -e "${RED}ERROR: argument missing.${RESET}"
    echo -e "$0 : needs 2 arguments to start."
    echo -e "For example type \n$ ${GREEN}build.sh calendar${YELLOW}(build option) ${GREEN}abc@xyz.com${YELLOW}(server name) ${RESET}.\nSee build list below."
    echo -e "Type '$0 --help' or '$0 -h' for more information."
    echo -e "${BLUEBG}Argument list:${RESET}"
    echo -e "1. all           -${YELLOW}For packaging complete Oxzion-3.0.${RESET}"
    echo -e "2. api           -${YELLOW}For packaging API.${RESET}"
    echo -e "3. view          -${YELLOW}For packaging UI/View.${RESET}"
    echo -e "4. workflow      -${YELLOW}For packaging workflow.${RESET}"
    echo -e "5. integrations  -${YELLOW}For packaging all Oxzion-3.0 integrations.${RESET}"
    echo -e "6. calendar      -${YELLOW}For packaging Event Calendar.${RESET}"
	echo -e "7. camel         -${YELLOW}For packaging Apache Camel.${RESET}"
    echo -e "8. chat          -${YELLOW}For packaging Mattermost Chat.${RESET}"
    echo -e "9. crm           -${YELLOW}For packaging OroCRM.${RESET}"
	echo -e "10. mail         -${YELLOW}For packaging Rainloop Mail.${RESET}"
	echo -e "11. --help or -h -${YELLOW}For help.${RESET}"
    echo -e "12. list         -${YELLOW}For list of options.${RESET}"
    echo -e "13. deploy       -${YELLOW}For deploying to production${RESET}"
    echo -e "14. clean        -${YELLOW}For cleaning the production server${RESET}"
    echo -e "15. setup        -${YELLOW}For fresh setup of the production server${RESET}"
    exit 0
fi
#writing functions for different tasks
#function checking exiting build dir and deleting it
check_dir()
{
cd ${OXHOME}
if [ -d "./build" ] ;
then
    echo -e "${RED}Directory build exist!${RESET}"
    echo -e "${YELLOW}Deleting existing build folder to avoid conflict...${RESET}"    
    rm -Rf build
fi
}
package()
{
    #going back to /build directory
    cd ${OXHOME}/build
    # zip the contents of the build folder excluding node_modules
    echo -e "${YELLOW}${BLINK}Packaging /build to build.zip${RESET}"
    if [ -e "../build.zip" ] ;
    then
    	echo -e "${RED}'build.zip' exist! Removing it to avoid conflict.${RESET}"
        rm ../build.zip
    fi
    zip -r ../build.zip . -x *node_modules/\*
    echo -e "${GREEN}Packaging Complete :)${RESET}"
    #Doing secure copy to dev3 server
    cd ${OXHOME}
    echo -e "${YELLOW}Now Copying ${RED}build.zip${YELLOW} to $SERVER..${RESET}"
    ssh -i ${HOME}/.ssh/oxzionapi.pem $SERVER ' mkdir -p oxzion3.0/deployment ;'
    scp -i ${HOME}/.ssh/oxzionapi.pem build.zip $SERVER:oxzion3.0
    echo -e "${YELLOW}Copying ${RED}build.zip${YELLOW} to $SERVER completed successfully!${RESET}"        
}
api()
{   
    echo -e "${YELLOW}Creating directory /build/api/v1...${RESET}"
    mkdir -p build/api/v1
    #copy contents of ap1v1 to build
    echo -e "${YELLOW}Copying Api/v1....${RESET}"
    cp -R api/v1 build/api/
    echo -e "${GREEN}Copying Completed!${RESET}"
    #building API
    cd build/api/v1
    echo -e "${YELLOW}Building API....${RESET}"
    docker run -t -v ${PWD}:/var/www v1_zf composer update
    echo -e "${GREEN}Building API Completed!${RESET}"
    cd ${OXHOME}
}
camel()
{   
    echo -e "${YELLOW}Creating directory build/integrations/camel...${RESET}"
    mkdir -p build/integrations/camel
    #building camel
    cd ${OXHOME}/integrations/camel
    echo -e "${YELLOW}Building Camel${RESET}"
    #building camel
    docker run --network="host" -t -v ${PWD}:/workspace/app --entrypoint ./docker-build.sh camel
    echo -e "${GREEN}Building Camel Completed!${RESET}"
    echo -e "${YELLOW}Copying Camel...${RESET}"
    cp ./build/libs/app-0.0.1-SNAPSHOT.jar ../../build/integrations/camel/camel.jar
    echo -e "${GREEN}Copying Camel completed!${RESET}"
    cd ${OXHOME}
}
calendar()
{   
    echo -e "${YELLOW}Creating directory build/integrations/eventcalendar...${RESET}" 
    mkdir -p build/integrations/eventcalendar
    echo -e "${YELLOW}Copying and Building Calendar....${RESET}"
    cp -R ./integrations/eventcalendar ./build/integrations/
    echo -e "${GREEN}Copying and Building Calendar Completed!${RESET}"
    cd ${OXHOME}
}
chat()
{   
    echo -e "${YELLOW}Creating directory build/integrations/mattermost...${RESET}"
    mkdir -p build/integrations/mattermost
    #building mattermost
    cd ${OXHOME}/integrations/mattermost
    echo -e "${YELLOW}Building Integration Mattermost...${RESET}"
    docker run -t --network="host" -v ${PWD}:/mattermost --entrypoint ./docker-build.sh mchat
    echo -e "${GREEN}Building Mattermost Completed!${RESET}"
    # unzip of the tar.gz file to build/integrations/mattermost
    echo -e "${YELLOW}Copying Mattermost${RESET}"
    tar xvzf ./mattermost-server/dist/mattermost-team-linux-amd64.tar.gz -C ../../build/integrations
    echo -e "${GREEN}Copying Mattermost Completed!${RESET}"
    cd ${OXHOME}
}
crm()
{   
    echo -e "${YELLOW}Creating directory build/integrations/orocrm...${RESET}"
    mkdir -p build/integrations/orocrm
    #building orocrm
    cd ${OXHOME}/integrations
    echo -e "${YELLOW}Building orocrm${RESET}"
    docker run -it --network="host" -v ${PWD}:/integrations -v /var/lib/oxzion/rainloop/data:/var/www/public/rainloop/data --entrypoint ./orocrm/docker-build.sh integrations
    echo -e "${GREEN}Building orocrm Completed!${RESET}"
    #copying orocrm to build
    echo -e "${YELLOW}Copying Orocrm....${RESET}"
    cp -R ./integrations/orocrm ./build/integrations/
    echo -e "${GREEN}Copying Completed!${RESET}"
    cd ${OXHOME}
}
mail()
{   
    mkdir -p build/integrations/rainloop
    #building rainloop
    cd ${OXHOME}/integrations/rainloop
    echo -e "${YELLOW}Building Rainloop...${RESET}"
    npm install
    npm audit fix
    npm update
    gulp rainloop:start
    #copying contents of src folder to build/integrations/rainloop
    echo -e "${GREEN}Building Rainloop Completed!${RESET}"
    echo -e "${YELLOW}Copying Rainloop...${RESET}"
    cp -R ./build/dist/releases/webmail/1.12.1/src/* ../../build/integrations/rainloop/
    echo -e "${GREEN}Copying Rainloop Completed!${RESET}"
    cd ${OXHOME}
}
view()
{   
    echo -e "${YELLOW}Creating directory /build/view...${RESET}"
    mkdir -p build/view
    #copy contents of view to build
    echo -e "${YELLOW}Copying View. Please wait this may take sometime....${RESET}"
    cp -R view build/
    echo -e "${GREEN}Copying View Completed!${RESET}"
    #building UI/view folder
    cd build/view
    echo -e "${YELLOW}Build UI/view${RESET}"
    docker run -t -v ${PWD}:/app -p 8081:8081 view ./build.sh
    echo -e "${GREEN}Building UI/view Completed!${RESET}"
    cd ${OXHOME}
}
workflow()
{
    echo -e "${YELLOW}Creating directory build/integrations/workflow...${RESET}"
    mkdir -p build/integrations/workflow/IdentityService/dist
    echo -e "${YELLOW}Copying workflow....${RESET}"
    cp integrations/workflow/bpm-platform.xml integrations/workflow/Dockerfile integrations/workflow/camunda-tomcat.sh ./build/integrations/workflow/ && cp integrations/workflow/IdentityService/dist/identity_plugin.jar ./build/integrations/workflow/IdentityService/dist/
    echo -e "${GREEN}Copying workflow Completed!${RESET}"
    cd ${OXHOME}
}
integrations()
{
    camel
    calendar
    chat
    crm
    mail
    workflow    
}
all()
{   
   integrations
   api
   view 
}
#looping through case from arguments passed
for i in $@
do
    case $i in
        api)
                echo -e "Starting script ${INVERT}$0${RESET}...with ${MAGENTA}$@${RESET} as parameters"                
                check_dir
                api
                package
                break ;;
        view)
                echo -e "Starting script ${INVERT}$0${RESET}...with ${MAGENTA}$@${RESET} as parameters"                
                check_dir
                view
                package
                break ;;
        camel)
                echo -e "Starting script ${INVERT}$0${RESET}...with ${MAGENTA}$@${RESET} as parameters"
                check_dir
                camel
                package
                break ;;
        calendar)
                echo -e "Starting script ${INVERT}$0${RESET}...with ${MAGENTA}$@${RESET} as parameters"
                check_dir
                calendar
                package
                break ;;
        chat)
                echo -e "Starting script ${INVERT}$0${RESET}...with ${MAGENTA}$@${RESET} as parameters"
                check_dir
                chat
                package
                break ;;
        crm)
                echo -e "Starting script ${INVERT}$0${RESET}...with ${MAGENTA}$@${RESET} as parameters"                
                check_dir
                crm
                package
                break ;;
        mail)
                echo -e "Starting script ${INVERT}$0${RESET}...with ${MAGENTA}$@${RESET} as parameters"                
                check_dir
                mail
                package
                break ;;
        workflow)
                echo -e "Starting script ${INVERT}$0${RESET}...with ${MAGENTA}$@${RESET} as parameters"                
                check_dir
                workflow
                package
                break ;;
        integrations)
                echo -e "Starting script ${INVERT}$0${RESET}...with ${MAGENTA}$@${RESET} as parameters"                
                check_dir
                integrations
                package
                break ;;
        all)
                echo -e "Starting script ${INVERT}$0${RESET}...with ${MAGENTA}$@${RESET} as parameters"                
                check_dir                
                all
                package
                break ;;
        --help | -h)
                echo -e "${BLINK}${CYAN}  _____  __ ________ ___  _   _   ____  _   _ ___ _     ____  "
                echo -e " / _ \ \/ /|__  /_ _/ _ \| \ | | | __ )| | | |_ _| |   |  _ \ "
                echo -e "| | | \  /   / / | | | | |  \| | |  _ \| | | || || |   | | | |"
                echo -e "| |_| /  \  / /_ | | |_| | |\  | | |_) | |_| || || |___| |_| |"
                echo -e " \___/_/\_\/____|___\___/|_| \_| |____/ \___/|___|_____|____/ "
                echo -e "                                                              ${RESET}"
                echo -e "This script is made to package oxzion3.0 to production build." 
                echo -e "This script takes 2 arguments to build oxzion-3.0.\nFirst the ${YELLOW}Build Option${RESET} Second the ${YELLOW}Server hostname${RESET}"
                echo -e "For example type \n$ ${GREEN}build.sh calendar$YELLOW(build option) ${GREEN}abc@xyz.com$YELLOW(server name) ${RESET}."
                echo -e "For argument list type ${GREEN}'$0 list'${MAGENTA} as arguments${RESET}."
                break ;;
        list)
                echo -e "1. all           -${YELLOW}For packaging complete Oxzion-3.0.${RESET}"
	            echo -e "2. api           -${YELLOW}For packaging API.${RESET}"
                echo -e "3. view          -${YELLOW}For packaging UI/View.${RESET}"
                echo -e "4. workflow      -${YELLOW}For packaging workflow.${RESET}"
                echo -e "5. integrations  -${YELLOW}For packaging all Oxzion-3.0 integrations.${RESET}"
                echo -e "6. calendar      -${YELLOW}For packaging Event Calendar.${RESET}"
                echo -e "7. camel         -${YELLOW}For packaging Apache Camel.${RESET}"
                echo -e "8. chat          -${YELLOW}For packaging Mattermost Chat.${RESET}"
                echo -e "9. crm           -${YELLOW}For packaging OroCRM.${RESET}"
                echo -e "10. mail         -${YELLOW}For packaging Rainloop Mail.${RESET}"
                echo -e "11. --help or -h -${YELLOW}For help.${RESET}"
                echo -e "12. list         -${YELLOW}For list of options.${RESET}"
                echo -e "13. deploy       -${YELLOW}For deploying to production${RESET}"
                echo -e "14. clean        -${YELLOW}For cleaning the production server${RESET}"
                echo -e "15. setup        -${YELLOW}For fresh setup of the production server${RESET}"
                break ;;
        setup)  
                while true; do
                    echo -e "${RED}Warning! Only use for Fresh Setup, might break the server $SERVER!${RESET}"
                    read yn
                    case $yn in
                        [Yy]* ) scp -i ${HOME}/.ssh/oxzionapi.pem deployment/freshsetup.sh $SERVER:oxzion3.0/deployment
                                ssh -i ${HOME}/.ssh/oxzionapi.pem $SERVER 'sudo bash oxzion3.0/deployment/freshsetup.sh ;'
                                break;;
                        [Nn]* ) echo "Ok bye! ;)"
                                exit;;
                        * ) echo "Please type 'Yes' or 'No'.";;
                    esac
                done
                break ;;
                
        deploy)
                ssh -i ${HOME}/.ssh/oxzionapi.pem $SERVER ' mkdir -p oxzion3.0/deployment ;'
                scp -i ${HOME}/.ssh/oxzionapi.pem deployment/deploy.sh $SERVER:oxzion3.0/deployment
                ssh -i ${HOME}/.ssh/oxzionapi.pem $SERVER 'cd oxzion3.0/deployment ; sudo bash deploy.sh ;'
                break ;;
        clean)
                while true; do
                    echo -e "${RED}Warning! Are you sure you want to clean the production server $SERVER?${RESET}"
                    read yn
                    case $yn in
                        [Yy]* ) echo -e "${YELLOW}Started Cleaning Production server $SERVER${RESET}"
                                ssh -i ${HOME}/.ssh/oxzionapi.pem $SERVER ' rm -Rf oxzion3.0 ;'
                                ssh -i ${HOME}/.ssh/oxzionapi.pem $SERVER ' mkdir -p oxzion3.0/deployment ;'
                                echo -e "${GREEN}Cleaning Production server Completed!${RESET}"
                                break;;
                        [Nn]* ) echo "Ok bye! ;)"
                                exit;;
                        * ) echo "Please type 'Yes' or 'No'.";;
                    esac
                done
                break ;;
                
        *)
                echo -e "${RED}Error : Wrong build option ${YELLOW}'$i'${RESET}"
                echo -e "Type '$0 --help' or '$0 -h' for more information."
                break ;;
    esac
done
