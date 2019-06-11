# Setup

1. Clone the public project

`git clone https://github.com/JonathanLafey/tm-mini-crm.git`

2. Create the local .env file by copying the provided .env.example (the expected values are already set)

`cp .env.example .env`

Even though putting credentials on a git is a bad practice, we only use local development credentials, so there is no security risk

## Install docker and docker compose

3. Install docker following [the official instructions](https://docs.docker.com/install/linux/docker-ce/ubuntu/). Docker is supported for most OSes.

4. Then install [docker-compose](https://docs.docker.com/compose/install/) which will help orchestrate various docker containers without any complex tool like Kubernetes or docker swarm.

# Run
We're using dockers in order to avoid installing php, apache/nginx, database, so on on our systems. It makes our software environment predictable and easily deployable on all dev, staging and production environments with minimal changes

For this project we've used [Laradock](https://laradock.io/), an awesome tool that dockerizes away pretty much the whole infrastructure for most Laravel projects.

## Containers setup

1. cd into the laradock directory

`cd ./laradock`

> **Important** all `docker-compose` commands should run from inside *laradock* directory

2. create the required .env file for laradock

`cp env.example .env`

3. Create and startup the required containers

`docker-compose up --detach nginx mariadb portainer`

We've using nginx as web server, mariadb as database and optionally portainer to have a visual status of the containers running. We also run the containers in detach mode so we can freely access them from the same cli

This may take a while the first time running as it has to first get all required images from the docker repository and then build the containers. Every followup start/restart of the services will be way faster.

## Prepare the project

Now with the containers running is time to setup the project essentials

### Create the required tables and seed the database

1. Install composer packages

`docker-compose exec workspace composer install`

2. Generate an application key
`docker-compose exec workspace php artisan key:generate`

3. Create tables

`docker-compose exec workspace php artisan migrate`

4. Insert initial users

`docker-compose exec workspace php artisan db:seed --class=UsersTableSeeder`

5. (optional) confirm that the admin user has been created

`docker-compose exec mariadb mysql -uuser -puser -e'select * from crm.users;'`

6. Put a symlink from `/public/storage` to `/storage/app/public` folder

This is to store client avatars in storage/app/public folder and make them accessible from public

`docker-compose exec workspace php artisan storage:link`

7. Add some test clients/transactions using faker

`docker-compose exec workspace php artisan db:seed --class=TestDataSeeder`

8. Install front-end dependencies

`docker-compose exec workspace npm install`

## Showcase

On your browser, head to `localhost`. You should see the default laravel template.
There is no UI provided but you can navigate to `localhost/login` and login with the required credentials (*username = admin@admin.com*, *password = password*).
You can also navigate to `localhost/register` to confirm that it has been deactivated

The rest of the functionality is only available through APIs. For ease of reference you can find a [postman](https://www.getpostman.com/) collection with the name `tickmill-crm.postman_collection.json` which you can [import to postman](https://learning.getpostman.com/docs/postman/collections/data_formats/#importing-postman-data). After that you need to [create an environment with 2 variables](https://learning.getpostman.com/docs/postman/environments_and_globals/variables/#accessing-variables-in-the-request-builder):
* url = localhost
* api_token = secret

All the routes require authentication but because we didn't implement a proper API authentication method like JWT, we're using a static token attached to the admin user as per [official guidelines](https://laravel.com/docs/5.8/api-authentication). Of course this is a suboptimal solution that needs to be replaced in the future.

> If you experience any permission issues regarding logs, then execute the following 3 commands
>
> `docker-compose exec workspace bash`
>
> `chown -R laradock:laradock storage/logs/`
>
> `exit`

# TODO:

These are the pending items that need to be done as they weren't implemented due to lack of time

* Front-end with Vue
* Tests
* More fine-grained API error handler
* More appropriate API authentication with JWT
* Guidelines for building a production-ready Dockerfile