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

## Using Vagrant

This application includes a `Vagrantfile` based on ubuntu 16.04 (bento box)
with configured Apache2 and PHP 7.0. Start it up using:

$ vagrant up

Once built, you can also run composer within the box. For example, the following
will install dependencies:

```bash
$ vagrant ssh -c 'composer install'
```

While this will update them:

```bash
$ vagrant ssh -c 'composer update'
```

While running, Vagrant maps your host port 8080 to port 80 on the virtual
machine; you can visit the site at http://localhost:8080/

> ### Vagrant and VirtualBox
>
> The vagrant image is based on ubuntu/xenial64. If you are using VirtualBox as
> a provider, you will need:
>
> - Vagrant 1.8.5 or later
> - VirtualBox 5.0.26 or later

For vagrant documentation, please refer to [vagrantup.com](https://www.vagrantup.com/)

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

To run tests using the mysql database running on your machine run the following command
```bash
For Linux
$ docker run --network="host" -it -v ${PWD}:/var/www v1_zf ./phpunit
For Windows
$ docker run --network="host" -it -v ${PWD}:/var/www v1_zf ./phpunit
```
To run Documentation Generator on your machine run the following command
This will create the Documentation in a new folder "Doc" which will have an index.html file which contains the list of subpages across the features
```bash
For Linux
$ docker run --network="host" -it -v $(pwd):/var/www v1_zf phpdoc
For Windows
$ docker run --network="host" -it -v ${PWD}:/var/www v1_zf phpdoc
```


To connect to the container shell you can run the following command
```bash
For Linux
$ docker run --network="host" -it -v $(pwd):/var/www v1_zf bash
For Windows
$ docker run --network="host" -it -v ${PWD}:/var/www v1_zf bash
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


