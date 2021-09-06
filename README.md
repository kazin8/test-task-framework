# Symfony test task

## Running

```
$ git clone git@github.com:kazin8/test-task-framework.git
$ cd test-task-framework
$ docker-compose up -d
$ docker exec -it app bash
$ bin/console doctrine:migrations:migrate --env=test
$ php ./vendor/bin/phpunit
```