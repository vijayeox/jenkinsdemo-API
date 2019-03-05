#!/bin/bash
su -u activemq /bin/activemq console &
java -jar ./lib/camel.jar

