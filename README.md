# ShopApp demo 

made with AngularJs and Laravel 5

## Setup Backend

1. create a virtual host for apache like shopapp/.example-apache.conf:

2. create database in mysql

mysql> create database shopapp character "utf8";

3. complete configuration file .env in backend

$ cd shopapp/api/
$ cp .example.env .env

4. install backend dependencies using composer:

$ cd shopapp/api/
$ composer install

5. run database installation with laravel and mock data

$ php artisan migrate
$ php artisan db:seed

7. generate app_key

$ php artisan key:generate
copy/paste the generated key to .env with the name APP_KEY=...yourKey...

6. give file access to write logs

$ sudo chmod -R 0777 shopapp/api/storage/

7. test the api from browser

http://shopapp.local/v1.0/user

## Setup Frontend

1. install front end dependencies using bower:

$ cd shopapp/web-app/
$ bower install

2. test the app from browser

http://shopapp.local/