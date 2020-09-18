#!/bin/bash

# Mysql
VOLUME_HOME="/var/lib/mysql" 

echo "Editing APACHE_RUN_GROUP environment variable"
sed -i "s/export APACHE_RUN_GROUP=www-data/export APACHE_RUN_GROUP=staff/" /etc/apache2/envvars

echo "Editing phpmyadmin config"
sed -ri -e "s/^upload_max_filesize.*/upload_max_filesize = ${PHP_UPLOAD_MAX_FILESIZE}/" \
    -e "s/^post_max_size.*/post_max_size = ${PHP_POST_MAX_SIZE}/" \
    -e "s/^memory_limit.*/memory_limit = ${PHP_MEMORY_LIMIT}/" /etc/php/7.2/apache2/php.ini
echo "Editing MySQL config"
sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/my.cnf
sed -i "s/.*Listen 80*/Listen 8080/" /etc/apache2/ports.conf
sed -i "s/.*Listen 808080*/Listen 8080/" /etc/apache2/ports.conf
sed -i "s/user.*/user = www-data/" /etc/mysql/mysql.conf.d/mysqld.cnf

sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/mysql.conf.d/mysqld.cnf

echo "Setting up MySQL directories"
mkdir -p /var/run/mysqld

chmod 755 /etc/mysql/conf.d/my.cnf
# install db
if [ -n "$VAGRANT_OSX_MODE" ];then
    echo "Setting up users and groups"
    usermod -u $DOCKER_USER_ID www-data
    groupmod -g $(($DOCKER_USER_GID + 10000)) $(getent group $DOCKER_USER_GID | cut -d: -f1)
    groupmod -g ${DOCKER_USER_GID} staff
else
    echo "Allowing Apache/PHP to write to the app"
    # Tweaks to give Apache/PHP write permissions to the app
    chown -R www-data:staff /var/www
fi
echo "Allowing Apache/PHP to write to MySQL"
# Setup user and permissions for MySQL and Apache
chmod -R 770 /var/lib/mysql
chmod -R 770 /var/run/mysqld
chown -R www-data:staff /var/lib/mysql
chown -R www-data:staff /var/run/mysqld
chown -R www-data:staff /var/log/mysql

if [ -e /var/run/mysqld/mysqld.sock ];then
    echo "Removing MySQL socket"
    rm /var/run/mysqld/mysqld.sock
fi

if [[ ! -d $VOLUME_HOME/mysql ]]; then
    echo "=> An empty or uninitialized MySQL volume is detected in $VOLUME_HOME"
    echo "=> Installing MySQL ..."
    mysqld --initialize-insecure > /dev/null 2>&1
    echo "=> Done!"  
    /create_mysql_admin_user.sh
else
    echo "=> Using an existing volume of MySQL"
fi
echo "=> Setting up Environment Files ..."
cd /app
cp /configs/env/api/v1/config/autoload/local.php /app/api/config/autoload/
cp /configs/env/integrations/camel/src/main/resources/* /app/camel/src/main/resources/
cp -r /configs/env/view/* /app/view
cp /configs/env/view/bos/src/osjs-server/.env.example /app/view/bos/src/osjs-server/.env
cp -r /configs/env/integrations/workflow/* /app/workflow

# SETUP App view Configs
cp /configs/env/view/apps/Analytics/.env.example /app/view/apps/Analytics/.env
cp /configs/env/view/apps/Calendar/.env.example /app/view/apps/Calendar/.env
cp /configs/env/view/apps/Chat/.env.example /app/view/apps/Chat/.env
cp /configs/env/view/apps/CRM/.env.example /app/view/apps/CRM/.env
cp /configs/env/view/apps/CRMAdmin/.env.example /app/view/apps/CRMAdmin/.env
cp /configs/env/view/apps/HelpApp/.env.example /app/view/apps/HelpApp/.env
cp /configs/env/view/apps/Mail/.env.example /app/view/apps/Mail/.env
cp /configs/env/view/apps/MailAdmin/.env.example /app/view/apps/MailAdmin/.env
cp /configs/env/view/apps/Task/.env.example /app/view/apps/Task/.env
cp /configs/env/view/apps/TaskAdmin/.env.example /app/view/apps/TaskAdmin/.env

echo "=> Setting up API Vendor Files ..."
cd /app/api
composer install
dos2unix *
/usr/bin/mysqld_safe > /dev/null 2>&1 &
./migrations migrate

mysqladmin -u root -proot shutdown
ln -s /app/api/* /var/www
#Workflow setup
cd /app/workflow
cd /app/workflow/IdentityService/

if [ -f "dist/identity_plugin.jar" ]; then
  echo "=> IdentityService Plugin exists ..."
else 
  echo "=> Fix for windows Environment ..."
  dos2unix ./gradlew
  echo "=> Building IdentityService Plugin ..."
  ./gradlew build
  mkdir -p dist
  cp build/libs/identity_plugin-1.0.jar dist/identity_plugin.jar
fi

cd /app/workflow/ProcessEngine/
if [ -f "dist/processengine_plugin.jar" ]; then
  echo "=> ProcessEngine Plugin exists ..."
else 
  echo "=> Fix for windows Environment ..."
  dos2unix ./gradlew
  echo "=> Building ProcessEngine Plugin ..."
  ./gradlew build
  mkdir -p dist
  cp build/libs/processengine_plugin-1.0.jar dist/processengine_plugin.jar 
fi
# Workflow Setup 
cp /app/workflow/IdentityService/dist/identity_plugin.jar /camunda/lib/identity_plugin.jar
cp /app/workflow/ProcessEngine/dist/processengine_plugin.jar /camunda/lib/processengine_plugin.jar
cp /app/workflow/bpm-platform.xml /camunda/conf/
#Camel Setup
cd /app/camel
if [ -f "./build/libs/camel-0.0.1-SNAPSHOT.jar" ]; then
  echo "=> Camel Jar file exists ..."
else 
  echo "=> Fix for windows Environment ..."
  dos2unix ./gradlew
  echo "=> Building Camel Jar File ..."
  ./gradlew bootJar
fi
cp ./build/libs/camel-0.0.1-SNAPSHOT.jar ./camel.jar

#view setup
ls /app/view/view_built >> /dev/null 2>&1 && echo "Starting view" || (echo "Building view" && cd /app/view && dos2unix * && ./build.sh && touch /app/view/view_built)

su - root /app/activemq/bin/activemq console &
su - root /camunda/bin/catalina.sh start &

# supervisord
dos2unix /start-*
echo "========================================================================"
echo "Supervisord launchs: "
exec supervisord -n -c /etc/supervisor/supervisord.conf