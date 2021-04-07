#!/bin/bash
chmod 755 /etc/mysql/conf.d/my.cnf
/usr/bin/mysqld_safe > /dev/null 2>&1 &

RET=1
while [[ RET -ne 0 ]]; do
    echo "=> Waiting for confirmation of MySQL service startup"
    sleep 5
    mysql -uroot -e "status" -h "localhost" > /dev/null 2>&1
    RET=$?
done

if [ -z "${MYSQL_PASS}" ] 
then
  PASS="admin"
else 
  PASS="password"
fi

_word=$( [ ${MYSQL_PASS} ] && echo "preset" || echo "default" )
echo "=> Creating MySQL admin user with password"
# mysql -uroot -e "CREATE USER 'admin'@'localhost' IDENTIFIED BY '$PASS'"
# mysql -uroot -e "GRANT ALL PRIVILEGES ON *.* TO 'admin'@'localhost' WITH GRANT OPTION"
mysql -uroot -e "CREATE USER 'admin'@'%' IDENTIFIED BY 'password';" -h "localhost"
mysql -uroot -h "localhost" -e "GRANT ALL PRIVILEGES ON *.* TO 'admin'@'%' REQUIRE NONE WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;"
echo "=> Done!"

echo "========================================================================"
echo "You can now connect to this MySQL Server using:"
echo ""
echo "    mysql -uadmin -ppassword -h<host> -P<port>"
echo ""
#echo "Please remember to change the above password as soon as possible!"
echo "MySQL user 'admin' has password 'password'"
echo "MySQL user 'root' has password 'root' but only allows local connections"
echo "========================================================================"

# phpmyadmin configuration
# Change the MySQL root password
mysqladmin -h "localhost" -u root password root

# Create the phpmyadmin storage configuration database.
mysql -h "localhost" -uroot -proot -e "CREATE DATABASE phpmyadmin; GRANT ALL PRIVILEGES ON phpmyadmin.* TO 'root'@'localhost' IDENTIFIED BY 'root'; FLUSH PRIVILEGES;"

mysql -h "localhost" -uroot -proot < "/configs/database_setup.sql"

# Shutdown the server.
mysqladmin -u root -proot shutdown