# Juice

Juice is Lemons template engine that compiles to raw php code. It does that by lexing into tokens via regular expressions and then parsing.

Default syntax are:
- `{{ $variable }}` - variable $variable will be safely echoed
- `{! $variable !}` - variable $variable will be unsafely echoed
- `{ tag args }` - this will replace into one of juices supported tag

You can modify those syntax in config:

- Each regex mustn't contain starting and ending deliminers
- Tag regex must have one match for tag name and second optional match for tag arguments
- End regex mustn't have tag logic, only regex that describes end tag. It has to have 1 match that is tag that is ended
- Echo and unescaped echo tags must have 1 match for content

Custom syntax are very risky so be aware.
