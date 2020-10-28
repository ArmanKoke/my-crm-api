<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Project

CRM api that uses Laravel Passport for client authentication (in this case front-end app) 
and Laravel Socialite for github, google etc. authentication. For now implemented deals CRUD.

## Deploy

- Create `.env` (note that i am using here pgsql driver)
- `composer install`
- `php artisan passport:install` run for generating keys
- `php artisan migrate --seed`
- Client side authenticated by token with backend server and user from client side authenticated by email and password

## ToDo

- MakeFile for deploy
- More Seeders
- Unit Tests

## Drawbacks

- Did not remove blade and front related staff
- Password not salted yet need to fix later
