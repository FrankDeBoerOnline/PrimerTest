# PrimerTest for User/Department Application

This project contains multiple docker containers for developing, building and testing.
For issue setup docker-compose is used building the images for PHP, NGinX, MySQL and PHPMYAdmin.

### Installation
Assuming you already cloned the project the first step is copying the dev .env file with settings for your local environment.

``cp ./.env-dev.dist ./.env``

Building the development environment

``docker-compose -f docker-compose-dev.yml build``

Running the environment

``docker-compose -f docker-compose-dev.yml up``

The following local url (assuming default  `.env`-variables)

- Web: `http://localhost:8000`
  By default it points to `./code/public/index.php`
- PHPMyAdmin: `http://localhost:9191`

### Development
Composer vendor packages are build withing the docker image so not available for your IDE. You can install the packages locally by running the following commands:

``composer install -d ./code``


Changes to composer packages or autoloader can be reflected in the running docker instance:

``./run composer install`` or ``./run composer --dump-autoload``


Running database migrations that are located in `./code/.config/db/migrations` with Phinx

``./run phinx migrate -e dev``


Creating a new migration. The new migration script will be created in `./code/.config/db/migrations`

``./run phinx create MyNewMigration``


# Running tests

``./run phpunit``

For more options on tests
``./run phpunit --help``