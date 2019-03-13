#!/bin/sh

cd rainloop 
npm install 
npm audit fix
gulp rainloop:start

cp build/dist/releases/webmail/1.12.1/src/*.php /var/www/public/rainloop
mkdir /var/www/public/rainloop/rainloop
cp -R build/dist/releases/webmail/1.12.1/src/rainloop /var/www/public/rainloop/
cp build/dist/releases/webmail/1.12.1/src/data/* /var/www/public/rainloop/data

cp -R /integrations/eventcalendar /var/www/public/calendar/    

cd /integrations/orocrm
composer install
cp -R /integrations/orocrm /var/www/public/crm/
service apache2 start
tail -f /dev/null &

    `
