# Contributing

Contributions are welcomed but please follow:

## Ways of contributing

- Write tests (see tests/README.md)
- Find and report bugs (see SECURITY.md)
- Add new consultant handlers (see src/Lemon/Debug/Handling/README.md)


If you want to add some features, please open issue before you open pull request.

## Pull request

Before you oepn pull request, make sure to run following:

```sh
$ ./vendor/bin/phpunit # tests
$ ./vendor/bin/phpstan # static analysis
$ ./vendor/bin/php-cs-fixer fix # fixes style
```
They all should pass before you open it.

