<?php

namespace Lemon\Support\Types;

use ArrayAccess;
use Exception;
use Iterator;

class Array_ implements Iterator, ArrayAccess
{
    /** 
     * Array content 
     * 
     * @var array
     */
    public array $content;

    /** 
     * Array lenght 
     *
     * @var int
     */
    public int $lenght;

    /** Iterating position */
    private int $position = 0;

    public function __construct(array|Array_ $content=[])
    {
        $content = $content instanceof Array_ ? $content->content : $content;
        $this->content = $content;
        $this->lenght = sizeof($content);
    }

    public function current(): mixed
    {
        return $this->content[$this->position];
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function next(): void
    {
        $this->position++;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return isset($this->content[$this->position]);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->content[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        if ($offset < 0) {
            $offset = $this->lenght - 1 + $offset;
        }
        if (isset($this->content[$offset])) {
            return $this->content[$offset];
        }
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->content[] = $value;
        } else {
            $this->content[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->content[$offset]);
    }

    public function __get($key)
    {
        if (!isset($this->content[$key])) {
            throw new Exception("Undefined array key $key");
        }
        return $this->content[$key];
    }

    public function __set($key, $value)
    {
        $this->content[$key] = $value;
    }

    /**
     * Pushes value to the top of array
     *
     * @param mixed ...$values 
     * @return self
     */
    public function push(...$values): self
    {
        array_push($this->content, ...$values);
        $this->lenght();
        return $this;
    }

    /**
     * Pops item from the top of array
     *
     * @return self
     */
    public function pop(): self
    {
        array_pop($this->content);
        $this->lenght();
        return $this;
    }

    /**
     * Returns size of array
     *
     * @return int
     */
    public function lenght(): int
    {
        $this->lenght = sizeof($this->content);
        return $this->lenght;
    }

    /**
     * Returns size of array
     *
     * @return int
     */
    public function size(): int
    {
        return $this->lenght;
    }

    /**
     * Converts array to json
     *
     * @return string
     */
    public function json(): string
    {
        return json_encode($this->content);
    }

    /**
     * Exports nested Array_ as regular array
     *
     * @return array
     */
    public function export(): array
    {
        $parsed_arrays = new Array_();
        foreach ($this->content as $array) {
            $parsed_arrays->push($array instanceof Array_ ? $array->export() : $array);
        }

        return $parsed_arrays->content;
    }

    /**
     * Splits array into chunks
     *
     * @param int $lenght
     * @return self
     */
    public function chunk(int $lenght): self
    {
        $this->content = array_chunk($this->content, $lenght);
        return $this;
    }

    /**
     * Determins whenever array contains given key
     *
     * @param mixed $key
     * @return bool
     */
    public function hasKey($key): bool
    {
        return array_key_exists($key, $this->content);
    }

    /**
     * Filters array by given callback
     *
     * @param callable $callback
     * @return self
     */
    public function filter(callable $callback): self
    {
        $this->content = array_filter($this->content, $callback);
        $this->lenght();
        return $this;
    }

    /**
     * Returns first key of array
     *
     * @return mixed
     */
    public function firstKey(): mixed
    {
        return array_key_first($this->content);
    }

    /**
     * Returns last key of array
     *
     * @return mixed
     */
    public function lastKey(): mixed
    {
        return array_key_last($this->content);
    }

    /**
     * Returns first item from array
     *
     * @return mixed
     */
    public function first(): mixed
    {
        return $this->content[$this->firstKey()];
    }

    /**
     * Returns last item from array
     *
     * @return mixed
     */
    public function last(): mixed
    {
        return $this->content[$this->lastKey()];
    }

    /**
     * Returns all array keys
     *
     * @return self
     */
    public function keys(): self
    {
        return new self(array_keys($this->content));
    }

    /**
     * Returns all array items
     *
     * @return self
     */
    public function values(): self
    {
        return new Array_(array_values($this->content));
    }

    public function items(): self
    {
        return $this->values();
    }

    /**
     * Applies given callback to each item of array
     *
     * @param callable $callback
     * @return self
     */
    public function map(callable $callback): self
    {
        $this->content = array_map($callback, $this->content);
        return $this;
    }

    /**
     * Merges all given arrays into curent
     *
     * @param array|Array_ ...$arrays
     * @return self
     */
    public function merge(array|Array_ ...$arrays): self
    {
        $arrays = (new Array_($arrays))->export();
        $this->content = array_merge($this->content, ...$arrays);
        $this->lenght();
        return $this;
    }

    /**
     * Returns random item from array
     *
     * @param int $count=1
     * @return mixed
     */
    public function random(int $count=1): mixed
    {
        return array_rand($this->content, $count);
    }

    /**
     * Randomly shuffles array
     *
     * @return self
     */
    public function shuffle(): self
    {
        $this->content = shuffle($this->content);
        return $this;
    }

    /**
     * Replaces elements from passed arrays into array
     *
     * @param array|Array_ ...$replacements
     * @return self
     */
    public function replace(array|Array_ ...$replacements): self
    {
        $replacements = (new self($replacements))->export();
        $this->content = array_replace($this->content, ...$replacements);
        $this->lenght();
        return $this;
    }

    /**
     * Puts item in array in reverse order
     *
     * @return self
     */
    public function reverse(): self
    {
        $this->content = array_reverse($this->content);
        return $this;
    }

    /**
     * Extract a slice of the array
     *
     * @param int $start
     * @param mixed int $lenght
     * @return void
     */
    public function slice(int $start, int $lenght)
    {
        $this->content = array_slice($this->content, $start, $lenght);
        return $this;
    }

    /**
     * Returns sum of array
     *
     * @return int|float
     */
    public function sum(): int|float
    {
        return array_sum($this->content);
    }

    /**
     * Determins whenever array contains given needle
     *
     * @param mixed $needle
     * @return bool
     */
    public function contains($needle): bool
    {
        return in_array($needle, $this->content);
    }

    /**
     * Determins whenever array contains given needle
     *
     * @param mixed $needle
     * @return bool
     */
    public function has($needle): bool
    {
        return $this->contains($needle);
    }
}

