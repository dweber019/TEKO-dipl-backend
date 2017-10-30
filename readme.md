## About this project

This project was build as a result of a diploma exam. It's an API to manage lessons and subjects of an educational institution.

## Learning Laravel

Laravel has the most extensive and thorough documentation and video tutorial library of any modern web application framework. The [Laravel documentation](https://laravel.com/docs) is thorough, complete, and makes it a breeze to get started learning the framework.

If you're not in the mood to read, [Laracasts](https://laracasts.com) contains over 900 video tutorials on a range of topics including Laravel, modern PHP, unit testing, JavaScript, and more. Boost the skill level of yourself and your entire team by digging into our comprehensive video library.

## Installation

There are a few thing to install before you can use this project.
1. Install PHP 7 ([Tutorial Brew](https://developerjack.com/blog/2016/installing-php71-with-homebrew/))
2. Install Composer ([Tutorial Brew](https://pilsniak.com/install-composer-mac-os/))
3. Go to your project root and run "composer install"
4. Install MySQL ([Tutorial Brew](https://gist.github.com/nrollr/3f57fc15ded7dddddcc4e82fe137b58e))

### Setup CF services

MariaDB:
1. Login to [Developer Portal](https://console.developer.swisscom.com)
2. Create or navigate to a Org / Space
3. Create a MariaDB Service
4. Bind the service to this app

S3:
1. Do step 1 and 2 from MariaDB
2. Create a S3 Service
3. Bind the service to this app
4. Update your `.env` with the new service settings or use a other disk as cloud

ELK:
1. Do step 1 and 2 from MariaDB
2. Create a ELK Service
3. Bind the service to this app

### Setup Mailgun
Go to [Mailgun](https://www.mailgun.com/) and add a new domain like [this](https://help.mailgun.com/hc/en-us/articles/203637190-How-do-I-add-a-domain-).
Fill in the domain and the domain key in `.env` and create a custom service in CF with name `dipl-mailgun`
and two credential keys `MAILGUN_DOMAIN` and `MAILGUN_SECRET`.

### Setup Auth0
Go to [Auth0](https://auth0.com/) and create a new tenant.
1. Create a Resource Server like described [here](https://auth0.com/docs/quickstart/backend/laravel#create-a-resource-server-api-).
2. Create a client like in section "Get Your Application Keys" in [here](https://auth0.com/docs/quickstart/spa/angular2#get-your-application-keys).

Now fill in the settings in `.env` and create a custom service in CF with name `dipl-auth0` and add the following
credentials to it: `AUTH0_CLIENT_ID`, `AUTH0_CLIENT_SECRET`, `AUTH0_DOMAIN`, `AUTH0_AUDIENCE`, `AUTH0_CALLBACK_URL`

## Run

First you need to copy and rename the .env.example to .env and fill in the your configurations.

After that the only thing to start up the backend is to enter `php artisan serve` and hit enter.

## API Documentation

Once the backend is up and running visit the url `../api/documentation` to view the API documentation.
You will find a swagger documentation which is also oAuth enabled.

## Tests

To run the tests you have to enter `vendor/bin/phpunit` in the root of the project.

## Deployment

Currently there is a `manifest.yaml` file which holds all the Cloud Foundry specific information.
In addition in the root of the project, in folder `.bp-config` are additional configurations regarding the PHP configuration.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
