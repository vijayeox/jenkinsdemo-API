#!/bin/bash

#This script has steps to fix crm cache issue

service supervisor stop
rm -rf /var/www/crm/var/cache/*
chown www-data:www-data var -R && sudo chmod 777 var/ -R
service supervisor restart
