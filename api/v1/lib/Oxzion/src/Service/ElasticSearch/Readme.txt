<!--
/* Welcome to OX Zion
 * oxzion.com 
 * Operational Excellence: Running Company as a game. https://www.youtube.com/watch?v=fK8JhVbhxS4 
 * http://www.oxzion.com/public
 * 
 * Copyright (c) 2011 - 2015 Vantage Agora Pvt.Ltd
 *
 * Author: Vantage Agora R&D,Goki :P
 * Version: 2.6.2
 * Date: July 2018
 */

 //Steps to Install Elastic Search

 1) Install Java Libraries
	a)sudo yum install java-1.8.0-openjdk.x86_64
	b)java -version //to Check version
 2) Install Elastic Search
 	a) wget https://download.elastic.co/elasticsearch/elasticsearch/elasticsearch-1.7.3.noarch.rpm
 	b) sudo rpm -ivh elasticsearch-1.7.3.noarch.rpm
 	c) sudo systemctl enable elasticsearch.service
 3) Start Elastic Search
 sudo service elasticsearch start
 4) Test Instance
 curl -X GET 'http://localhost:9200'