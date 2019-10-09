# laravel-api-jwt

## How it works

`git clone https://github.com/andydptyo/laravel-api-jwt.git`

`cd laravel-api-jwt`

`composer install`

`cp .env.example .env`

open `.env` then edit `DB_DATABASE,DB_USERNAME,DB_PASSWORD` according to yours

`php artisan key:generate`

`php artisan jwt:secret`

`php artisan migrate`

if you want to insert dummy data run `php artisan db:seed`

`php artisan serve` it will run in `localhost:8000`

`vendor/bin/phpunit` to run test
