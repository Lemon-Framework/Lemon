<?php

declare(strict_types=1);

namespace Lemon\DataMapper;

use Lemon\Support\Types\Maybe;
use Lemon\Support\Types\Nothing;
use ReflectionType;

class DataMapper
{
    /**
     * @template T of object
     *
     * @param class-string<T> $class
     *
     * @return ?T
     */
    public static function mapTo(array $data, string $class): ?object
    {
        $reflection = new \ReflectionClass($class);
        $params = [];
        foreach ($reflection->getConstructor()->getParameters() as $property) {
            $item = $data[$property->getName()] ?? null;
            $value = static::typeCheck($item, $property->getType());
            if ($value instanceof Nothing) {
                return null;
            }

            $params[$property->getName()] = $value->unwrap();
        }

        return new $class(...$params);
    }

    public static function typeCheck(mixed $value, ReflectionType $type): Maybe
    {
        $type_name = trim((string) $type, '?');
        if (class_exists($type_name)) {
            if ($type->allowsNull() && $value === null) {
                return Maybe::just(null);
            }

            if (!is_array($value)) {
                return Maybe::nothing();
            } 

            return ($v = static::mapTo($value, $type_name)) === null ? Maybe::nothing() : Maybe::just($v);
        }

        if (!$type->allowsNull() && $value === null) {
            return Maybe::nothing();
        }

        $ok = @settype($value, $type_name);

        return $ok ? Maybe::just($value) : Maybe::nothing();
    }
}
