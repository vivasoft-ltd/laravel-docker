## Laravel Docker

- Author: [Vivasoft Ltd](http://www.vivasoftltd.com)

If you want to use [Docker](https://www.docker.com) for your laravel project, this package will help you to create the container.
We provided most of the require tools to run a general laravel application. But, you will able to customize your container based on your need.

## Table of contents

- [Prerequisite](#prerequisite)
- [Option 1: Use with new project](#use-with-new-laravel-installation)
- [Option 2: Use with existing project](#use-with-existing-project)
- [Modify your `.env` file](#modify-environment-file)
- __Docker Compose:__
    - [PHP](#php-container)
    - [Database](#database-container)
    - [Nginx](#nginx-container)
    - [Redis](#redis-container)
- [Advanced](#advanced)    

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

Before building the docker image you should update your [database](#database-container) credential.

For [advanced usages](#advanced) you may want to update your [nginx](#database-container) configuration.

---

__Step 4:__ <br>
Run the application:
```bash
docker-compose up -d
```

It may take some time, so grab a cup of :coffee: :grimacing:


When done - [update your `.env` file](#modify-environment-file) and visit your IP address: http://your_ipaddress:port [default is: 80]

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

Before building the docker image you should update your [database](#database-container) credential.

For [advanced usages](#advanced) you may want to update your [nginx](#database-container) configuration.

---

__Step 3:__ <br>
Run the application:
```bash
docker-compose up -d
```

When done - [update your `.env` file](#modify-environment-file) and visit your IP address: http://your_ipaddress:port [default is: 80]

## Modify environment file

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

See the [advanced usages](#database-container) section for more options.

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

## License

The Vivasoft Laravel Docker is licensed under the terms of the [MIT License](https://github.com/vivasoft-ltd/laravel-docker/blob/master/LICENSE)