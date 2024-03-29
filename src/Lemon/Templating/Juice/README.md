# Juice

Juice is Lemons template engine that compiles to raw php code. It does that by lexing into tokens via regular expressions and then parsing.

Its heavily influenced by [nette/latte](https://github.com/nette/latte)

Default syntax are:
- `{{ $variable }}` - variable $variable will be safely echoed
- `{! $variable !}` - variable $variable will be unsafely echoed
- `{ tag args }` - this will replace into one of juices supported tag
- `{# anything#}` - this wont be included in render - comment

You can modify those syntax in config:

- Each regex mustn't contain starting and ending deliminers
- Tag regex must have one match for tag name and second optional match for tag arguments
- End regex mustn't have tag logic, only regex that describes end tag. It has to have 1 match that is tag that is ended
- Echo and unescaped echo tags must have 1 match for content

Custom syntax are very risky so be aware.

## Plans

- [x] lexer
- [x] each tag compiler is separate class that takes token and returns php code
- [x] context-based rendering like in Latte https://stackoverflow.com/questions/2725156/complete-list-of-html-tag-attributes-which-have-a-url-value, `on.+='|"`, `src|href|...=`
- [x] syntax bundle of blade and twig (For blade `@([^\(]+)(?(?=\()\((.+?)\))` this is what my mind made in 23:15 CET i will regret this tommorow cause i have no idea how it works but it works lets goooooo)
- [x] syntax highlight for vim (emacs?)
- [x] ability of custom tags
- [x] metaframework
- [x] elixir-like piping?
- [ ] safer syntax check
- [ ] syntax for actual {}
- [x] includes, extends,...
- [x] line counting
- [ ] rewrite lexer
- [ ] migrate to stack

## Contributing

You can add more directives.
