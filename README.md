# Boilerplate Portal for BMK Projects
# PHP 8
We are going to use PHP version 8.0 for this proyect.


## How to install PHP 8 in Ubuntu 20.04

```
sudo add-apt-repository ppa:ondrej/php
sudo apt install php8.0
sudo apt install php8.0-curl php8.0-gd php8.0-zip php8.0-mbstring php8.0-xml php8.0-pgsql
```


## Using composer

Run composer with a custom php version

```
php8.0 $(which composer) install
```

## Init and launch the server


Copy `.env.example` to `.env`


```
php8.0 artisan key:generate
```

To run the server in local

```
php8.0 artisan serve
```



# JS & CSS

```
npm ci
```

```
npm run dev
```


# DB

You can just call `./create_docker_db.sh` in the `utils/devdb` folder
to create a docker postgesql database.

Then configure `.env` to match the `utils/devdb/config.sh`

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5431
DB_DATABASE=localportal
DB_USERNAME=local
DB_PASSWORD=local
```

Execute all the migrations

```
php8.0 artisan migrate
```



```
php8.0 artisan migrate:fresh --seed
```

```
php8.0 artisan db:seed
```


@Javi: This is my particular seeder for test

php8.0 artisan db:seed --class=JaviSeeder



# Debug

@Javi: I like the clasic stderr

```
LOG_CHANNEL=stderr
```


# Steps 

- Clone the repo
- Copy .env.example into .env
- Set the db in the .env matching the config in utils/devdb/config.sh
- Run utils/devdb/create_docker_db.sh
- php8.0 artisan key:generate
- Composer install
- npm ci
- npm run dev

