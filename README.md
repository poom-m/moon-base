# MoonBase - Cryptocurrency exchange

## Project Overview

This project is for user to trade THBT (Thai Baht Tether) and MOON coin, a new crypto currency.
They can buy MOON according to exchange rate. The MOON price will increase by 10% for each 10 MOON sold.

## Technical Stacks

This project include following technical stacks:

- **Nuxt.JS** as front end framework, access via [http://localhost:8080](http://localhost:8080)
- **Laravel** as back end api, access via [http://localhost:8080/api](http://localhost:8080/api)
- **PostgreSQL** as database
- Nginx to proxy each request to front end and back end
- Docker boilerplate build based on [nevadskiy/laravel-nuxt-docker](https://github.com/nevadskiy/laravel-nuxt-docker) to minimize set up processs

## Prerequisites

- Git
- Docker-compose
- Make tool

## Installation

**1. Run the build script (it may take up to 10 minutes)**

```
# Make command
make build

# or Full command
docker-compose up -d --build
```

**2. Run database migration to create database structure**

```
# Make command
make db-migrate

# or Full command
docker-compose exec php php artisan migrate
```

**3. Run database seeder to populate required data**

```
# Make command
make db-seed

# or Full command
docker-compose exec php php artisan db:seed
```

**4. Done! Connect to the server via browser**

[http://localhost:8080](http://localhost:8080)

_If you see the 502 error page, just wait a moment to let front end server build and compile code or check the status with command `make logs-client` or `docker-compose logs client`_

## Database

If you need to connect to the database, use following credentials

```
HOST: localhost
PORT: 54321
DB: app
USER: app
PASSWORD: app
```

## Usage

Upon landing on the home page, on load and every refresh you will be given an auto-generated ID as well as 100 THBT in your balance. Every refresh page will cause the system to generate new ID thus, treating you as another user.

Input amount you want to buy in THBT field and the system will calculate MOON for you, the other way round is also true. The system will not allow you to input THBT and MOON less than 0 and more that their current balance. You can also buy in decimal points.

Input slippage tolerance and click buy. If the value is valid: no validation error, the correct exchange rate is calculated and not over slippage tolerance, THBT amount is not more than current balance, and MOON amount is also not more than exchange's MOON balance, then the order will be created and success page will display. If not, you will see an error page.

You can visit history page to view the order.

Price will increase by 10% for every 10 MOON sold. 'Edge' case is handled according to requirement as when 9 MOON are sold, when a user want to buy with 75 THBT, they will get 1.454545 MOON.

To perform unit test, please use following command.

```
# Make command
make test-all

# or Full command
docker-compose exec php vendor/bin/phpunit --order-by=defects
```

Unfortunately, the real time update function is not completed due to time constraint.
