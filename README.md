## Laravel Docker

[Vivasoft Ltd](http://www.vivasoftltd.com)

If you want to use [docker](https://www.docker.com) with your laravel project, this package will help you to create the container. 
It contains most of the require software to run a laravel application; 
it also provides flexibility to customize your container based on your need.

## Table of contents

- [Prerequisite](#prerequisite)
- __Installation & Setup:__
    - __Install:__
        - [With a new project](#use-with-new-laravel-installation)
        - [Or, into an existing project](#use-with-existing-project)
    - [Update `.env` file](#update-env-file)
- __Docker Compose Settings:__
    - [PHP](#php)
    - [Database](#database)
    - [Nginx](#nginx)
    - [Redis](#redis)
- __Daily Usages:__
    - [Connecting Via SSH](#connecting-via-ssh)
    - [Running `php artisan` command](#running-php-artisan-command)
    - [Connect To Database](#connect-to-database)
- [Advance Usages](#advance-usages)

## Prerequisite

- Docker Engine >= 17.04.0

## Use with new laravel installation

__Step 1:__ <br>
Open your terminal, navigate to your project directory and run the following command to install the latest version.

```bash
docker run --rm --interactive --tty --volume ${PWD}:/app composer create-project --prefer-dist laravel/laravel .
```

__Step 2:__ <br>
Install `vivasoft/laravel-docker` package using the following command:
```bash
docker run --rm --interactive --tty --volume ${PWD}:/app composer require vivasoft/laravel-docker:dev-master
```

__Step 3:__ <br>
Run the following command to publish your docker files into your root project directory. 
```bash
docker run --rm --interactive --tty --volume ${PWD}:/app composer php artisan vivasoft:dockerInstall
```

A new folder `.docker` along with two other files `docker-compose.yml` and `Dockerfile` should copy to your root installation directory.

---
**NOTE**

Before building the docker image you should update your [database](#database) credential.

For [advanced usages](#advance-usages) you may want to update your [nginx](#nginx) configuration.

---

__Step 4:__ <br>
Run the application:
```bash
docker-compose up -d
```

It may take some time, so grab a cup of :coffee: :grimacing:


When done - [update your `.env` file](#update-env-file) and visit your IP address: http://your_ipaddress:port [default is: 80]

## Use with existing project

__Step 1:__ <br>
Install `vivasoft/laravel-docker` package:
```bash
composer require vivasoft/laravel-docker:dev-master
```

__Step 2:__ <br>
Publish docker components by running the following command:
```bash
php artisan vivasoft:dockerInstall
```
A new folder `.docker` along with two other files `docker-compose.yml` and `Dockerfile` should copy to your root installation directory.

---
**NOTE**

Before building the docker image you should update your [database](#database) credential.

For [advanced usages](#advance-usages) you may want to update your [nginx](#nginx) configuration.

---

__Step 3:__ <br>
Run the application:
```bash
docker-compose up -d
```

When done - [update your `.env` file](#update-env-file) and visit your IP address: http://your_ipaddress:port [default is: 80]

## Update `.env` file

Open your `docker-compose.yml` file and use the related value. 

Suppose your `docker-compose.yml` settings:
```yaml
  #MySQL
  db:
    image: mysql:5.7.28
    container_name: db
    restart: unless-stopped
    tty: true
    ports:
      - "3306:3306"
    environment:
      MYSQL_DATABASE: one_database
      MYSQL_ROOT_PASSWORD: root
      SERVICE_TAGS: dev
      SERVICE_NAME: mysql
    volumes:
      - dbdata:/var/lib/mysql
      - ./.docker/mysql/my.cnf:/etc/mysql/my.cnf
    networks:
      - app-network
```

In your `.env` file you have to update the following value:

The `DB_HOST` should be the `container_name` of your `#MySQL Container`. <br>
The `DB_DATABASE` should be same as `MYSQL_DATABASE`. <br>
The `DB_PORT` should be same as `3306`. <br>
The `DB_PASSWORD` should be same as `MYSQL_ROOT_PASSWORD`. <br>

See the [advanced usages](#database) section for more options.

**EXAMPLE**

BEFORE UPDATE:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

AFTER UPDATE:

```dotenv
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=one_database
DB_USERNAME=root
DB_PASSWORD=root
```

## PHP

Default `PHP` settings:
```yaml
  #PHP
  app:
    build:
      context: .
      dockerfile: Dockerfile
    image: vivasoft/php
    container_name: app
    restart: unless-stopped
    tty: true
    environment:
      SERVICE_NAME: app
      SERVICE_TAGS: dev
    working_dir: /var/www
    volumes:
      - ./:/var/www
      - ./.docker/php/local.ini:/usr/local/etc/php/conf.d/local.ini
    networks:
      - app-network
```

- __`php.ini:`__ you can modify or add any settings on your host machine's `.docker/php/local.ini` file and it should apply the changes on your application.
- __`Dockerfile:`__ contains all the require tools to build the `vivasoft/php` image. If you need any __additional piece of software__ or another __php extension__ you can easily add them in this file.
See the [official documentation](https://docs.docker.com/engine/reference/builder/) for more information. After modifying the file you have to [rebuild](#advance-usages) the image. 

## Database

__Default__ settings:
```yaml
  #MySQL
  db:
    image: mysql:5.7.28
    container_name: db
    restart: unless-stopped
    tty: true
    ports:
      - "3306:3306"
    environment:
      MYSQL_DATABASE: one_database
      MYSQL_ROOT_PASSWORD: root
      
      MYSQL_USER: homestead
      MYSQL_PASSWORD: secret
      
      SERVICE_TAGS: dev
      SERVICE_NAME: mysql
    volumes:
      - dbdata:/var/lib/mysql
      - ./.docker/mysql/my.cnf:/etc/mysql/my.cnf
    networks:
      - app-network
```

- You can use more `environment` variables. `MYSQL_USER` and `MYSQL_PASSWORD` are most important among them.

See more option on [docker mysql official](https://hub.docker.com/_/mysql) page.

## Nginx

__Default__ settings:
```yaml
  #Nginx
  webserver:
    image: nginx:latest
    container_name: webserver
    restart: unless-stopped
    tty: true
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/var/www
      - ./.docker/nginx/conf.d/:/etc/nginx/conf.d/
    networks:
      - app-network
```
__Run application into another ports:__ update the `docker-compose.yml` file.
```yaml
  #Nginx
  webserver:
    ...
    ports:
      - "NEW_PORT:80"
      - "443:443"
    ...
```
__Add SSL Certificate__: Coming Soon.

## Redis

## Connecting via SSH

When your container up and running. You can SSH into your container by using the following command:

```bash
docker exec -it bash CONTAINER_NAME
```

## Running `php artisan` command

There are two options to execute your `php artisan` command.

__Option 1__: <br>
Run the following command from your project root directory.
```bash
docker-compose exec app php artisan
```
**n.b:** `app` is name of your PHP container.

__Option 2__: [SSH](#connecting-via-ssh) into your PHP container then run `php artisan`

## Connect To Database

See [Update `.env` file](#update-env-file) section for current settings:

The `DB_HOST` should be your __IP Address__ <br>
The `DB_DATABASE` should be `MYSQL_DATABASE`. <br>
The `DB_PORT` should be same as `3306`. <br>
The `DB_USERNAME` should be `MYSQL_ROOT_PASSWORD` or `MYSQL_USER` <br>
The `DB_PASSWORD` should be `MYSQL_ROOT_PASSWORD` or `MYSQL_PASSWORD`. <br>

## Advance Usages

__Rebuilding Image:__
- You can rebuild the image using `docker-compose up -d --build` command.

**Coming Soon**

## Contributor

 - [Faisal Islam](https://github.com/nscreed)
 - [Sazedul Islam](https://github.com/sazid1462)

## License

The Vivasoft Laravel Docker is licensed under the terms of the [MIT License](https://github.com/vivasoft-ltd/laravel-docker/blob/master/LICENSE)