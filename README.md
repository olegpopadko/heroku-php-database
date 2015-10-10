# heroku-php-database-environment
Simple connect to [herokupostgres](https://www.heroku.com/postgres) for php-applications

##Usage

Add the following in your root composer.json file:

```json
{
    "require": {
        "olegpopadko/composer-heroku-database-environment": "dev-master"
    },
    "scripts": {
        "post-install-cmd": [
            "OlegPopadko\\HerokuDatabaseEnvironment\\Composer\\EnvironmentHandler::expand"
        ]
    }
}
```

`EnvironmentHandler` divides `DATABASE_URL` environment variable and expands your environment with new five variables.

After `composer install` you will have something like this in your environment 

```bash
DATABASE_URL=postgres://user:password@database.host:5432/database_name

DATABASE_HOST=database.host
DATABASE_PORT=5432
DATABASE_USER=user
DATABASE_PASSWORD=password
DATABASE_NAME=database_name
```

Now you can easily setup your app with Heroku database credentials.

##Addition setup for [incenteev/composer-parameter-handler](https://github.com/Incenteev/ParameterHandler)

You can setup it in `composer.json` like this:

```json
{
  "scripts": {
      "post-install-cmd": [
          "OlegPopadko\\HerokuDatabaseEnvironment\\Composer\\EnvironmentHandler::expand",
          "Incenteev\\ParameterHandler\\ScriptHandler::buildParameters"
      ]
  },
  "extra": {
        "incenteev-parameters": {
            "file": "app/config/parameters.yml",
            "env-map": {
                "database_host": "DATABASE_HOST",
                "database_port": "DATABASE_PORT",
                "database_name": "DATABASE_NAME",
                "database_user": "DATABASE_USER",
                "database_password": "DATABASE_PASSWORD"
            }
        }
    }
}
```

**Note:** `EnvironmentHandler::expand` must be executed before `ScriptHandler::buildParameters`.

It's would be usefull if you are using [Symfony](https://symfony.com/).
