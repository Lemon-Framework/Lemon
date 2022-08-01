# 🍋 Lemon 3 

Pracujem na jednom phpcku ten sa bude volat Lemon... 3. Bude tam nieco zo symfony nieco z laravelu nieco z nette nieco z termwindu chcem to akokeby spojit do jedneho. 

Moral: dont be lazy, read the docs.

❗NOT READY FOR PRODUCTION EVERYTHING COULD (AND PROPABLY WILL) CHANGE❗

TODO:

- [x] setup unit testing
- [x] split routing from http
- [x] array slicing ["1..2"]
- [x] tests for array
- [x] tests for string
- [x] tests for arr and str
- [x] Better array map
- [ ] new session
- [x] new csrf (middleware?)
- [ ] cors
- [x] env
- [x] basic filesystem
- [x] tests for kernel
- [x] new kernel
- [x] split container
- [ ] database (base)
- [x] new lemonade kernel
- [x] dump die
- [x] reporter
- [x] consultant
- [x] caching core, psr
- [x] fake tests for caching
- [x] tests for caching
- [x] juice template engine (base)
- [x] rething config
- [x] events
- [x] logging
- [x] validation
- [x] Router
- [x] Middlewares

Directories with D means deprecated so they will have to be completely rewritten

## Contributing

Lemon 3 is under rapid rewriting, if you have ideas for Lemon 3 please create issue first. Bug fixes or tests will be propably accepted but please wait with features.

### How to contribute

Fork repo, create new branch, add stuff and:

```php
$ ./vendor/bin/phpunit # test
$ ./vendor/bin/phpstan # first static analysis
$ ./vendor/bin/php-cs-fixer fix # fixes style
```

Make sure all of them pass before sending pull request, if they wont pr wont be ignored, but its better to use it.

## Thanks

- CoolFido - Psychical helping, contributting, ideas, the whole idea of creating framework from scratch (he didn't expect that to happen)
- Mia - 'Lemon 2'
- Quapka - Unit testing philosofy
- Taylor Otwell - Made Laravel, which was big inspiration
- David Grudl - Made Nette, which was aslo great inspiration
- Nuno Maduro - Created Termwind which was used as inspiration in Terminal Component
- Azeem Hassni - Tutorial for creating simple router
- Marek_P - First user who made actual app in Lemon, his code was used as inspiration for new features
- Starganzers - ❤
