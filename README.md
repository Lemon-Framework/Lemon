# 🍋 Lemon

Tato složka obsahuje celý framework Lemon, který je nutný pro úplnost překladače Juice. Kód překladače je v adresáři `src/Lemon/Templating` a unit testy v `\tests\Templating`.

Je vyžadováno PHP 8.3. Pro spuštění testů je nutný composer, pro použití v aplikaci je potřeba vytvořit Lemon aplikaci a jako verzi Lemonu použít dev-juice. Více v dokumentaci níže.

Lemon is microframework for php designed to be simple for begginers. <!-- TODO -->

## Features

- Simple to use router
- Safe, hackable template engine Juice
- Simplified database manipulation
- Debugging tools
- Html-based Terminal layer
- Http abstraction
- Caching
- All bundled using DI container

- Whole framework was heavilly inspired by:
    - [laravel/framework](https://github.com/laravel/framework)
    - [nette](https://github.com/nette) (mostly [latte](https://github.com/nette/latte))
    - [nunomaduro/termwind](https://github.com/nunomaduro/termwind)
    - [feast-framework/framework](https://github.com/feast-framework/framework)

## Learning Lemon

There is [documentation](https://github.com/Lemon-Framework/docs) (currently unfinished). And there are discord events of writing apps in Lemon (in Czech)

## Installing

`composer require lemon_framework/lemon`

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
