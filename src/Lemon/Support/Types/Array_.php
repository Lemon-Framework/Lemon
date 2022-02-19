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

    public function current()
    {
        return $this->content[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        $this->position++;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function valid()
    {
        return isset($this->content[$this->position]);
    }

    public function offsetExists($offset)
    {
        return isset($this->content[$offset]);
    }

    public function offsetGet($offset)
    {
        if ($offset < 0) {
            $offset = $this->lenght - 1 + $offset;
        }
        if (isset($this->content[$offset])) {
            return $this->content[$offset];
        }
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->content[] = $value;
        } else {
            $this->content[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
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
     * 
     */
    public function pop()
    {
        array_pop($this->content);
        $this->lenght();
        return $this;
    }

    // regex for parsing functions to create docblocker because this is pain
    // dont mind this
    // (public\s+)?function\s+(\w+)\(((.*?)\s*(...)?$(.+?))+\)(:\s*(\w+))?
    public function lenght()
    {
        $this->lenght = sizeof($this->content);
        return $this->lenght;
    }

    public function json()
    {
        return json_encode($this->content);
    }

    public function export()
    {
        $parsed_arrays = new Array_();
        foreach ($this->content as $array) {
            $parsed_arrays->push($array instanceof Array_ ? $array->export() : $array);
        }

        return $parsed_arrays->content;
    }

    public function chunk(int $lenght)
    {
        $this->content = array_chunk($this->content, $lenght);
        return $this;
    }

    public function hasKey($key)
    {
        return array_key_exists($key, $this->content);
    }

    public function filter(callable $callback)
    {
        $this->content = array_filter($this->content, $callback);
        $this->lenght();
        return $this;
    }

    public function firstKey()
    {
        return array_key_first($this->content);
    }

    public function lastKey()
    {
        return array_key_last($this->content);
    }

    public function first()
    {
        return $this->content[$this->firstKey()];
    }

    public function last()
    {
        return $this->content[$this->lastKey()];
    }

    public function keys()
    {
        return new Array_(array_keys($this->content));
    }

    public function values()
    {
        return new Array_(array_values($this->content));
    }

    public function map(callable $callback)
    {
        $this->content = array_map($callback, $this->content);
        return $this;
    }

    public function merge(array|Array_ ...$arrays)
    {
        $arrays = (new Array_($arrays))->export();
        $this->content = array_merge($this->content, ...$arrays);
        $this->lenght();
        return $this;
    }

    public function random(int $count=1)
    {
        return array_rand($this->content, $count);
    }

    public function shuffle()
    {
        $this->content = shuffle($this->content);
        return $this;
    }

    public function replace(array|Array_ ...$replacements)
    {
        $replacements = (new Array_($replacements))->export();
        $this->content = array_replace($this->content, ...$replacements);
        $this->lenght();
        return $this;
    }

    public function reverse()
    {
        $this->content = array_reverse($this->content);
        return $this;
    }

    public function slice(int $start, int $lenght)
    {
        $this->content = array_slice($this->content, $start, $lenght);
        return $this;
    }

    public function sum()
    {
        return array_sum($this->content);
    }

    public function contains($needle)
    {
        return in_array($needle, $this->content);
    }

    public function has($needle)
    {
        return $this->contains($needle);
    }
}

