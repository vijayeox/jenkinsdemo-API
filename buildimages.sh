#!/bin/sh
#Script to build all docker images in oxzion-3.0
#Defining variables for later use
HOME=${PWD}
RED="\e[91m"
GREEN="\e[92m"
YELLOW="\e[93m"
MAGENTA="\e[35m"
BLUEBG="\e[44m"
BLINK="\e[5m"
INVERT="\e[7m"
RESET="\e[0m"
echo "${YELLOW}Building Api Docker image..${RESET}"
#building api v1 docker
cd ${HOME}/api/v1
docker-compose build
echo "${GREEN}Building Api Docker image completed!\n${RESET}"
#building view docker
echo "${YELLOW}Building view Docker image..${RESET}"
#building view docker
cd ${HOME}/view/docker
docker build -t view .
echo "${GREEN}Building View Docker image completed!\n${RESET}"
#building integrations docker
echo "${YELLOW}Building Integrations php-apps Docker image..\n${BLUE}Contains OROCRM RAINLOOP AND CALENDAR${RESET}"
cd $HOME/integrations/docker
docker build --tag integrations .
echo "${GREEN}Building Integrations php-apps Docker image completed.${RESET}"
echo "${YELLOW}Building Integrations Camel Docker image..${RESET}"
cd ${HOME}/integrations/camel/docker
docker build . --tag camel
echo "${YELLOW}Building Integrations Camel Docker image completed!\n${RESET}"
echo "${YELLOW}Building Integrations Mattermost Docker image..${RESET}"
cd ${HOME}/integrations/mattermost/docker
docker build --tag mchat .
echo "${YELLOW}Building Integrations Mattermost Docker image completed!\n${RESET}"
echo "${BLINK} All images built successfully${RESET}"
