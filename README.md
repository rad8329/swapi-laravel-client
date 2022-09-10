<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## About this project

This is a basic Laravel app to practice a common synchronous communication using REST 

### Related

- [Docker](https://www.docker.com/)
- [Docker compose](https://docs.docker.com/compose/)
- [Laravel](https://laravel.com)
- [Livewire](https://laravel-livewire.com/)
- [SWAPI REST API](https://swapi.dev/)

### Requirements

- PHP 8.1

*Note: Just in case you dont want to have another PHP version on your dev environment, please consider to use* Docker *, see the* [Tooling](#tooling) *section*

### Setup

#### Using local environment

Placed into the project folder run the command $ `composer install`

#### Using Docker

Placed into the project folder run the command $ `docker-compose up -d && composer install`

*Note: You could change the application port by changing the environment variable `APP_PORT`*

### How to use it

If you're using Docker, just go to the url [http://localhost:8080](http://localhost:8080)

### Tooling

There are some docker wrappers within the folder `bin`, they are:
- `./bin/@composer` *example* `./bin/@composer dumpautoload`
- `./bin/@php` *example* `./bin/@php artisan optimize`
- `./bin/@npm`
- `./bin/cli` *to get into the docker container*

### Nice to have

- Synchronous communication using SOAP, a nice one would be to display in words the person `height` and `mass` using https://www.dataaccess.com/webservicesserver/NumberConversion.wso?WSDL
- Feature test cases to ensure the proper working from the user perspective
- Implementation of circuit breaker pattern to properly deal with third party downtimes
