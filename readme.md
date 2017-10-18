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
