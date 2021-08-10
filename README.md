# Symfony 5 skeleton

Use this repository as starter pack for your PHP application.
# What's inside?

* Symfony 5.3
* Doctrine 2.9 ORM
* Gedmo behavioral extensions
* Easy admin 3 (with login page)
* API platform (HTTP basic auth and JWT auth)
* Twig
* Nelmio CORS
* Data Fixtures (with Faker)
* User profile (with login page)

# Install

Install packages.
```bash
composer install
```

Create local env file.
```bash
cp .env .env.local
nano .env.local
```

Edit file and set database connection string. `DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"`


Create database.
```bash
php bin/console doctrine:database:create
```

Create tables.
```bash
php bin/console doctrine:schema:create
```

Load fixtures.
```bash
php bin/console doctrine:fixtures:load
```

Generate the JWT SSL keys:
```bash
php bin/console lexik:jwt:generate-keypair
```
Your keys will land in `config/jwt/private.pem` and `config/jwt/public.pem` (unless you configured a different path).

Available options:

--skip-if-exists will silently do nothing if keys already exist.
--overwrite will overwrite your keys if they already exist.
Otherwise, an error will be raised to prevent you from overwriting your keys accidentally.

Offical github repo [here](https://github.com/lexik/LexikJWTAuthenticationBundle).



