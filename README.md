# 🍋 Lemon 3 

> Lemon is not revolution, Lemon is not for everyone. Its not 🚀 Web 3 🚀Fast 🚀Framework 🚀For 🚀 Today 🚀. I mean its php framework written in neovim. But its tool that was made for fun and can introduce to you to the world of php frameworks. Welcome to Lemon 3 development repo

Pracujem na jednom phpcku ten sa bude volat Lemon... 3. Bude tam nieco zo symfony nieco z laravelu nieco z nette nieco z termwindu chcem to akokeby spojit do jedneho. 

![Blueprint](https://raw.githubusercontent.com/Lemon-Framework/static/master/images/lemon_bp.png)

WIP Lemon 3

❗NOT READY FOR PRODUCTION ❗

trust me

TODO:

- [x] setup unit testing
- [ ] split routing from http
- [x] array slicing ["1..2"]
- [x] tests for array
- [ ] array->hasSimilar
- [ ] other cool array methods propably BUT not that much ok do it while writing pls
- [x] tests for string
- [x] tests for arr and str
- [ ] Strng::trim
- [ ] Better array map
- [ ] new session
- [ ] new csrf (middleware?)
- [ ] cors
- [ ] env
- [x] basic filesystem
- [ ] tests for kernel
- [x] new kernel
- [x] split container
- [ ] database
- [ ] new lemonade kernel
- [ ] dump die
- [ ] reporter
- [ ] consultant
- [ ] caching (psr?)
- [ ] tests for caching
- [ ] juice template engine
- [ ] mailer?
- [ ] auth?

Directories with D means deprecated so they will have to be completely rewritten

## Contributing

Lemon 3 is under rapid rewriting, if you have ideas for Lemon 3 please create issue first. Bug fixes or tests will be propably accepted but please wait with features.

### How to contribute

Fork repo, create new branch, add stuff and:

```php
$ ./vendor/bin/phpunit # test
$ ./vendor/bin/phpstan # first static analysis
$ ./vendor/bin/psalm # second static analysis
$ ./vendor/bin/php-cs-fixer # fixes style
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
- Starganzers - ❤

*"Stolen from the best, with pride!"*
