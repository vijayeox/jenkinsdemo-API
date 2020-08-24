## Development mode

The application is using [zf-development-mode](https://github.com/zfcampus/zf-development-mode)
by default, and provides three aliases for consuming the script it ships with:

```bash
$ composer development-enable  # enable development mode
$ composer development-disable # disable development mode
$ composer development-status  # whether or not development mode is enabled
```

You may provide development-only modules and bootstrap-level configuration in
`config/development.config.php.dist`, and development-only application
configuration in `config/autoload/development.local.php.dist`. Enabling
development mode will copy these files to versions removing the `.dist` suffix,
while disabling development mode will remove those copies.

Development mode is automatically enabled as part of the skeleton installation process. 
After making changes to one of the above-mentioned `.dist` configuration files you will
either need to disable then enable development mode for the changes to take effect,
or manually make matching updates to the `.dist`-less copies of those files.

## Running Unit Tests

$ ./vendor/bin/phpunit
```

If you need to make local modifications for the PHPUnit test setup, copy
`phpunit.xml.dist` to `phpunit.xml` and edit the new file; the latter has
precedence over the former when running tests, and is ignored by version
control. (If you want to make the modifications permanent, edit the
`phpunit.xml.dist` file.)

## Using docker-compose

This application has a `docker-compose.yml` for use with
[docker-compose](https://docs.docker.com/compose/); it
uses the `Dockerfile` provided as its base. Build and start the image using:

```bash
$ docker-compose up -d --build
```

At this point, you can visit http://localhost:8080 to see the site running.

You can also run composer from the image. The container environment is named
"zf", so you will pass that value to `docker-compose run`:

```bash
$ docker-compose run zf composer install
```
To create tables in oxzionapi database, run migration script
```bash
For Linux
$ docker run --network="host" -it -v ${PWD}/../..:/app v1_zf --env-file .env ./migrations migrate
For Windows
$ docker run --network="host" -it -v ${PWD}/../..:/app v1_zf --env-file .env  ./migrations migrate

To run tests using the mysql database running on your machine run the following command
```bash
For Linux
$ docker run --network="host" -it --env-file .env -v ${PWD}/../..:/app v1_zf --env-file .env ./phpunit
For Windows
$ docker run --network="host" -it --env-file .env -v ${PWD}/../..:/app v1_zf --env-file .env ./phpunit
```
To run Documentation Generator on your machine run the following command
This will create the Documentation in a new folder "Doc" which will have an index.html file which contains the list of subpages across the features
```bash
For Linux
$ docker run --network="host" -it -v $(pwd)/../..:/app --env-file .env v1_zf phpdoc
For Windows
$ docker run --network="host" -it -v ${PWD}/../..:/app --env-file .env v1_zf phpdoc
```
To run php code Fixer use the following command
This will auto indent and pretify php code
```bash
For Linux
$ docker run --network="host" -it -v $(pwd)/../..:/app v1_zf vendor/bin/php-cs-fixer fix ./module
$ docker run --network="host" -it -v $(pwd)/../..:/app v1_zf vendor/bin/php-cs-fixer fix ./lib
For Windows
$ docker run --network="host" -it -v ${PWD}/../..:/app v1_zf vendor/bin/php-cs-fixer fix ./module
$ docker run --network="host" -it -v ${PWD}/../..:/app v1_zf vendor/bin/php-cs-fixer fix ./lib
```


To connect to the container shell you can run the following command
```bash
For Linux
$ docker run --network="host" -it -v $(pwd)/../..:/app --env-file .env v1_zf bash
For Windows
$ docker run --network="host" -it -v ${PWD}/../..:/app --env-file .env v1_zf bash
```

## Web server setup

### Apache setup

To setup apache, setup a virtual host to point to the public/ directory of the
project and you should be ready to go! It should look something like below:

```apache
<VirtualHost *:80>
    ServerName zfapp.localhost
    DocumentRoot /path/to/zfapp/public
    <Directory /path/to/zfapp/public>
        DirectoryIndex index.php
        AllowOverride All
        Order allow,deny
        Allow from all
        <IfModule mod_authz_core.c>
        Require all granted
        </IfModule>
    </Directory>
</VirtualHost>
```

### Nginx setup

To setup nginx, open your `/path/to/nginx/nginx.conf` and add an
[include directive](http://nginx.org/en/docs/ngx_core_module.html#include) below
into `http` block if it does not already exist:

```nginx
http {
    # ...
    include sites-enabled/*.conf;
}
```


Create a virtual host configuration file for your project under `/path/to/nginx/sites-enabled/zfapp.localhost.conf`
it should look something like below:

```nginx
server {
    listen       80;
    server_name  zfapp.localhost;
    root         /path/to/zfapp/public;

    location / {
        index index.php;
        try_files $uri $uri/ @php;
    }

    location @php {
        # Pass the PHP requests to FastCGI server (php-fpm) on 127.0.0.1:9000
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_param  SCRIPT_FILENAME /path/to/zfapp/public/index.php;
        include fastcgi_params;
    }
}
```

Restart the nginx, now you should be ready to go!


### MySQL Setup

1) We run Oxzion API development web server under docker and mysql daemon on the host running the docker within it. By default mysql daemon (mysqld / mysql server) listens only on local port (127.0.0.1 or localhost). Development web server running under docker cannot connect to mysqld on the host. Therefore we should modify mysqld configuration on the host to listen on all interfaces so that development web server running in docker can connect to mysqld on host. This is done by setting mysqld **bind-address** to **0.0.0.0**.

Different Linux distributions put *bind-address* configuration in different files. In some Linux variants it may be found under */etc/mysql/my.cnf*. In some other variants it may be in some other file under */etc/mysql*. For example in Linux Mint 19.1 (Tessa) bind-address is configured in */etc/mysql/mysql.conf.d/mysqld.cnf*. Therefore, do not blindly add *bind-address* configuration to some file like */etc/mysql/my.cnf*. grep for bind-address under */etc/mysql* and add/modify the entry in the file having the entry. If not found, grep for the configuration file containing [mysqld] section and add it under that. If existing *bind-address* entry is pointing to *127.0.0.1* or *localhost*, modify the entry to *0.0.0.0* as shown below.

In file containing mysqld *bind-address* configuration:

[mysqld]
...
...
bind-address=0.0.0.0
...
...

2) Grant priveleges to mysql root user connecting from docker to host through mysql command line

mysql> GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'password';
mysql> flush privileges;

3) Connecting as mysql root user create databases for Oxzion API and running Oxzion API tests in mysql running on the host. Create a database user for Oxzion and grant access.

mysql> CREATE DATABASE oxzion_api;
mysql> CREATE DATABASE oxzion_api_test;
mysql> CREATE USER 'oxzion_user'@'%' IDENTIFIED BY 'oxzion_password';
mysql> GRANT ALL PRIVILEGES ON oxzion_api.* TO 'oxzion_user'@'%';
mysql> GRANT ALL PRIVILEGES ON oxzion_api_test.* TO 'oxzion_user'@'%';

IMPORTANT: For appliction deployment *oxzion_user* should be able to create databases for applications. Therefore *oxzion_user* should be able to create databases. Grant all privileges to *oxzion_user* for that. Well, it is not a great situation to grant all privileges to *oxzion_user*, but there is no better strategy in mysql for now.

mysql> GRANT ALL PRIVILEGES ON *.* TO 'oxzion_user'@'%';
mysql> FLUSH PRIVILEGES;

4) Restart mysql service.

5) Specify the database information in */api/v1/.env*

DB_USERNAME=oxzion_user
DB_PASSWORD=oxzion_password
API_DB=oxzion_api
TEST_API_DB=oxzion_api_test

