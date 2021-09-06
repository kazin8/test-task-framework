# Symfony test task

## Running

```
$ git clone git@github.com:kazin8/test-task-framework.git
$ cd test-task-framework
$ docker-compose up -d
$ docker exec -it symfony_app bash
```

In container:
```
$ composer install
$ bin/console doctrine:migrations:migrate --env=test --no-interaction
$ php ./vendor/bin/phpunit
```

P.S. if something went wrong with the postgresql container and users and dbs were not created, you need to delete the folder

```
{projectFolder}/docker/postgres/runtime/data
```

and run docker-compose again