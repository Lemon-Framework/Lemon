# Lemon Error handler

This part contains error debug panel similar to spatie/laravel-ignition.

Debug panel is based on vue frontend along with Consultatnt which is class that shows hits based on error message.


## Adding hints

Start by defining hint in Consultant::$signatures like so:

```php
'Call to undefined function (\w+?)\(\)' => 'function'
```

Where key is regex and value name of handling method.

Handling method should be named in format `handleTarget` where Target is the same as value in $signatures.

The method should return array where all keys are possible hits, lower the index - higher the priority.
