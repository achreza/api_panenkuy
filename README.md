# API PANENKUY
this repository provide API to Panenkuy application, the entire application is made by using laravel 8 

## Requirements
- composer 2.x
- laravel 8
- php >= 7.4

## Installation
- first clone this repo
``` bash
$ git clone https://github.com/bintangmfhd/api-panenkuy.git
```
- go into the project directory
``` bash
$ cd api-panenkuy
```
- then install the laravel
``` bash
$ composer install
```
- after that copy the `.env.example` file and update it using your database credentials and mail driver
``` bash
$ cp .env.example .env
```
- do artisan migrate
``` bash
$ php artisan migrate --seed
```
- lastly serve your server
``` bash
$ php artisan serve
```

### Add Dummy Data
``` bash
$ php artisan db:seed --class=DummySeeder
```

## API Documentation
- https://documenter.getpostman.com/view/15292396/UV5RkKeM
## DB
- https://dbdocs.io/shevaathalla/panenskuy# api_panenkuy
# api_panenkuy
