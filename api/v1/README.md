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
$ docker run --network="host" -it -v ${PWD}/../..:/app v1_zf ./migrations migrate
For Windows
$ docker run --network="host" -it -v ${PWD}/../..:/app v1_zf ./migrations migrate

To run tests using the mysql database running on your machine run the following command
```bash
For Linux
$ docker run --network="host" -it --env-file .env -v ${PWD}/../..:/app v1_zf ./phpunit
For Windows
$ docker run --network="host" -it --env-file .env -v ${PWD}/../..:/app v1_zf ./phpunit
```
To run Documentation Generator on your machine run the following command
This will create the Documentation in a new folder "Doc" which will have an index.html file which contains the list of subpages across the features
```bash
For Linux
$ docker run --network="host" -it -v $(pwd)/../..:/app v1_zf phpdoc
For Windows
$ docker run --network="host" -it -v ${PWD}/../..:/app v1_zf phpdoc
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
$ docker run --network="host" -it -v $(pwd)/../..:/app v1_zf bash
For Windows
$ docker run --network="host" -it -v ${PWD}/../..:/app v1_zf bash
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

1) Change bind address in /etc/mysql/my.cnf

Add the following lines:

[mysqld]
bind-address=0.0.0.0

2) Grant priveleges through mysql command line

mysql> GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'password';
mysql> flush privileges;

3) Restart mysql service