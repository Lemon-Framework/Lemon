<?php

declare(strict_types=1);

namespace Lemon\Support;

final class CaseConverter
{
    public static function fromCamelPascal(string $camel): ?array
    {
        $result = preg_split('/([A-Z][a-z]*)/', $camel, -1, PREG_SPLIT_DELIM_CAPTURE);

        return $result[0] === $camel
            ? null
            : array_values(array_map('strtolower', array_filter($result)))
        ;
    }

    public static function fromSnakeKebab(string $snake): array
    {
        return preg_split('/-|_/', $snake);
    }

    public static function toArray(string $target): array
    {
        return
            static::fromCamelPascal($target)
            ?? static::fromSnakeKebab($target)
        ;
    }

    public static function toPascal(string $target): string
    {
        $array = static::toArray($target);

        return array_reduce(
            $array,
            fn ($cary, $item) => $cary.ucfirst($item),
            ''
        );
    }

    public static function toCamel(string $target): string
    {
        $array = static::toArray($target);

        return array_reduce(
            array_slice($array, 1),
            fn ($cary, $item) => $cary.ucfirst($item),
            $array[0]
        );
    }

    public static function toSnake(string $target): string
    {
        $array = static::toArray($target);

        return implode('_', $array);
    }

    public static function toKebab(string $target): string
    {
        $array = static::toArray($target);

        return implode('-', $array);
    }
}
