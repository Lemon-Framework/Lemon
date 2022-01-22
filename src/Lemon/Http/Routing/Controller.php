<?php

namespace Lemon\Http\Routing;

use Exception;
use Lemon\Support\Types\Str;

abstract class Controller
{
    public static function __callStatic($name, $_)
    {
        unset($_);
        if (preg_match('/with([A-Z][A-z]+)/', $name, $matches))
            return [new (get_called_class())(), Str::decapitalize($matches[1])->content];

        throw new Exception('Call to undefined method ' . self::class . '::' . $name);
    }
}
