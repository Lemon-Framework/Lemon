# Contributing

Contributions are welcomed but please follow:

## Ways of contributing

- Write tests (see tests/README.md)
- Find, report and fix bugs (see SECURITY.md)

For more details about contributing to specific component see its README.

Before you add big feature please open issue first.

## Pull request

Before you oepn pull request, make sure to run following:

```sh
$ ./vendor/bin/phpunit # tests
$ ./vendor/bin/phpstan # static analysis
$ ./vendor/bin/php-cs-fixer fix # fixes style
```
They all should pass before you open it.

