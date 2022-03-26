<?php

declare(strict_types=1);

namespace Lemon\Debug;

use Lemon\Exceptions\DebugerException;

class Dumper
{
    /**
     * Parses countable object as visualization.
     */
    public function parseIterator(mixed $iterator): string
    {
        $class = is_object($iterator) ? $iterator::class : gettype($iterator);
        $result = '<details><summary>'.$class.' [</summary>';
        foreach ($iterator as $key => $value) {
            $result .= '<span class="ldg-array-item"><span class="ldg-array-key">['.$key.']</span> => '.$this->resolve($value).'</span>';
        }

        return $result.'</details>]';
    }

    /**
     * Parses object as visualization.
     */
    public function parseObject(object $object): string
    {
        $class = $object::class;
        $result = '<details><summary>'.$class.' [</summary>';
        foreach (array_keys(get_class_vars($class)) as $property) {
            $result .= '<span class="ldg-property"><span class="ldg-property-name">'.$property.'</span> => '.$object->{$property}.'</span>';
        }

        return $result.'</details>]';
    }

    /**
     * Parses string as visualization.
     */
    public function parseString(string $string): string
    {
        return '<span class="ldg-string">"'.$string.'"</span>';
    }

    /**
     * Parses numeric value as visualization.
     */
    public function parseNumber(mixed $numeric): string
    {
        return '<span class="ldg-number">'.$numeric.'</span>';
    }

    /**
     * Parses boolean as visualization.
     */
    public function parseBool(bool $bool): string
    {
        $value = $bool ? 'true' : 'false';

        return '<span class="ldg-bool">'.$value.'</span>';
    }

    /**
     * Parses null as visualization.
     */
    public function parseNull(): string
    {
        return '<span class="ldg-null">null</span>';
    }

    /**
     * Resolves parsing method depending on datatype.
     */
    public function resolveType(mixed $data): string
    {
        if (is_iterable($data)) {
            return 'parseIterator';
        }

        if (is_object($data)) {
            return 'parseObject';
        }

        if (is_string($data)) {
            return 'parseString';
        }

        if (is_numeric($data)) {
            return 'parseNumber';
        }

        if (is_bool($data)) {
            return 'parseBool';
        }

        if (is_null($data)) {
            return 'parseNull';
        }
        $type = gettype($data);

        throw new DebugerException("Type {$type} cant be dumped.");
    }

    public function resolve($data): string
    {
        $method = $this->resolveType($data);

        return $this->{$method}($data);
    }

    /**
     * Dumps given value.
     */
    public function dump(mixed $data): string
    {
        return '<div class="ldg-bg">'.$this->resolve($data).'</div>';
    }
}
