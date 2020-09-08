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
sed -i "s/.*Listen 80*/Listen 80/" /etc/apache2/ports.cnf
sed -i "s/.*<VirtualHost *:80>*/<VirtualHost *:8080>/" /etc/apache2/sites-enabled/000-default.conf
sed -i "s/user.*/user = www-data/" /etc/mysql/mysql.conf.d/mysqld.cnf

sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/mysql.conf.d/mysqld.cnf

echo "Setting up MySQL directories"
mkdir -p /var/run/mysqld

chmod 755 /etc/mysql/conf.d/my.cnf
# install db
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
cp -r /configs/env/integrations/workflow/* /app/workflow

echo "=> Setting up API Vendor Files ..."
cd /app/api
composer install
dos2unix *
/usr/bin/mysqld_safe > /dev/null 2>&1 &
./migrations migrate
mysqladmin -u root -proot shutdown
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
if [ -e /app/view/view_built ];then
    echo "Building view"
    cd /app/view
    ./build.sh
    touch /app/view/view_built
else
    echo "Starting view"
fi


#setup final services
cp /view.service /etc/systemd/system/view.service
cp /camunda.service /etc/systemd/system/camunda.service
cp /camel.service /etc/systemd/system/camel.service
cp /activemq.service /etc/systemd/system/activemq.service

service view start
service camel start
service camunda start
service activemq start
# supervisord
echo "========================================================================"
echo "Supervisord launchs: "
exec supervisord -n -c /etc/supervisor/supervisord.conf