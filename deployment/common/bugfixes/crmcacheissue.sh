#!/bin/bash

#This script has steps to fix crm cache issue
service apache2 stop
service cron stop
service supervisor stop
rm -rf /var/www/crm/var/cache/*
chown www-data:www-data /var/www/crm/var -R && chmod 777 /var/www/crm/var -R
service apache2 restart
service supervisor restart
service cron restart

