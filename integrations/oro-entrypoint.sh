#!/bin/sh

#Entrypoint commands for orocrm only
echo "testing.. Inside oro-entrypoint"

chown www-data:www-data -R /var/www/public
ln -s /integrations/orocrm/* /var/www/public/crm
cd /integrations/orocrm/
chmod 777 -R .
composer install
php ./bin/console oro:install --env=prod --timeout=30000 --application-url="http://172.16.1.147:8075/crm/public" --organization-name="Vantage Agora" --user-name="admin" --user-email="admin@example.com" --user-firstname="Admin" --user-lastname="User" --user-password="admin" --language=en --formatting-code=en_US
cp ./orocrm_supervisor.conf /etc/supervisor/conf.d/
# Relative URL Issue
mkdir -p /var/www/public/crm/public/css/themes/oro/bundles/bowerassets/font-awesome
cp -R /var/www/public/crm/public/bundles/bowerassets/font-awesome/* /var/www/public/crm/public/css/themes/oro/bundles/bowerassets/font-awesome
chmod 777 -R /var/www/

service apache2 start
service supervisor restart
tail -f /dev/null &

