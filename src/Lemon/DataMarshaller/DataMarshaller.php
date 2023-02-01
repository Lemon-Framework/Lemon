<?php

declare(strict_types=1);

namespace Lemon\DataMarshaller;

use Lemon\Support\Types\Maybe;
use Lemon\Support\Types\Nothing;
use ReflectionClass;

class DataMarshaller
{
    /**
     * @param class-string<T> $class
     * @return T
     */
    public static function convert(array $data, string $class): ?object
    {
        $reflection = new ReflectionClass($class);
        $params = [];
        foreach ($reflection->getConstructor()->getParameters() as $property) {
            if (!isset($data[$property->getName()])) {
                return null;
            }
            $item = $data[$property->getName()];
            $value = static::typeCheck($item, (string) $property->getType());
            if ($value instanceof Nothing) {
                return null;
            }

            $params[$property->getName()] = $value->unwrap();
        }

        return new $class(...$params);
    }

    public static function typeCheck(mixed $value, string $type): Maybe
    {
        if (class_exists($type)) {
            if (!is_array($value)) {
                return Maybe::nothing();
            }
            return ($v = static::convert($value, $type)) === null ? Maybe::nothing() : Maybe::just($v);
        }

        $ok = @settype($value, $type);

        return $ok ? Maybe::just($value) : Maybe::nothing();
    }
}
