#!/bin/bash
cd /workspace/app
#check if .gradle is there if not create
if [[ ! -d "./.gradle" ]]; then
    mkdir .gradle
    chmod 777 .gradle 
fi
#create a link from home .gradle folder to our workspace .gradle folder
if [[ -d "/root/.gradle" && ! -L "/root/.gradle" ]]; then
    echo "removing /root/.gradle" 
    rm -Rf /root/.gradle
fi

if [[ ! -L "/root/.gradle" ]]
then
    ln -s /workspace/app/.gradle /root/.gradle 
fi
#if [[ ! -L "~/.gradle" ]]; then
#    ln -s "~/.gradle" "./.gradle"
#fi

./gradlew bootJar 
mkdir -p /workspace/camel
cp ./build/libs/app-0.0.1-SNAPSHOT.jar /workspace/camel/camel.jar
su - activemq /opt/activemq/bin/activemq console &
cd /workspace/camel
java -jar ./camel.jar
