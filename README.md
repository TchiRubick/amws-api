# AMWS API

This is the backend API for AMWS build on top of Laravel 9

## Installation

It's recommended to use the *Docker* configuration to run this project.  
In this case tou don't have to do more setup than usual

### Docker Installation

#### Build the container: `docker-compose up -d --build`

#### Move into the container: `docker exec -it test-api bash`

#### Run the migration script: `php artisan migrate --seed`

#### `composer install` is launched on build time

#### Access to storage, cache folder

You may encounter a permission problem with the storage folder.  
Our container hanlde that permission problem on build time.  
If you still encounter that problem, please grant the permission manually.

#### Environment Variable

With our docker environment, you only have to create a *.env* file and copy the content of our *.env.example* into your *.env* file.

### Any other installation (Out of docker)

#### First verify your *.env* file if all of the value match your configuration

#### Run `composer install`

#### Run the migration `php artisan migrate --seed`

## Front app

The CORS is only configured for *localhost* use only.  
If you want to use it with an other Domain, you still need to dive more into the configuration.

Please use this API with that front app: <https://github.com/TchiRubick/amws-front>

## Please contact me on <mailto:tchi.devica@gmail.com> if you have some question
