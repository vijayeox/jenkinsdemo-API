#!/bin/sh
#Script to build all docker images in oxzion-3.0
# exit when any command fails
#set -e
#trap 'echo -e "\"${BASH_COMMAND}\" command failed with exit code $?."' EXIT
#going back to oxzion3.0 root directory
cd ../
#Defining variables for later use
HOME=${PWD}
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

#building api v1 docker
echo -e "${YELLOW}Building Api Docker image..${RESET}"
cd ${HOME}/api/v1
docker-compose build
echo -e "${GREEN}Building Api Docker image completed!\n${RESET}"

#building view docker
echo -e "${YELLOW}Building view Docker image..${RESET}"
cd ${HOME}/view/docker
docker build -t view .
echo -e "${GREEN}Building View Docker image completed!\n${RESET}"

#building integrations(php-apps) docker
echo -e "${YELLOW}Building Integrations php-apps Docker image..\n${BLUE}Contains OROCRM RAINLOOP AND CALENDAR${RESET}"
cd $HOME/integrations/docker
docker build --tag integrations .
echo -e "${GREEN}Building Integrations php-apps Docker image completed.${RESET}"

#building Camel docker
echo -e "${YELLOW}Building Integrations Camel Docker image..${RESET}"
cd ${HOME}/integrations/camel/docker
docker build . --tag camel
echo -e "${GREEN}Building Integrations Camel Docker image completed!\n${RESET}"

#building mattermost docker
echo -e "${YELLOW}Building Integrations Mattermost Docker image..${RESET}"
cd ${HOME}/integrations/mattermost/docker
docker build --tag mchat .
echo -e "${GREEN}Building Integrations Mattermost Docker image completed!\n${RESET}"

#building workflow docker
echo -e "${YELLOW}Building Integrations Workflow Docker image..${RESET}"
cd ${HOME}/integrations/workflow
docker build -t workflow .
echo -e "${GREEN}Building Integrations Workflow Docker image completed!\n${RESET}"

#building openproject docker
echo -e "${YELLOW}Building Integrations Openproject Docker images..${RESET}"
echo -e "${YELLOW}Building development docker now..${RESET}"
cd ${HOME}/integrations/openproject
docker build -t openproject .
echo -e "${YELLOW}Building production docker now..${RESET}"
cd ${HOME}/integrations/openproject/docker_prod
docker build -t openproject_prod .
echo -e "${YELLOW}Building docker for build and deployment now..${RESET}"
cd ${HOME}/integrations/openproject/docker_build
docker build -t openproject_build .
echo -e "${GREEN}Building Integrations Openproject Docker images completed!\n${RESET}"
echo -e "${BLINK} All images built successfully${RESET}"
