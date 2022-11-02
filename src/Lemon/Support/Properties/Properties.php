<?php

declare(strict_types=1);

namespace Lemon\Support\Properties;

trait Properties
{
    public function __get(string $name): mixed
    {
        if (in_array($name, array_keys(get_class_vars(static::class)))) {
            $class = new \ReflectionClass(static::class);
            $property = $class->getProperty($name);
            if ($property->getAttributes(Read::class)) {
                return $this->{$name};
            }
        }

        throw new \Exception('Undefined property '.$name);
    }
}
