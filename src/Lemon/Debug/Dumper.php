<?php

declare(strict_types=1);

namespace Lemon\Debug;

use Lemon\Config\Config;
use Lemon\Exceptions\DebugerException;

class Dumper
{
    private Style $style;

    private bool $is_style_rendered = false;

    public function __construct(Config $config)
    {
        $this->style = $config->part('debug')->get('dump')['style'];
    }

    /**
     * Resolves parsing method depending on datatype.
     */
    public function resolve(mixed $data): string
    {
        if (is_iterable($data)) {
            return $this->parseIterator($data);
        }

        if (is_object($data)) {
            return $this->parseObject($data);
        }

        if (is_string($data)) {
            return $this->parseString($data);
        }

        if (is_numeric($data)) {
            return $this->parseNumber($data);
        }

        if (is_bool($data)) {
            return $this->parseBool($data);
        }

        if (is_null($data)) {
            return $this->parseNull();
        }
        $type = gettype($data);

        throw new DebugerException("Type {$type} cant be dumped.");
    }

    /**
     * Dumps given value.
     */
    public function dump(mixed $data): string
    {
        $style = '';
        if (!$this->is_style_rendered) {
            $this->is_style_rendered = true;
            $style = $this->style->generate();
        }

        return $style.'<div class="ldg">'.$this->resolve($data).'</div>';
    }

    /**
     * Parses countable object as visualization.
     */
    private function parseIterator(mixed $iterator): string
    {
        $class = is_object($iterator) ? $iterator::class : gettype($iterator);
        $result = '<details><summary>'.$class.' [</summary>';
        foreach ($iterator as $key => $value) {
            $result .= '<span class="ldg-array-item"><span class="ldg-array-key">['.$this->resolve($key).']</span> => '.$this->resolve($value).'</span>';
        }

        return $result.'</details>]';
    }

    /**
     * Parses object as visualization.
     */
    private function parseObject(object $object): string
    {
        $class = $object::class;
        $result = '<details><summary>'.$class.' [</summary>';
        foreach (array_keys(get_class_vars($class)) as $property) {
            $result .= '<span class="ldg-property"><span class="ldg-property-name">'.$property.'</span> => '.$this->resolve($object->{$property} ?? null).'</span>';
        }

        return $result.'</details>]';
    }

    /**
     * Parses string as visualization.
     */
    private function parseString(string $string): string
    {
        return '<span class="ldg-string">"'.$string.'"</span>';
    }

    /**
     * Parses numeric value as visualization.
     */
    private function parseNumber(mixed $numeric): string
    {
        return '<span class="ldg-number">'.$numeric.'</span>';
    }

    /**
     * Parses boolean as visualization.
     */
    private function parseBool(bool $bool): string
    {
        $value = $bool ? 'true' : 'false';

        return '<span class="ldg-bool">'.$value.'</span>';
    }

    /**
     * Parses null as visualization.
     */
    private function parseNull(): string
    {
        return '<span class="ldg-null">null</span>';
    }
}
