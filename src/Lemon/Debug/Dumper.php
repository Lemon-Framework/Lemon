<?php

namespace Lemon\Debug;

class Dumper 
{

    /**
     * Parses countable object as visualization
     *
     * @param Countable $countable
     * @return string
     */
    public function parseIterator($iterator): string
    {
        $class = is_object($iterator) ? get_class($iterator) : gettype($iterator);
        $result = '<details><summary>' . $class . ' [</summary>';
        foreach ($iterator as $key => $value) {
            $result .= '<span class="ldg-array-item"><span class="ldg-array-key">[' . $key . ']</span> => ' . $this->resolve($value) . '</span>';
        }

        return $result . '</details>]';
    }

    /**
     * Parses object as visualization
     *
     * @param Object $object
     * @return string
     */
    public function parseObject(Object $object): string
    {
        $class = get_class($object);
        $result = '<details><summary>' . $class . ' [</summary>';
        foreach (array_keys(get_class_vars($class)) as $property) {
            $result .= '<span class="ldg-property"><span class="ldg-property-name">' . $property . '</span> => ' . $object->$property . '</span>';
        }

        return $result . '</details>]';
    }

    /**
     * Parses string as visualization
     *
     * @param string $string
     * @return string
     */
    public function parseString(string $string): string
    {
        return '<span class="ldg-string">"' . $string . '"</span>';
    }

    /**
     * Parses numeric value as visualization
     *
     * @param mixed $numeric
     * @return string
     */
    public function parseNumber($numeric): string
    {
        return '<span class="ldg-number">' . $numeric . '</span>';
    }

    /**
     * Parses boolean as visualization
     *
     * @param bool $bool
     * @return string
     */
    public function parseBool(bool $bool): string
    {   
        $value = $bool ? 'true' : 'false';
        return '<span class="ldg-bool">' . $value . '</span>';
    }

    /**
     * Parses null as visualization
     *
     * @return string
     */
    public function parseNull(): string
    {
        return '<span class="ldg-null">null</span>';
    }

    /**
     * Resolves parsing method depending on datatype
     *
     * @param mixed $data
     * @return string
     */
    public function resolveType($data): string
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
    }

    public function resolve($data): string
    {
        $method = $this->resolveType($data);
        return $this->$method($data);
    }

    /**
     * Dumps given value
     *
     * @param mixed $data
     * @return string
     */
    public function dump($data): string
    {
        return '<div class="ldg-bg">' . $this->resolve($data) . '</div>';
    }
}
